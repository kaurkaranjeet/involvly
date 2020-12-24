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
use App\Models\GroupDiscussion;
use App\Models\DiscussionComment;
use App\Models\DiscussionsLike;
use App\Models\ParentChildrens;
use App\Models\GroupMember;
use App\Models\ReportGroup;
use App\Models\DiscussionCommentReply;

use App\Models\ClearChatGroup;
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

              if(!empty($user->exit_groups)){
                $msql1=' AND ( id NOT IN('.$user->exit_groups.'))';
              }
              else{
                $msql1='';
              }


              $clear_chat_groups= ClearChatGroup::where('user_id',$request->user_id)->select(DB::raw('group_concat(DISTINCT(group_number)) as group_number'))->first();
              if(!empty($clear_chat_groups->group_number)){
                $group_number_sql=' AND  ( group_number NOT IN('.$clear_chat_groups->group_number.'))';
              }
              else{
                $group_number_sql='';
              }
              if(!empty($teachers->schools)){
               $sql= Group::whereRaw(" ((type='parent_community' AND 
  EXISTS (SELECT join_community from users WHERE id='".$user->id."' 
AND join_community=1)) OR (type='school' AND EXISTS (SELECT join_community from users WHERE id='".$user->id."' AND join_community=1)) OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  ".$msql."  )  ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id
)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."  ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id ".$group_number_sql." ORDER by id DESC limit 1) as file");
               if(!empty($classes->classes)){
                 $sql= Group::whereRaw(" ((type='parent_community' AND 
  EXISTS (SELECT join_community from users WHERE id='".$user->id."' 
AND join_community=1)) OR (type='school' AND EXISTS (SELECT join_community from users WHERE id='".$user->id."' AND join_community=1)) OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  OR ( type='class_group' AND class_id IN('".$classes->classes."'))  ".$msql." )  ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ".$group_number_sql."  ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as file");
               }
             }else{
              $sql= Group::whereRaw(" ((type='parent_community' AND 
  EXISTS (SELECT join_community from users WHERE id='".$user->id."' AND join_community=1)) OR (type='school' AND EXISTS (SELECT join_community from users WHERE id='".$user->id."' AND join_community=1))  ".$msql." )  ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id
)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."  ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as file");
            }
   $groups=$sql->orderBy('message_date', 'DESC')->orderBy(DB::raw( '  FIELD(type, "custom_group", "parent_community", "school","school_admin", "class_group") '))->orderBy('created_at', 'DESC')->get();
/*select  groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message,
(SELECT created_at FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as message_date from `groups`
 where  ((type='parent_community' AND   EXISTS (SELECT join_community from users WHERE id='242' AND join_community=1))
 OR type='school' OR ( type='school_admin' AND school_id IN('1'))  OR ( type='class_group' AND class_id IN('11'))  
 OR ( id IN(84,85,86,87)) )   AND status=1  ORDER BY FIELD(type, "custom_group", "parent_community", "school","school_admin",
 "class_group")   asc ,created_at DESC*/
                 
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

     // Group List
    public function GroupListNew(Request $request) {
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
                $msql='  ( id IN('.$members->groups.'))  OR  view_status=\'public\'';
              }
              else{
                $msql='  view_status=\'public\'';
              }

              if(!empty($user->exit_groups)){
                $msql1=' AND ( id NOT IN('.$user->exit_groups.'))';
              }
              else{
                $msql1='';
              }


              $clear_chat_groups= ClearChatGroup::where('user_id',$request->user_id)->select(DB::raw('group_concat(DISTINCT(group_number)) as group_number'))->first();
              if(!empty($clear_chat_groups->group_number)){
                $group_number_sql=' AND  ( group_number NOT IN('.$clear_chat_groups->group_number.'))';
              }
              else{
                $group_number_sql='';
              }
            if(!empty($teachers->schools)){
               $sql= Group::whereRaw(" ((type='parent_community' AND 
  EXISTS (SELECT join_community from users WHERE id='".$user->id."' 
AND join_community=1))  OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  )   AND ( ".$msql." )  ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id
)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."  ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id ".$group_number_sql." ORDER by id DESC limit 1) as file");
               /*  if(!empty($classes->classes)){
                 $sql= Group::whereRaw(" ((type='parent_community' AND 
  EXISTS (SELECT join_community from users WHERE id='".$user->id."' 
AND join_community=1)) OR (type='school' AND EXISTS (SELECT join_community from users WHERE id='".$user->id."' AND join_community=1)) OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  OR ( type='class_group' AND class_id IN('".$classes->classes."'))  ".$msql." )  ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ".$group_number_sql."  ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as file");
               }*/
             }else{
              $sql= Group::whereRaw(" ((type='parent_community' AND 
  EXISTS (SELECT join_community from users WHERE id='".$user->id."' AND join_community=1))  )   AND ( ".$msql." )  ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id
)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."  ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as file");
           }

$groups=$sql->orderBy('message_date', 'DESC')->orderBy(DB::raw( '  FIELD(type, "custom_group", "parent_community", "school","school_admin", "class_group") '))->orderBy('created_at', 'DESC')->get();

   $sql_oo= Group::whereRaw("type='custom_group' AND  group_category='community_group'   AND ( ".$msql." )  ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id
)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."  ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as file")->orderBy('message_date', 'DESC')->get();

 $digital_learning= Group::whereRaw("type='custom_group' AND  group_category='digital_learning'   AND ( ".$msql."  ) ".$msql1." AND status=1 AND NOT EXISTS (SELECT id FROM report_groups WHERE user_id = '".$user->id."' AND group_id = groups.id
)")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id  ".$group_number_sql."  ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id  ".$group_number_sql." ORDER by id DESC limit 1) as file")->orderBy('message_date', 'DESC')->get();
                 
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

               foreach($sql_oo as $single_group){
            
                $count=GroupMember::where('group_id',$single_group->id)->count();
                  $single_group->member_count=$count;
                  $unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $single_group->id)->count();
                  $single_group->unread_count=$unread_count;
           
                
            if(!empty($single_group->message_date)){
                  $date = strtotime($single_group->message_date); 

                  $single_group->message_date =date('Y-m-d\TH:i:s.00000',$date).'Z';
                }
                else{
                  $single_group->message_date=null;
                }
             }

             foreach($digital_learning as $single_group){
               if($single_group->type=='parent_community'){
                $count=GroupMember::where('group_id',$single_group->id)->count();
                  $single_group->member_count=$count;
                  $unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $single_group->id)->count();
                  $single_group->unread_count=$unread_count;
           
                
            if(!empty($single_group->message_date)){
                  $date = strtotime($single_group->message_date); 

                  $single_group->message_date =date('Y-m-d\TH:i:s.00000',$date).'Z';
                }
                else{
                  $single_group->message_date=null;
                }



               }
             }

           

                 return response()->json(array('error' => false, 'data' => $groups, 'digital_learning' => $digital_learning ,'community_group' => $sql_oo), 200);
               
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
                $users= User::where('role_id',3)->where('join_community',1)->where('status',1)->whereRaw(' NOT EXISTS (SELECT id FROM report_groups WHERE user_id = users.id AND group_id ='.$request->group_id.')')->get();
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
              $users= User::where('city',$user->city)->where('join_community',1)->where('status',1)->whereRaw(' NOT EXISTS (SELECT id FROM report_groups WHERE user_id = users.id AND group_id ='.$request->group_id.')')->get();

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
             $users= User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1)->whereRaw(' NOT EXISTS (SELECT id FROM report_groups WHERE user_id = users.id AND group_id ='.$request->group_id.')')->get();
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
             ->select(DB::raw('DIstinct parent_childrens .parent_id'))->where('class_id', $class_id->class_id)->whereRaw(' NOT EXISTS (SELECT id FROM report_groups WHERE user_id = users.id AND group_id ='.$request->group_id.')')->get();
             
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
    $group_data= GroupMessage::selectRaw("group_messages.*,group_messages.created_at as message_date, (CASE when (SELECT COUNT(id) from group_messages as l WHERE l.is_read=0 AND l.group_number= group_messages.group_number) > 0 THEN 0 ELSE 1 END) as read_number")->with('User:id,name,email,profile_image,role_id')->where('group_id',$request->group_id)->where('from_user_id',$request->user_id)->where('to_user_id','!=',$request->user_id)->groupBy('group_number')->orderBy('id', 'DESC')->limit($limit)->get();
         $array=array('error' => false, 'data' => $group_data,'group_id' =>$request->group_id,);
         $this->pusher->trigger('group-channel', 'group_user', $array);  
            // List Group
         $list_group=Group::selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message,(SELECT created_at FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as message_date,(SELECT file FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as file")->where('id',$request->group_id)->first();

         	//$date = Carbon::parse($list_group->message_date); 
         	//$list_group->message_date = $date->diffForHumans();
         	$group_single=$this->CountGroups($list_group,$request->user_id);
         		if(!empty($group_single->message_date)){
               		$date = strtotime($group_single->message_date); 

               		$group_single->message_date =date('Y-m-d\TH:i:s.00000',$date).'Z';
               	}
               	else{
               		$group_single->message_date=null;
               	}
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
          'user_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
      //  select (CASE when (SELECT COUNT(id) from group_messages as l WHERE l.is_read=0 AND l.group_number= group_messages.group_number) > 0 THEN 0 ELSE 1 END) as read_number ,group_messages.* from `group_messages` where `group_id` = 1 group by `group_number` order by `id` asc
           $group_info=Group::where('id',$request->group_id)->first();
            $count_member=   $this->CountGroups($group_info,$request->user_id);
       if($count_member->member_count>1){

        $group_data= GroupMessage::selectRaw(" (CASE when (SELECT COUNT(id) from group_messages as l WHERE l.is_read=0 AND l.group_number= group_messages.group_number) > 0 THEN 0 ELSE 1 END) as read_number ,group_messages.*")->with('User:id,name,email,profile_image,role_id')->where('group_id',$request->group_id)->whereRaw("group_number NOT IN( Select group_number FROM clear_chat_groups WHERE user_id=".$request->user_id." AND group_number=clear_chat_groups.group_number)" )->groupBy('group_number')->orderBy('id', 'ASC')->get();  
        }else{
          $group_data= GroupMessage::selectRaw(" (CASE when (SELECT COUNT(id) from group_messages as l WHERE l.is_read=0 AND l.group_number= group_messages.group_number) > 0 THEN 0 ELSE 0 END) as read_number ,group_messages.*")->with('User:id,name,email,profile_image,role_id')->where('group_id',$request->group_id)->whereRaw("group_number NOT IN( Select group_number FROM clear_chat_groups WHERE user_id=".$request->user_id." AND group_number=clear_chat_groups.group_number)" )->groupBy('group_number')->orderBy('id', 'ASC')->get(); 

        }         
          $array=array('error' => false, 'data' => $group_data,'group_id' => $request->group_id);
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
          $group_info=Group::where('id',$request->group_id)->first();
       $count_member=   $this->CountGroups($group_info,$request->user_id);
       if($count_member->member_count>1){
         GroupMessage::where('group_id',$request->group_id)->where('to_user_id',$request->user_id)->update(['is_read'=>'1']);
       }
       $group_data=DB::select( DB::raw("sELECT (CASE when (SELECT COUNT(id)  from group_messages WHERE is_read=0 AND group_number=	g.group_number) > 0 THEN 0 ELSE 1 END) as read_number , group_number from group_messages as g where group_id=" .$request->group_id." GROUP by group_number order by id ASc" ));


         $array=array('error' => false, 'data' => $group_data);
         $this->pusher->trigger('read-channel', 'read_group', $array);       
      return response()->json($array, 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


// Group Detail API
    public function GroupDetail(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
          'user_id' => 'required',
          'group_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
        	$is_joined=0;
          $group_info=Group::where('id',$request->group_id)->first();
          $user=User::find($request->user_id);
          if($group_info->type=='parent_community'){
            $count= User::where('role_id',3)->where('join_community',1)->where('status',1);
            $unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $group_info->id)->count();
            if($user->role_id==3 && $user->join_community==1){
            	$is_joined=1;
            }
              $group_info->is_joined=$is_joined;
            $group_info->member_count=$count->count();
             $group_info->unread_count=$unread_count;
            $group_info->members=$count->get();
           
          } 
          if($group_info->type=='school_admin'){
            $count=User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1);
            $group_info->member_count=$count->count();
            $is_joined=0;
            if($user->role_id==3){
            	$is_joined=1;
            }
            $unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $group_info->id)->count();
               $group_info->is_joined=$is_joined;
            $group_info->unread_count=$unread_count;
            $group_info->members=$count->get();
          }
          if($group_info->type=='custom_group'){

            $users = GroupMember::join('users', 'group_members.member_id', '=', 'users.id')
            ->select("users.*")->where('group_id',$group_info->id);
           // $count=GroupMember::where('group_id',$group_info->id);
            $group_info->member_count=$users->count();
             $unread_count=GroupMessage::where('to_user_id',$request->user_id)->where('is_read',0)->where('group_id', $group_info->id)->count();
             $is_joined=GroupMember::where('group_id',$group_info->id)->where('member_id',$request->user_id)->count();
            $group_info->unread_count=$unread_count;
            $group_info->members=$users->get();
             $group_info->is_joined=$is_joined;
           
          }
          $array=array('error' => false, 'data' => $group_info);

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
         'group_description' => 'required',
         'group_category' => 'required',
         'view_status' => 'required',
        // 'group_members' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
        	$groupobj=	new Group;
        	$groupobj->user_id=$request->user_id;
        	$groupobj->group_name=$request->group_name;
          $groupobj->group_description=$request->group_description;
          $groupobj->group_category=$request->group_category;
          $groupobj->view_status=$request->view_status;
        	  
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
        	$groupobjmember=new GroupMember;
        	$groupobjmember->member_id=$request->user_id;
        	$groupobjmember->group_id=$groupobj->id;        
        	$groupobjmember->save();
        	
         // Send pusher Event
        	//$count=GroupMember::where('group_id',$groupobj->id)->count();
        	$groupobj->member_count=1;
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



// Create custom Group
    public function AddMemberOfGroup(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
         'member_id' => 'required|exists:users,id',
         'group_id' => 'required|exists:groups,id'
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {        	
        
        			$groupobjmember=new GroupMember;
        			$groupobjmember->member_id=$request->member_id;
        			$groupobjmember->group_id=$request->group_id;        
        			$groupobjmember->save();

           $this->pusher->trigger('member-channel', 'add_member', $groupobjmember);
        		
        	}
                 
      return response()->json(array('error' => false, 'data' => $groupobjmember), 200);
       }
      catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


     public function LikeUnlikeDiscussion(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required|exists:users,id',
        'discussion_id' => 'required|exists:group_discussions,id',
        'like' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
    }
    else{
    $like_unlike_id = DiscussionsLike::where('user_id', $request->user_id)->where('discussion_id', $request->discussion_id)->first();
    if(!empty($like_unlike_id)){
    /* $notify_id=1;
     if($like_unlike_id->like!=$request->like){
      $notify_id=0;
    }*/
     $flight = DiscussionsLike::find($like_unlike_id->id);
  } 
  else
  {
  //$notify_id=0;
  $flight= new DiscussionsLike;//then create new object
}
    $flight->user_id=$request->user_id;
    $flight->discussion_id=$request->discussion_id;
    $flight->like=$request->like;
    $flight->save();
  // total likes

        if($request->like=='1'){

         $like='liked';
         $this->pusher->trigger('like-discussion', 'like_discussion', $flight);
       }
       else{
       $like='disliked';
         $this->pusher->trigger('like-discussion', 'dislike_discussion', $flight);
       }

       $posts = GroupDiscussion::select((DB::raw("( CASE WHEN EXISTS (
        SELECT *
        FROM discussions_like
        WHERE group_discussions.id = discussions_like.discussion_id
        AND discussions_like.user_id = ".$request->user_id."  AND discussions_like.like = 1
        ) THEN TRUE
        ELSE FALSE END)
        AS is_like,group_discussions.*")))->with('User')->withCount('likes','comments')->where('id', $request->discussion_id)->orderBy('id', 'DESC')->first();
       $this->pusher->trigger('count-discussions', 'discussion_count', $posts);
      /* $post_user=GroupDiscussion::with('User')->where('id',$request->discussion_id)->first();
        // send notification    
       if($post_user->User->id!=$request->user_id){ 

         $message= $flight->User->name .' has '.$like.' your discussion.';
         if(!empty($post_user->User->device_token)){
          SendAllNotification($post_user->User->device_token,$message,'social_notification');        
        }

      Notification::create(['user_id'=>$post_user->User->id,'notification_message'=>$message,'type'=>'social_notification','notification_type'=>'like','from_user_id'=>$request->user_id]); 
       } */
       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);


}
}


public function GetComments(Request $request){
    $validator = Validator::make($request->all(), [
        'discussion_id' => 'required|exists:group_discussions,id'
    ]);
    if($validator->fails()){
        return response()->json(array('errors' => $validator->errors(),'error' => true));
    }
    else{


    $comments= DiscussionComment::with('User')->/*withCount('replycomments')->with('replycomments.User')->*/where('discussion_id' , $request->discussion_id)->get();

    return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $comments), 200);

    }

}

	  public function AddDiscussionComments(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required|exists:users,id',
        'discussion_id' => 'required|exists:group_discussions,id',
        'comment' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
    }
    else{
    
    $flight= new DiscussionComment;//then create new object
    $flight->user_id=$request->user_id;
    $flight->discussion_id=$request->discussion_id;
    $flight->comment=$request->comment;
    $flight->save();
    $flight->name= $flight->User->name;
    // total comments
        $results1 = DB::select( DB::raw("select count(*) as total_comments from `group_discussion_comments` where `discussion_id` = ".$request->discussion_id."") );
        if(isset($results1[0])){
            $flight->total_comments=(int)$results1[0]->total_comments;
        }
        else{
             $flight->total_comments=0;
        }

        $flight->user_id=(int) $request->user_id;
        $flight->discussion_id=(int) $request->discussion_id;

        $comments=  DiscussionComment::with('User')->where('id' , $flight->id)->first();
    //    $comments->comment_id=(int) $comments->comment_id;
        $post_user=GroupDiscussion::with('User')->where('id',$request->discussion_id)->first();
        // send notification         
        $message= $comments->User->name .' has commented on your discussion.';

       /* if($post_user->user->id!=$request->user_id){
         if(!empty($post_user->User->device_token)){
          SendAllNotification($post_user->User->device_token,$message,'social_notification');          
        }
        Notification::create(['user_id'=>$post_user->User->id,'from_user_id'=>$request->user_id,'notification_message'=>$message,'type'=>'social_notification','notification_type'=>'comment']);
      }*/

        $this->pusher->trigger('discuss-channel', 'discuss_comment', $comments);

    $posts = GroupDiscussion::select((DB::raw("( CASE WHEN EXISTS (
      SELECT *
      FROM discussions_like
      WHERE group_discussions.id = discussions_like.discussion_id
      AND discussions_like.user_id = ".$request->user_id."  AND discussions_like.like = 1
      ) THEN TRUE
      ELSE FALSE END)
      AS is_like,group_discussions.*")))->with('User')->withCount('likes','comments')->where('id', $request->discussion_id)->orderBy('id', 'DESC')->first();

    $this->pusher->trigger('count-discussions', 'comment_count_discussion', $posts);
       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);
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

    $members_sql=GroupMember::select(DB::raw('group_concat(member_id) as members'))->where('group_id',$request->group_id)->first();

         if($request->type=='parent'){
          $group_data= User::where('role_id',3)->where('status',1)->where('school_id',$usrobj->school_id)->where('id','!=',$request->user_id)->whereRaw('id NOT IN( Select to_user_id FROM report_users WHERE from_user_id='.$request->user_id.')')->whereRaw('id NOT IN( Select from_user_id FROM report_users WHERE to_user_id='.$request->user_id.')')->whereRaw('id NOT IN ('.$members_sql->members.')')->get(); 

         
        }
        else{
          $group_data= User::where('role_id',4)->where('status',1)->where('school_id',$usrobj->school_id)->where('id','!=',$request->user_id)->whereRaw('id NOT IN( Select to_user_id FROM report_users WHERE from_user_id='.$request->user_id.')')->whereRaw('id NOT IN( Select from_user_id FROM report_users WHERE to_user_id='.$request->user_id.')')->whereRaw('id NOT IN ('.$members_sql->members.')')->get(); 
        }
        $array=array('error' => false, 'data' => $group_data);
        return response()->json($array, 200);
      }
    } catch (\Exception $e) {
      return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
    }
  }


public function GroupMembers(Request $request) {
      try {
        $input = $request->all();
        $validator = Validator::make($input, [
          'user_id' =>'required|exists:users,id',
           'group_id' =>'required|exists:groups,id'
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else { 
        	$user=User::find($request->user_id);
        	$group_info=Group::find($request->group_id);
        	if($group_info->type=='parent_community'){
           $members= User::where('role_id',3)->where('join_community',1)->where('status',1)->get();
          } 
          if($group_info->type=='school_admin'){
            $members=User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1)->get();         
          }
          if($group_info->type=='custom_group'){
            $users = GroupMember::join('users', 'group_members.member_id', '=', 'users.id')
            ->select("users.*")->where('group_id',$group_info->id);
          $members=$users->get();
          }
        
        $array=array('error' => false, 'data' => $members);
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
			$group_info=Group::where('user_id',$request->user_id)->where('id',$request->group_id);
			if($group_info->count()){
				$group_info->delete();
			}
			else{
      $get_group=Group::where('id',$request->group_id)->first();
        //$get_group=$group_info->first();
        if($get_group->type=='parent_community' || $get_group->type=='school'){
          User::where('id',$request->user_id)->update(['join_community'=>0]);
        }
        
    
        
           // GroupMember::where('group_id',$request->group_id)->where('member_id',$request->user_id)->delete();
//update `users` set `exit_groups` =IFNULL( CONCAT(exit_groups, ',2') , '2') where `id` = 242
				GroupMessage::where('group_id',$request->group_id)->where('from_user_id',$request->user_id)->orWhere('to_user_id',$request->user_id)->delete();

      User::where('id',$request->user_id)->update(['exit_groups' => DB::raw("IFNULL(CONCAT(exit_groups, '," . $request->group_id . "')," . $request->group_id . ")")]);

			

    }
     return response()->json(array('error' => false, 'data' => []), 200);
       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function ReportGroup(Request $request) {
  try {
    $input = $request->all();
    $validator = Validator::make($input, [
      'user_id' => 'required|exists:users,id',
      'group_id' => 'required|exists:groups,id',
    ]);

    if ($validator->fails()) {
      throw new Exception($validator->errors()->first());
    } else {
      $group_info=Group::where('user_id',$request->user_id)->where('id',$request->group_id)->first();
      $report_group= new ReportGroup;
      $report_group->user_id=$request->user_id;
      $report_group->group_id=$request->group_id;
      $report_group->save();
      if($request->is_exit==1){
      if($group_info->type=='custom_group'){
     $delete=   GroupMember::where('group_id',$request->group_id)->where('member_id',$request->user_id)->delete();
      }
     $delete= GroupMessage::where('group_id',$request->group_id)->where('to_user_id',$request->user_id)->where('from_user_id',$request->user_id)->delete();
      }
    }
      return response()->json(array('error' => false, 'data' => $report_group), 200);
    }
      catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

     public function Deletemember(Request $request) {
  try {
    $input = $request->all();
    $validator = Validator::make($input, [
      'member_id' => 'required|exists:users,id',
      'group_id' => 'required|exists:groups,id',
    ]);

    if ($validator->fails()) {
      throw new Exception($validator->errors()->first());
    } else {
    	$delete=GroupMember::where('member_id',$request->member_id)->where('group_id',$request->group_id)->delete();


     
      return response()->json(array('error' => false, 'data' => $delete), 200);
    }
}
      catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

      public function CLearchatByUser(Request $request) {
     try {
    $input = $request->all();
    $validator = Validator::make($input, [
      'user_id' => 'required|exists:users,id',
      'group_id' => 'required|exists:groups,id',
    ]);

    if ($validator->fails()) {
      throw new Exception($validator->errors()->first());
    } else {
    

     $groups= GroupMessage::where('group_id',$request->group_id)->where('to_user_id',$request->user_id)->orWhere('from_user_id',$request->user_id)->select('group_number')->groupBy('group_number')->get();
     foreach($groups as $single_group){
      $ClearChatGroup= new ClearChatGroup;
      $ClearChatGroup->user_id=$request->user_id;
      $ClearChatGroup->group_id=$request->group_id;
      $ClearChatGroup->group_number=$single_group->group_number;
      $ClearChatGroup->save();
    }
      return response()->json(array('error' => false, 'data' => $groups), 200);
    }
  }
      catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


     public function CreateGroupDiscussion(Request $request) {
     try {
    $input = $request->all();
    $validator = Validator::make($input, [
      'user_id' => 'required|exists:users,id',
      'group_id' => 'required|exists:groups,id',
     // 'description' => 'required',
    ]);

    if ($validator->fails()) {
      throw new Exception($validator->errors()->first());
    } else {  

      $GroupDiscussion=new GroupDiscussion();
      $GroupDiscussion->user_id=$request->user_id;
      $GroupDiscussion->group_id=$request->group_id;
      $GroupDiscussion->description=$request->description;
      $data=array();
      if($request->hasfile('image'))
      {
      
      	foreach($request->file('image') as $key=>$file)
      	{
      		$name=time().$key.'.'.$file->getClientOriginalExtension();    
      		$file->move(public_path().'/images/', $name);      
      		$data[$key] = URL::to('/').'/images/'.$name;  
      	}
      }

      $GroupDiscussion->image=$data;
     
      $GroupDiscussion->save();

    

      return response()->json(array('error' => false, 'data' => $GroupDiscussion), 200);
    }
  }
      catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


    public function GetGroupDiscussions(Request $request) {
     try {
    $input = $request->all();
    $validator = Validator::make($input, [
      'group_id' => 'required|exists:groups,id',
       'user_id' => 'required|exists:users,id'
    ]);

    if ($validator->fails()) {
      throw new Exception($validator->errors()->first());
    } else {  
   $group_discussions= GroupDiscussion::select((DB::raw("( CASE WHEN EXISTS (
      SELECT *
      FROM discussions_like
      WHERE group_discussions.id = discussions_like.discussion_id
      AND discussions_like.user_id = ".$request->user_id."  AND discussions_like.like = 1
      ) THEN TRUE
      ELSE FALSE END)
      AS is_like,group_discussions.*")))->with('User','User.CityDetail','User.SchoolDetail','User.StateDetail')->withCount('likes','comments')->where('group_id',$request->group_id)->orderBy('id', 'DESC')->get();    
      return response()->json(array('error' => false, 'data' => $group_discussions), 200);
    }
  }
  catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

     public function AddReplyComments(Request $request){
            $input = $request->all();
            $validator = Validator::make($input, [
             'user_id' => 'required|exists:users,id',
             'discussion_id' => 'required|exists:group_discussions,id',
             'comment_id' => 'required|exists:group_discussion_comments,id',
             'reply_comment' => 'required'

 

         ]);    
            if ($validator->fails()) {
             return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
         }
         else{

 

         $reply= new DIscussionCommentReply;//then create new object
         $reply->user_id=$request->user_id;
         $reply->discussion_id=$request->discussion_id;
         $reply->comment_id=$request->comment_id;
         $reply->reply_comment=$request->reply_comment;
         $reply->save();
         $reply->name= $reply->User->name;
//          total reply comments
         $results1 = DB::select( DB::raw("select count(*) as total_reply_comments from `discussion_comments_reply` where `comment_id` = ".$request->comment_id."") );
             if(isset($results1[0])){
                 $reply->total_reply_comments=(int)$results1[0]->total_reply_comments;
             }
             else{
                  $reply->total_reply_comments=0;
             }

 

         $reply->user_id=(int) $request->user_id;
         $reply->discussion_id=(int) $request->discussion_id;
         $reply->comment_id=(int) $request->comment_id;
 

           $this->pusher->trigger('reply-discuss-channel', 'add_reply_discussion', $reply);
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $reply), 200);
     }
    }

    public function GetReplyComments(Request $request){
      $validator = Validator::make($request->all(), [
          'comment_id' => 'required|exists:group_discussion_comments,id'
      ]);
      if($validator->fails()){
          return response()->json(array('errors' => $validator->errors(),'error' => true));
      }
      else{
      $replycomments= DIscussionCommentReply::with('User')->where('comment_id' , $request->comment_id)->get();
      return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $replycomments), 200);
  
      }
  
  }
}
