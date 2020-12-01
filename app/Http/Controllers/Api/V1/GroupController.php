<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\Group;
use App\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class GroupController extends Controller {

    public function __construct() {
        
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

                 $groups=Group::all();
                 $user=User::find($request->user_id);
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


     public function SendMessage(Request $request) {
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

                
              $groups=new Group;
              $groups->to_user_id=$request->to_user_id;
              $groups->from_user_id=$request->from_user_id;
              $groups->group_id=$request->group_id;
              $groups->message=$request->message;
              $groups->save();
                 return response()->json(array('error' => false, 'data' => $groups), 200);
               
          }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

  

}
