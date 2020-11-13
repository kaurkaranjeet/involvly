<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\Notification;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller {
// Provide the Host Information.

	public function SendNotification($token,$message,$notify_type){
                $notification=SendAllNotification($token,$message,$notify_type);
	}

	public function AllNotifications(Request $request){
	$validator = Validator::make($request->all(), [
		'user_id' => 'required|exists:users,id'
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
		if($request->type=='school_notification'){
			$notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->orderBy('id', 'DESC')->select(DB::raw("DATE(created_at) as notification_date"),"notification.*")->with('User')->get();
		}else{
			$notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'social_notification')->orderBy('id', 'DESC')->select(DB::raw("DATE(created_at) as notification_date"),"notification.*")->with('User')->get();
		}
	
if(count($notifications)>0){
	return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);
}else{
	return response()->json(array('error' => true, 'message' => ' No Record found', 'data' => []), 200);
}
	       
	

	}

}




}

?> 