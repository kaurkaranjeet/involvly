<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Notification;
use App\FollowUnfollow;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller {
// Provide the Host Information.

	public function SendNotification(){
		
		$notification=SendAllNotification('d0ga2Ad9l0iwqpKu3xofVB:APA91bF51lh4MtFaZ-rlKzA_1Qi6X90I63Nkxv10eipfEZYyOx4k2M6DkBx4ZzKAwEPK15agOsVBZTdW71k9NArQybUTYdMj2YSK0970epyPAo3hbeu5TQhZ_o8_4zntSbKojyVO53TF','helllo','helllo');

	}

	public function AllNotifications(Request $request){
	$validator = Validator::make($request->all(), [
		'user_id' => 'required|exists:users,id'
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
	//$notifications=	Notification::where('user_id' , $request->user_id)->get();

		$notifications=Notification::with('NotifyUser')->leftJoin('queues','notification.stream_id', '=', 'queues.id')
        ->leftJoin('video_user', 'video_user.id', '=', 'queues.video_id')->select('notification.stream_id','notification.*','video_user.thumbnail_url','notification.stream_id')->where('notification.user_id',$request->user_id)->orderBy('notification.id', 'DESC')->get();
  

$data=array();
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

          

        }
       
	return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $data), 200);

	}

}

}

?> 