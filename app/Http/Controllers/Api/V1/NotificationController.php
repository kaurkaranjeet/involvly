<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\Notification;
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
	$notifications=	Notification::with('User')->where('user_id' , $request->user_id)->get();

		/*$notifications=Notification::with('NotifyUser')->leftJoin('queues','notification.stream_id', '=', 'queues.id')
        ->leftJoin('video_user', 'video_user.id', '=', 'queues.video_id')->select('notification.stream_id','notification.*','video_user.thumbnail_url','notification.stream_id')->where('notification.user_id',$request->user_id)->orderBy('notification.id', 'DESC')->get();*/
  

/*$data=array();
        foreach($notifications as $value){
        	if($value->notify_id>0){
        	$value->name=$value->NotifyUser->name;
        }
         $is_follow=FollowUnfollow::where('following_user_id',$request->user_id)->where('followed_user_id',$value->notify_id)->first();
          if(!empty($is_follow)){
          	$value->is_follow=1;
          }
        	$value->notification_message=ltrim(str_replace($value->name,"",$value->notification_message));
        	 $data[]=$value;

          

        }*/
       
	return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);

	}

}

}

?> 