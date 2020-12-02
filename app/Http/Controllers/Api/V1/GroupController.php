<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\Events\GroupEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\ParentChildrens;

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

              // $results = DB::select( DB::raw("SELECT m1.message as last_message,m1.created_at,u1.name ,u1.email,u1.image,if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) as user_id ,(SELECT count(messages.is_read) from messages  WHERE messages.is_read=0 and messages.from_user_id=m1.from_user_id AND messages.to_user_id=".$user_id." group by messages.from_user_id) as  unreadcount FROM messages m1 LEFT JOIN messages m2 ON (CONCAT(GREATEST(m1.from_user_id,m1.to_user_id),' ',LEAST(m1.from_user_id,m1.to_user_id)) = CONCAT(GREATEST(m2.from_user_id,m2.to_user_id),' ',LEAST(m2.from_user_id,m2.to_user_id)) AND m1.id < m2.id) JOIN users u1 on u1.id=if(m1.from_user_id=".$user_id.",m1.to_user_id,m1.from_user_id) WHERE m2.id IS NULL AND (m1.from_user_id=".$user_id." or m1.to_user_id=".$user_id.") ORDER BY m1.created_at") );

              $teachers = ParentChildrens::leftJoin('users', 'users.id', '=', 'parent_childrens.children_id')
               ->select(DB::raw('group_concat(DISTINCT(school_id)) as schools'))
              ->where('parent_id', $user->id)->groupBy('parent_id')->first();

               $classes = ParentChildrens::leftJoin('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')
               ->select(DB::raw('group_concat(DISTINCT(class_id)) as classes'))
              ->where('parent_id', $user->id)->groupBy('parent_id')->first();
              if(!empty($teachers->schools)){
               $sql= Group::whereRaw("type='parent_community' OR type='school' OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))   AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message");
               if(!empty($classes->classes)){
                 $sql= Group::whereRaw("type='parent_community' OR type='school' OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  OR ( type='class_group' AND class_id IN('".$classes->classes."'))   AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message");;
               }

             }else{
              $sql= Group::whereRaw("type='parent_community' OR type='school') AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message");;
            }
               $groups=$sql->get();
                 
               foreach($groups as $single_group){
                if($single_group->type=='parent_community'){
                 $count= User::where('role_id',3)->where('join_community',1)->where('status',1)->count();
                 $single_group->member_count=$count;
               }
               if($single_group->type=='school'){
                 $count= User::where('city',$user->city)->where('join_community',1)->where('status',1)->count();
                 $single_group->member_count=$count;
               }

               if($single_group->type=='school_admin'){
                 $count=User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1)->count();
                 $single_group->member_count=$count;
               }

               if($single_group->type=='class_group'){
                $count = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')
                ->select(DB::raw('count(*)'))
                ->where('parent_id', $user->id)->where('class_id', $single_group->class_id)->groupBy('parent_id')->count();
                $single_group->member_count=$count;
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
                       'message' => 'required',
                         'user_id' => 'required|exists:users,id',
                        'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
              $user=User::find($request->user_id);
              $random_number=rand();
              if($request->group_id=='1'){
               $users= User::where('role_id',3)->where('join_community',1)->where('status',1)->where('id','!=',$user->id)->get();
               foreach($users as $single){
                 $groups=new GroupMessage;
                 $groups->to_user_id=$single->id;
                 $groups->from_user_id=$request->user_id;
                 $groups->group_id=$request->group_id;
                 $groups->message=$request->message;
                  $groups->group_number=$random_number;
                  $groups->is_read=0;
                 $groups->save();
                   $this->pusher->trigger('group-channel', 'group_user', $groups);
               }
                 

             }

            elseif($request->group_id=='2'){
              $users= User::where('city',$user->city)->where('join_community',1)->where('status',1)->where('id','!=',$user->id)->get();
              foreach($users as $single){
               $groups=new GroupMessage;
               $groups->to_user_id=$single->id;
               $groups->from_user_id=$request->user_id;
               $groups->group_id=$request->group_id;
               $groups->message=$request->message;
               $groups->is_read=0;
                $groups->group_number=$random_number;
               $groups->save();
               $this->pusher->trigger('group-channel', 'group_user', $groups);
             }          

           }

           else if(Group::where('group_id',$request->group_id)->where('type','school_admin')->exists()){
             $users= User::where('role_id',3)->where('school_id',$user->school_id)->where('id','!=',$user->id)->where('status',1)->get();
              foreach($users as $single){
               $groups=new GroupMessage;
               $groups->to_user_id=$single->id;
               $groups->from_user_id=$request->user_id;
               $groups->group_id=$request->group_id;
               $groups->message=$request->message;
                $groups->group_number=$random_number;
               $groups->is_read=0;
               $groups->save();
               $this->pusher->trigger('group-channel', 'group_user', $groups);
             }
           }


  $group_data= GroupMessage::where('group_id',$request->group_id)->where('from_user_id',$request->user_id)->groupBy('group_number')->orderBy('id', 'DESC')->first();
                
         return response()->json(array('error' => false, 'data' => $group_data), 200);
               
          }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
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
          $group_data= GroupMessage::with('User')->where('group_id',$request->group_id)->groupBy('group_number')->get();           
         return response()->json(array('error' => false, 'data' => $group_data), 200);

       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

  

}
