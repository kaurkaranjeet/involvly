<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\Events\GroupEvent;
use App\Events\ReadEvent;
use App\Events\CustomGroupEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\ParentChildrens;
use App\Models\GroupMember;
use Carbon\Carbon;
use Pusher\Pusher;
use App\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class GroupController extends Controller {

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

    // Group List
    public function GroupList(Request $request) {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                        'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
            	$user=User::find($request->user_id);
            	$teachers = ParentChildrens::Join('users', 'users.id', '=', 'parent_childrens.children_id')
            	->select(DB::raw('group_concat(DISTINCT(school_id)) as schools'))
            	->where('parent_id', $user->id)->groupBy('parent_id')->first();
            	$classes = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')
            	->select(DB::raw('group_concat(DISTINCT(class_id)) as classes'))
            	->where('parent_id', $user->id)->groupBy('parent_id')->first();
            	$members= GroupMember::where('member_id',$request->user_id)->select(DB::raw('group_concat(DISTINCT(group_id)) as groups'))->first();
            	if(!empty($members->groups)){
            		$msql=' OR ( id IN('.$members->groups.'))';
            	}
            	else{
            		$msql='';
            	}
              if(!empty($teachers->schools)){
               $sql= Group::whereRaw("(type='parent_community' OR type='school' OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  ".$msql." ) AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as message_date");
               if(!empty($classes->classes)){
                 $sql= Group::whereRaw("(type='parent_community' OR type='school' OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  OR ( type='class_group' AND class_id IN('".$classes->classes."'))  ".$msql." )   AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as message_date");;
               }
             }else{
              $sql= Group::whereRaw("(type='parent_community' OR type='school' ".$msql." )  AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as message_date");;
            }
               $groups=$sql->get();
                 
               foreach($groups as $single_group){
               	if($single_group->type=='parent_community'){
               		$count= User::where('role_id',3)->where('join_community',1)->where('status',1)->count();
               		$unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $single_group->id)->count();
               		$single_group->member_count=$count;
               		$single_group->unread_count=$unread_count;
               	}
               	if($single_group->type=='school'){
               		$count= User::where('city',$user->city)->where('join_community',1)->where('status',1)->count();
               		$single_group->member_count=$count;
               		$unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $single_group->id)->count();
               		$single_group->unread_count=$unread_count;
               	}

               	if($single_group->type=='school_admin'){
               		$count=User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1)->count();
               		$single_group->member_count=$count;
               		$unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $single_group->id)->count();
               		$single_group->unread_count=$unread_count;
               	}
               	if($single_group->type=='custom_group'){
               		$count=GroupMember::where('group_id',$single_group->id)->count();
               		$single_group->member_count=$count;
               		$unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $single_group->id)->count();
               		$single_group->unread_count=$unread_count;
               	}
               	if($single_group->type=='class_group'){
               		$count = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')->Join('users', 'users.id', '=', 'parent_childrens.parent_id')
               		->select(DB::raw('Distinct count(parent_id))'))
               		->where('class_id', $single_group->class_id)->count();
               		$unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $single_group->id)->count();
               		$single_group->member_count=$count;
               		$single_group->unread_count=$unread_count;


               	}
           	if(!empty($single_group->message_date)){
               		$date = strtotime($single_group->message_date); 

               		$single_group->message_date =date('Y-m-d\TH:i:s.00000',$date).'Z';
               	}
               	else{
               		$single_group->message_date=null;
               	}

           

               }
                 return response()->json(array('error' => false, 'data' => $groups), 200);
               
          }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


     public function SendGroupMessage(Request $request) {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                      // 'message' => 'required',
                         'user_id' => 'required|exists:users,id',
                        'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
              $user=User::find($request->user_id);
              $random_number=rand();
              $limit=1;
              if($request->group_id=='1'){
                $users= User::where('role_id',3)->where('join_community',1)->where('status',1)->get();
               if($request->hasfile('images'))
               {
                $limit=count($request->file('images'));
                foreach($request->file('images') as $key=>$single)
                {
                   $random_number=rand();

                  $name=time().$key.'.'.$single->getClientOriginalExtension();    
                  $single->move(public_path().'/assignment_doc/', $name);      
                  $filename= URL::to('/').'/assignment_doc/'.$name; 
                  if($key>0){
                    $request->message='';
                  }
                  if(!empty($users)){
                
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->id,$request,$random_number,$filename);
                  } 
                }
              }
            }
            else{
            	 if(!empty($users)){
               foreach($users as $single){ 
                $this->Sendsinglemessage($single->id,$request,$random_number); 
            }
              }
            } 
          }
                elseif($request->group_id=='2'){
              $users= User::where('city',$user->city)->where('join_community',1)->where('status',1)->get();

              if($request->hasfile('images'))
               {
                   $limit=count($request->file('images'));
                foreach($request->file('images') as $key=>$single)
                {
                     $random_number=rand();
                  $name=time().$key.'.'.$single->getClientOriginalExtension();    
                  $single->move(public_path().'/assignment_doc/', $name);      
                  $filename= URL::to('/').'/assignment_doc/'.$name; 
                  if($key>0){
                    $request->message='';
                  }
                 if(!empty($users)){
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->id,$request,$random_number,$filename); 
              }
                }
              }
            }
            else{
            	 if(!empty($users)){
              foreach($users as $single){

                $this->Sendsinglemessage($single->id,$request,$random_number);
                                              }       
                                          }
            }

            }
           else if(Group::where('id',$request->group_id)->where('type','school_admin')->where('school_id',$user->school_id)->exists()){
             $users= User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1)->get();
             if($request->hasfile('images'))
               {
                   $limit=count($request->file('images'));
                foreach($request->file('images') as $key=>$file)
                {

                     $random_number=rand();
                  $name=time().$key.'.'.$file->getClientOriginalExtension();    
                  $file->move(public_path().'/assignment_doc/', $name);      
                  $filename= URL::to('/').'/assignment_doc/'.$name; 
                  if($key>0){
                    $request->message='';
                  }
                   if(!empty($users)){
                
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->id,$request,$random_number,$filename); 
                }
            }
              }
            }else{
            	 if(!empty($users)){
              foreach($users as $single){
               $this->Sendsinglemessage($single->id,$request,$random_number);
              
             }
         }
           }
           }

           else if(Group::where('id',$request->group_id)->where('type','class_group')->exists()){
             $class_id= Group::where('id',$request->group_id)->where('type','class_group')->first();           
             $users = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')
             ->select(DB::raw('DIstinct parent_childrens .parent_id'))->where('class_id', $class_id->class_id)->get();
             
             if($request->hasfile('images'))
             {
             	   $random_number=rand();
                 $limit=count($request->file('images'));
              foreach($request->file('images') as $key=>$file)
              {
                $name=time().$key.'.'.$file->getClientOriginalExtension();    
                $file->move(public_path().'/assignment_doc/', $name);      
                $filename= URL::to('/').'/assignment_doc/'.$name; 
                if($key>0){
                  $request->message='';
                }
                 if(!empty($users)){
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->parent_id,$request,$random_number,$filename); 
                }
            }
              }
            }   else{
            	 if(!empty($users)){
             foreach($users as $single){
               $this->Sendsinglemessage($single->parent_id,$request,$random_number);              
             }
         }
           }
         }

         else{


         	$class_id= Group::where('id',$request->group_id)->where('type','custom_group')->first();           
         	$users=GroupMember::where('group_id',$request->group_id)->get();
         	if($request->hasfile('images'))
         	{
         		$limit=count($request->file('images'));
         		foreach($request->file('images') as $key=>$file)
         		{

         			$random_number=rand();
         			$name=time().$key.'.'.$file->getClientOriginalExtension();    
         			$file->move(public_path().'/assignment_doc/', $name);      
         			$filename= URL::to('/').'/assignment_doc/'.$name; 
         			if($key>0){
         				$request->message='';
         			}
         			if(!empty($users)){
         				foreach($users as $single){ 
         					$this->Sendsinglemessage($single->member_id,$request,$random_number,$filename); 
         				}
         			}
         		}
         	}   else{
         		if(!empty($users)){
         			foreach($users as $single){
         				$this->Sendsinglemessage($single->member_id,$request,$random_number);              
         			}
         		}
         	}

       
         } 
  // Update user read message to yourself
         GroupMessage::where('from_user_id',$request->user_id)->where('to_user_id',$request->user_id)->update(['is_read'=>1]);
         $group_data= GroupMessage::selectRaw("group_messages.*,group_messages.created_at as message_date")->with('User')->where('group_id',$request->group_id)->where('from_user_id',$request->user_id)->where('to_user_id','!=',$request->user_id)->groupBy('group_number')->orderBy('id', 'DESC')->limit($limit)->get();
         $array=array('error' => false, 'data' => $group_data,'group_id' =>$request->group_id);
         $this->pusher->trigger('group-channel', 'group_user', $array);  
            // List Group
         $list_group=Group::selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as message_date")->where('id',$request->group_id)->first();

         	//$date = Carbon::parse($list_group->message_date); 
         	//$list_group->message_date = $date->diffForHumans();
         	$group_single=$this->CountGroups($list_group,$request->user_id);
         	$array1=array('data' => $group_single);
         	$this->pusher->trigger('list-channel', 'list_group', $array1);              
         return response()->json($array, 200);               
          }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


    public function Sendsinglemessage($to_user_id,$input,$random_number,$file_name=''){
      
          $groups=new GroupMessage;
          $groups->to_user_id=$to_user_id;
          $groups->from_user_id=$input->user_id;
          $groups->group_id=$input->group_id;

          $groups->message=$input->message;
          $groups->group_number=$random_number;
          $groups->is_read=0;
          $groups->file=$file_name;
             if(empty($groups->message)){
             	$groups->message= '';
             }
          $groups->save();
      //    $messsage='';
      

    }
     // Group Messages List
    public function GroupMessages(Request $request) {
      try {

        $input = $request->all();
        $validator = Validator::make($input, [
          'group_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
          $group_data= GroupMessage::with('User')->where('group_id',$request->group_id)->groupBy('group_number')->orderBy('id', 'ASC')->get();           
          $array=array('error' => false, 'data' => $group_data);
         return response()->json($array, 200);

       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

  // Group Messages List
    public function ReadGroupMessage(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
          'user_id' => 'required',
          'group_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
       GroupMessage::with('User')->where('group_id',$request->group_id)->where('to_user_id',$request->user_id)->update(['is_read'=>'1']);   
       $group_data= GroupMessage::with('User')->where('group_id',$request->group_id)->where('to_user_id',$request->user_id)->get();   
         $array=array('error' => false, 'data' => $group_data);
         $this->pusher->trigger('read-channel', 'read_group', $array);       
      return response()->json($array, 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Create custom Group
    public function CreateCustomGroup(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
         'user_id' => 'required|exists:users,id',
          'group_name' => 'required',
          'group_members' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
        	$groupobj=	new Group;
        	$groupobj->user_id=$request->user_id;
        	$groupobj->group_name=$request->group_name;
        	  
        	if($request->hasfile('group_icon'))
        	{  
        		$file = $request->file('group_icon');
        		$filename=trim($file->getClientOriginalName());
        		$file->move(public_path().'/images/',$filename) ; 
        		$file_name=URL::to('/').'/images/'.$filename;  
        		$groupobj->group_icon=$file_name;
        	}
        	$groupobj->type='custom_group';
        	$groupobj->status='1';
        	$groupobj->school_id=0;
        	$groupobj->class_id=0;
        	$groupobj->save();
        	if(!empty($request->group_members)){        		
        		$members=explode(',',$request->group_members);
        		array_push($members,$groupobj->user_id);
        		$members=array_unique($members);
        		foreach($members as $member_id){
        			$groupobjmember=new GroupMember;
        			$groupobjmember->member_id=$member_id;
        			$groupobjmember->group_id=$groupobj->id;        
        			$groupobjmember->save();
        		}
        	}
         // Send pusher Event
        	$count=GroupMember::where('group_id',$groupobj->id)->count();
        	$groupobj->member_count=$count;
        	$groupobj->unread_count=0;
        	$groupobj->last_message=null;
        	$groupobj->message_date=$groupobj->created_at;
        $this->pusher->trigger('custom-channel', 'custom_group', $groupobj);         
      return response()->json(array('error' => false, 'data' => $groupobj), 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


// Group Messages List
    public function CustomMembers(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
          'user_id' =>'required|exists:users,id',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else { 
         $usrobj=User::find($request->user_id);
         $group_data= User::where('role_id',3)->where('status',1)->where('school_id',$usrobj->school_id)->get();   
         $array=array('error' => false, 'data' => $group_data);
      return response()->json($array, 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


    public function CountGroups($list_group,$user_id){
    	$user=User::find($user_id);
    	if($list_group->type=='parent_community'){
               		$count= User::where('role_id',3)->where('join_community',1)->where('status',1)->count();
               		$unread_count=GroupMessage::where('to_user_id',$user_id)->where('is_read',0)->where('group_id', $list_group->id)->count();
               		$list_group->member_count=$count;
               		$list_group->unread_count=$unread_count;
               	}
               	if($list_group->type=='school'){
               		$count= User::where('city',$user->city)->where('join_community',1)->where('status',1)->count();
               		$list_group->member_count=$count;
               		$unread_count=GroupMessage::where('to_user_id',$user_id)->where('is_read',0)->where('group_id', $list_group->id)->count();
               		$list_group->unread_count=$unread_count;
               	}

               	if($list_group->type=='school_admin'){
               		$count=User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1)->count();
               		$list_group->member_count=$count;
               		$unread_count=GroupMessage::where('to_user_id',$user_id)->where('is_read',0)->where('group_id', $list_group->id)->count();
               		$list_group->unread_count=$unread_count;
               	}
               	if($list_group->type=='custom_group'){
               		$count=GroupMember::where('group_id',$list_group->id)->count();
               		$list_group->member_count=$count;
               		$unread_count=GroupMessage::where('to_user_id',$user_id)->where('is_read',0)->where('group_id', $list_group->id)->count();
               		$list_group->unread_count=$unread_count;
               	}
               	if($list_group->type=='class_group'){
               		$count = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')->Join('users', 'users.id', '=', 'parent_childrens.parent_id')
               		->select(DB::raw('Distinct count(parent_id))'))
               		->where('class_id', $list_group->class_id)->count();
               		$unread_count=GroupMessage::where('to_user_id',$user_id)->where('is_read',0)->where('group_id', $list_group->id)->count();
               		$list_group->member_count=$count;
               		$list_group->unread_count=$unread_count;
               	}

               	return $list_group;

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
           //  'message'=> 'required_if:file,==,0'
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
            $data->is_read = 0; // message will be unread when sending message
            $data->save();
           // $data->message_date = $data->created_at->diffForHumans();
            $data->User;      
            $this->pusher->trigger('chat-channel', 'chat_event', $data);  
         // prepare some data to send with the response
        $response = [
          'error' =>  true,
          'message'  =>'Message send successfully',
          'data' =>  $data

        ]; 
    }
}
      //catch exception
catch(Exception $e) {

    $response = [
        'success' => false,
        'message' =>  $e->getMessage(),
    ]; 

}
return response()->json($response);
}
 // Delete custom Group
    public function DeleteCustomGroup(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
         'user_id' => 'required|exists:users,id',
         'group_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
        $delete=Group::where('user_id',$request->user_id)->where('id',$request->group_id)->delete();
        if($delete){
        	      
      return response()->json(array('error' => false, 'data' => $delete), 200);
  } else{
  	    throw new Exception('You have not created this group');
  }
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }
}
