<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\Message;
use App\Models\ReportUser;
use App\Events\MessagesEvent;
use Pusher\Pusher;
use Carbon\Carbon;
use App\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class MessageController extends Controller {

     public function __construct() {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $this->pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
        );
    }

 /**
     * sendUserMessage
     *
     * @param Request $request
     */
      public function sendUserMessage(Request $request)
    {
        $input = $request->all();
        $response =[];
        try{
            $validator = Validator::make($input, [
             'from_user_id' => 'required|exists:users,id',
             'to_user_id' => 'required|exists:users,id',
         
            ]);
            if($validator->fails()){
             throw new Exception( $validator->errors());
         }  else{
          $from_user_id=$request->from_user_id;
           $to_user_id=$request->to_user_id;
           $query1=Message::where(function ($query) use ($from_user_id, $to_user_id) {
            $query->where('from_user_id', $to_user_id)->where('to_user_id', $from_user_id);
          })->oRwhere(function ($query) use ($from_user_id, $to_user_id) {
            $query->where('from_user_id', $from_user_id)->where('to_user_id', $to_user_id);
          });

          $response1=$query1->count();

         $data = new Message();
         $data->from_user_id =  $request->from_user_id;
         $data->to_user_id =  $request->to_user_id;
         $data->message = $request->message; 
         if(empty($data->message)){
         	$data->message='';
         } 
          if($request->hasfile('file'))
          {
             
             $name = rand().'.'.$request->file('file')->getClientOriginalExtension();
             $request->file('file')->move(public_path().'/files/', $name);              
             $data->file =URL::to('/').'/files/'.$name;;            
         }
            $data->is_read = 0; // message will be unread when sending message
            $data->save();
           // $date = Carbon::parse($data->created_at); 
          // $data->message_date = $date->diffForHumans();   
            $data->message_date = $data->created_at; 
            $data->User;      
            $array=array('error' => false, 'data' => $data);
            $this->pusher->trigger('chat-channel', 'chat_event', $array);

            $pusher_data= $data->User;
            $pusher_data->last_message= $data->message;
            $pusher_data->message_date= $data->created_at;
            $pusher_data->to_user_id= $data->to_user_id;
            if(!empty($data->file)){
            $pusher_data->file= $data->file;
          } else{
             $pusher_data->file= '';
          }
            $count= DB::select( DB::raw( "SELECT count(one_to_one_message.is_read) as  unread_count from one_to_one_message  WHERE one_to_one_message.is_read=0 and one_to_one_message.from_user_id= ".$request->from_user_id." AND one_to_one_message.to_user_id=".$request->to_user_id." group by one_to_one_message.from_user_id "));
            if(!empty($count[0])){
             $pusher_data->unread_count=$count[0]->unread_count;
           }else{
            $pusher_data->unread_count=0;
          }

           $array_new=array('error' => false, 'data' => $pusher_data);
          


          if($response1>0){
            $this->pusher->trigger('usermassage-channel', 'listuser_event', $array_new);
          }else{
            $this->pusher->trigger('first-channel', 'first_event', $array_new);
          }

           
      

         // prepare some data to send with the response
        $response = [
          'error' =>  false,
          'message'  =>'Message send successfully',
          'data' =>  $data,
          'pusher_data' => $pusher_data
         

        ]; 
    }
}
      //catch exception
catch(Exception $e) {
    $response = [
        'error' => true,
        'message' =>  $e->getMessage(),
    ]; 

}
return response()->json($response);
}


/**
     * Chat History
     *
     * we will fetch the users  recently  I chatted from the request
     *
     * @param Request $request
     */
  public function GetHistory(Request $request){
    $input = $request->all();
    $response =[];
    try{
      $validator = Validator::make($input, [
        'user_id' => 'required'
      ]);
      if($validator->fails()){
       throw new Exception( $validator->errors());
     }  else{

      $user_id=$request->user_id;
      
      $results = DB::select( DB::raw("SELECT m1.created_at as message_date,(SELECT message FROM one_to_one_message WHERE (NOT FIND_IN_SET(".$user_id.",m1.deleted_by_members) OR m1.deleted_by_members IS NULL) AND id=m1.id ) as last_message,(SELECT file FROM one_to_one_message WHERE (NOT FIND_IN_SET(".$user_id.",m1.deleted_by_members) OR m1.deleted_by_members IS NULL) AND id=m1.id ) as file,u1.*,if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) as user_id ,(SELECT count(one_to_one_message.is_read) from one_to_one_message  WHERE one_to_one_message.is_read=0 and one_to_one_message.from_user_id=m1.from_user_id AND one_to_one_message.to_user_id=".$user_id." group by one_to_one_message.from_user_id) as  unread_count FROM one_to_one_message m1 LEFT JOIN one_to_one_message m2 ON (CONCAT(GREATEST(m1.from_user_id,m1.to_user_id),' ',LEAST(m1.from_user_id,m1.to_user_id)) = CONCAT(GREATEST(m2.from_user_id,m2.to_user_id),' ',LEAST(m2.from_user_id,m2.to_user_id)) AND m1.id < m2.id) JOIN users u1 on u1.id=if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) WHERE m2.id IS NULL AND (m1.from_user_id=".$user_id." or m1.to_user_id=".$user_id.") AND  u1.id NOT IN( Select to_user_id FROM report_users WHERE from_user_id=".$user_id.") AND  u1.id NOT IN( Select from_user_id FROM report_users WHERE to_user_id=".$user_id.") AND  u1.role_id=4 ORDER BY m1.created_at DESC") );
      if($results ){       

        foreach($results as $data){
          if(!empty($data->message_date)){
          $date = strtotime($data->message_date); 
          $data->message_date =date('Y-m-d\TH:i:s.00000',$date).'Z';
        }
        else{
          $data->message_date =null;
        }
       if($data->unread_count==null){ $data->unread_count=0;}
       }

   }



    $parents = DB::select( DB::raw("SELECT m1.created_at as message_date,(SELECT message FROM one_to_one_message WHERE (NOT FIND_IN_SET(".$user_id.",m1.deleted_by_members) OR m1.deleted_by_members IS NULL) AND id=m1.id ) as last_message,(SELECT file FROM one_to_one_message WHERE (NOT FIND_IN_SET(".$user_id.",m1.deleted_by_members) OR m1.deleted_by_members IS NULL) AND id=m1.id ) as file,u1.*,if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) as user_id ,(SELECT count(one_to_one_message.is_read) from one_to_one_message  WHERE one_to_one_message.is_read=0 and one_to_one_message.from_user_id=m1.from_user_id AND one_to_one_message.to_user_id=".$user_id." group by one_to_one_message.from_user_id) as  unread_count FROM one_to_one_message m1 LEFT JOIN one_to_one_message m2 ON (CONCAT(GREATEST(m1.from_user_id,m1.to_user_id),' ',LEAST(m1.from_user_id,m1.to_user_id)) = CONCAT(GREATEST(m2.from_user_id,m2.to_user_id),' ',LEAST(m2.from_user_id,m2.to_user_id)) AND m1.id < m2.id) JOIN users u1 on u1.id=if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) WHERE m2.id IS NULL AND (m1.from_user_id=".$user_id." or m1.to_user_id=".$user_id.") AND  u1.id NOT IN( Select to_user_id FROM report_users WHERE from_user_id=".$user_id.") AND  u1.id NOT IN( Select from_user_id FROM report_users WHERE to_user_id=".$user_id.") AND  u1.role_id=3 ORDER BY m1.created_at DESC") );
      if($parents ){       

        foreach($parents as $data){
          if(!empty($data->message_date)){
          $date = strtotime($data->message_date); 
          $data->message_date =date('Y-m-d\TH:i:s.00000',$date).'Z';
        }
        else{
          $data->message_date =null;
        }
       if($data->unread_count==null){ $data->unread_count=0;}
       }

   }
   if(!empty($results) ||!empty($parents) ) {
       $response = [
        'error' => false,
        'message' =>  'Record found',
        'data' =>  $results,
        'parents' =>  $parents,

      ];
  }
    
    else{
     throw new Exception("Record not found");
   }


 }
}
      //catch exception
catch(Exception $e) {

  $response = [
    'error' => true,
    'message' =>  $e->getMessage(),
  ]; 

}

return response()->json($response );

}


public function chatList(Request $request)
 {
  $input = $request->all();
  $response =[];
  try{
    $validator = Validator::make($input, [
      'from_user_id' => 'required',
      'to_user_id' => 'required',
      // 'page' => 'required'
    ]);
    if($validator->fails()){
     throw new Exception( $validator->errors());
   }  else{
    $from_user_id=$request->from_user_id;
    $to_user_id=$request->to_user_id;
    //Update read status 
    DB::enableQueryLog(); 
    $query1=Message::with('User:id,name,email,profile_image')->select('message','from_user_id','to_user_id','created_at as message_date','is_read','id','file','deleted_by_members')->where(function ($query) use ($from_user_id, $to_user_id) {
      $query->where('from_user_id', $to_user_id)->where('to_user_id', $from_user_id)->whereRaw('  id NOT IN(select id from one_to_one_message as l 
where FIND_IN_SET('.$from_user_id.', l.deleted_by_members))');
    })->oRwhere(function ($query) use ($from_user_id, $to_user_id) {
      $query->where('from_user_id', $from_user_id)->where('to_user_id', $to_user_id)->whereRaw('  id NOT IN(select id from one_to_one_message as l 
where FIND_IN_SET('.$from_user_id.', l.deleted_by_members))');
    });
/*
    SELECT * FROM one_to_one_message WHERE 
(from_user_id=190 AND to_user_id=242) OR
 (from_user_id=242 AND to_user_id=190) and 
id NOT IN(SELECT id FROM one_to_one_message as l 
WHERE FIND_IN_SET(190, l.deleted_by_members))
*/

     // $perPage=10;
 /*   $page = $request->page;
    $start = ($page-1)*$perPage;   

    if($start < 0) $start = 0;*/

   $count= $query1->count();
/* $total_pages = ceil($count / $perPage);
 if($page>$total_pages){
     throw new Exception("Invalid page number");
 }*/


    $results =  $query1->get();

    // dd(DB::getQueryLog());
    if($results ){
      foreach($results as $key=>$data){
       
     
      if(!empty($data->message_date)){
          $date = strtotime($data->message_date); 
          $data->message_date =date('Y-m-d\TH:i:s.00000',$date).'Z';


        }
        else{
          $data->message_date =null;
        }
      }
    

      
        
      
      $response = [
        'error' => false,
        'message' =>  'Record found',
        'data' =>  $results,
        //'current_page' =>$page,
        //'total_pages'=>$total_pages,
        'total' =>$count,

      ];
    }
    else{
     throw new Exception("Record not found");
   }
   
   
 }
}
      //catch exception
catch(Exception $e) {
  $response = [
    'error' => true,
    'message' =>  $e->getMessage(),
  ]; 
}
return response()->json($response);     
}

 // Group Messages List
    public function ReadMessage(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
          'from_user_id' => 'required',
          'to_user_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
         Message::with('User')->where('to_user_id',$request->to_user_id)->where('from_user_id',$request->from_user_id)->update(['is_read'=>'1']);   
         $from_user_id=$request->from_user_id;
         $to_user_id=$request->to_user_id;
         $query1=Message::with('User')->select('is_read','id')->where(function ($query) use ($from_user_id, $to_user_id) {
          $query->where('from_user_id', $from_user_id)->where('to_user_id', $to_user_id);
        })->oRwhere(function ($query) use ($from_user_id, $to_user_id) {
          $query->where('from_user_id', $to_user_id)->where('to_user_id', $from_user_id);
        });
      $results =  $query1->get();
      

         $array=array('error' => false, 'data' => $results);
         $this->pusher->trigger('read-message', 'single_message', $array);       
      return response()->json($array, 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

     // Group Messages List
    public function ReportUser(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
          'from_user_id' => 'required',
          'to_user_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
        $report_user= new ReportUser;
        $report_user->from_user_id=$request->from_user_id;
        $report_user->to_user_id=$request->to_user_id;
        $report_user->text_description='';
        $report_user->save();
        $array=array('error' => false, 'data' => $report_user);
        // $this->pusher->trigger('read-message', 'single_message', $array);       
       return response()->json($array, 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

 // Group Messages List
    public function ClearChat(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
          'from_user_id' => 'required',
          'to_user_id' => 'required'
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
     $from_user_id=$request->from_user_id;
         $to_user_id=$request->to_user_id;
         $query1=Message::where(function ($query) use ($from_user_id, $to_user_id) {
          $query->where('from_user_id', $from_user_id)->where('to_user_id', $to_user_id);
        })->oRwhere(function ($query) use ($from_user_id, $to_user_id) {
          $query->where('from_user_id', $to_user_id)->where('to_user_id', $from_user_id);
        });


       $update= $query1->update(['deleted_by_members' => DB::raw("IFNULL(CONCAT(deleted_by_members, '," . $request->from_user_id . "')," . $request->from_user_id . ")")]);

         
        $array=array('error' => false, 'data' => $update);       
       return response()->json($array, 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


}
