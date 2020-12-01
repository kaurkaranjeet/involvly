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
              $teachers = ParentChildrens::leftJoin('users', 'users.id', '=', 'parent_childrens.children_id')
               ->select(DB::raw('group_concat(DISTINCT(school_id) as schools'))
              ->where('parent_id', $user->id)->groupBy('parent_id')->first();
              if(!empty($teachers->schools)){

               $groups= Group::whereRaw("type='parent_community' OR type='school' OR ( type='school_admin' AND school_id IN('".$teachers->schools."')) AND status=1")->get();

             }else{
              $groups= Group::whereRaw("type='parent_community' OR type='school') AND status=1")->get();
            }
                // $groups=Group::where('status',1)->get();
                 
                 foreach($groups as $single_group){
                  if($single_group->type=='parent_community'){
                   $count= User::where('role_id',3)->where('join_community',1)->where('status',1)->count();
                   $single_group->member_count=$count;
                  }
                  if($single_group->type=='school'){
                   $count= User::where('city',$user->city)->where('join_community',1)->where('status',1)->count();
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
                       // 'from_user_id' => 'required|exists:users,id',
                         'user_id' => 'required|exists:users,id',
                        'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
              $user=User::find($request->user_id);
              if($request->group_id=='1'){
               $users= User::where('role_id',3)->where('join_community',1)->where('status',1)->get();
               foreach($users as $single){
                 $groups=new GroupMessage;
                 $groups->to_user_id=$single->id;
                 $groups->from_user_id=$request->user_id;
                 $groups->group_id=$request->group_id;
                 $groups->message=$request->message;
                  $groups->is_read=0;
                 $groups->save();
                   $this->pusher->trigger('group-channel', 'group_user', $groups);
               }
                 

             }

             if($request->group_id=='2'){
              $users= User::where('city',$user->city)->where('join_community',1)->where('status',1)->get();
              foreach($users as $single){
               $groups=new GroupMessage;
               $groups->to_user_id=$single->id;
               $groups->from_user_id=$request->user_id;
               $groups->group_id=$request->group_id;
               $groups->message=$request->message;
               $groups->is_read=0;
               $groups->save();
               $this->pusher->trigger('group-channel', 'group_user', $groups);
             }          

           }


                $group_data= GroupMessage::where('from_user_id',$user->id)->where('group_id',$request->group_id)->get();
                
             
                 return response()->json(array('error' => false, 'data' => $group_data), 200);
               
          }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

  

}