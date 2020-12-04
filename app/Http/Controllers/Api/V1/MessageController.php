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

         $data = new Message();
         $data->from_user_id =  $request->from_user_id;
         $data->to_user_id =  $request->to_user_id;
         $data->message = $request->message; 
         if(empty($data->message)){
         	$data->message='';
         } 
          if($request->hasfile('file'))
          {
             
             $name = time().'.'.$request->file('file')->getClientOriginalExtension();
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
            $pusher_data->message_date= $data->message_date;
            $pusher_data->unread_count=0;
            $array=array('error' => false, 'data' => $pusher_data);
            $this->pusher->trigger('usermassage-channel', 'listuser_event', $array);

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
      
      $results = DB::select( DB::raw("SELECT m1.message as last_message,m1.created_at as message_date,u1.*,if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) as user_id ,(SELECT count(one_to_one_message.is_read) from one_to_one_message  WHERE one_to_one_message.is_read=0 and one_to_one_message.from_user_id=m1.from_user_id AND one_to_one_message.to_user_id=".$user_id." group by one_to_one_message.from_user_id) as  unread_count FROM one_to_one_message m1 LEFT JOIN one_to_one_message m2 ON (CONCAT(GREATEST(m1.from_user_id,m1.to_user_id),' ',LEAST(m1.from_user_id,m1.to_user_id)) = CONCAT(GREATEST(m2.from_user_id,m2.to_user_id),' ',LEAST(m2.from_user_id,m2.to_user_id)) AND m1.id < m2.id) JOIN users u1 on u1.id=if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) WHERE m2.id IS NULL AND (m1.from_user_id=".$user_id." or m1.to_user_id=".$user_id.") ORDER BY m1.created_at") );
      if($results ){       

        foreach($results as $data){
        // $date = Carbon::parse($data->message_date); 
        // $data->message_date = $date->diffForHumans();
         if($data->unread_count==null) $data->unread_count=0;
       }
       $response = [
        'error' => false,
        'message' =>  'Record found',
        'data' =>  $results,

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
     Message::where(['from_user_id' => $to_user_id, 'to_user_id' => $from_user_id])->update(['is_read' => 1]);

    $query1=Message::with('User')->select('message','from_user_id','to_user_id','created_at','is_read','updated_at','id','file')->where(function ($query) use ($from_user_id, $to_user_id) {
      $query->where('from_user_id', $from_user_id)->where('to_user_id', $to_user_id);
    })->oRwhere(function ($query) use ($from_user_id, $to_user_id) {
      $query->where('from_user_id', $to_user_id)->where('to_user_id', $from_user_id);
    });
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
    if($results ){
      foreach($results as $key=>$data){
        $date = Carbon::parse($data->created_at); 
        $data->message_date = $date->diffForHumans();
        $data->fromUserName = $data->User->name;
          // $data->key=$key; 
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
       $group_data= Message::with('User')->where('to_user_id',$request->to_user_id)->where('from_user_id',$request->from_user_id)->get();   
         $array=array('error' => false, 'data' => $group_data);
         $this->pusher->trigger('read-message', 'single_message', $array);       
      return response()->json($array, 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

}
