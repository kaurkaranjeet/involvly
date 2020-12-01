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
                        'school_id' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
             $group_count= Group::where('type','parent_community')->where('school_id',$request->school_id)->count();
                 if($group_count==0){
                   $group_obj = new Group;
                   $group_obj->type='parent_community';
                   $group_obj->user_id='0';
                   $group_obj->status='1';
                   $group_obj->school_id=$request->school_id;
                   $group_obj->group_name='Parent Community';
                   $group_obj->save();
                }
                 $groups=Group::where('school_id',$request->school_id)->get();
                 return response()->json(array('error' => false, 'data' => $groups), 200);
               
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

  

}
