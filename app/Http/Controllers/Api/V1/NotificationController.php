<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\Notification;
use App\Models\Schedule;
use App\Models\AssignedAssignments;

use App\Models\ParentTask;
use Pusher\Pusher;
use DB;
use App\Events\NotificationEvent;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller {
    
    public function __construct()
    {
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
			$notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->orderBy('id', 'DESC')->select(DB::raw("DATE(created_at) as notification_date"),"notification.*")->with('User:id,name,role_id,profile_image')->get();
		}else{
			$notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'social_notification')->orderBy('id', 'DESC')->select(DB::raw("DATE(created_at) as notification_date"),"notification.*")->with('User:id,name,role_id,profile_image')->get();
		}
	

		foreach($notifications as $single_notification){
			if(!empty($single_notification->schedule_id)){
				$scheduke=	Schedule::with('User')->where('id',$single_notification->schedule_id)->first();
				$tasks=  ParentTask::with('AssignedUser')->where('schedule_id',$single_notification->schedule_id)->first();
                                if(!empty($tasks)){
				$user= User::where('id', $tasks->AssignedUser->task_assigned_to)->select('name','id','device_token')->get();
				$scheduke->assigned_to=$user;
                                }else{
				$scheduke->assigned_to=null;   
                                }

				$single_notification->schedule=$scheduke;
			}
			else{
				$single_notification->schedule=null;

			}

			if(!empty($single_notification->assignment_id)){
			$subject= AssignedAssignments::where("assignment_id",$single_notification->assignment_id)->select("subject_id","class_id")->first();
                        if(!empty($subject)){
                            $single_notification->subject_id=$subject->subject_id;
                        }else{
			   $single_notification->subject_id=null;
                        }
			$single_notification->class_id=$subject->class_id;
			}
		}
		if(count($notifications)>0){
	return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);
}else{
	return response()->json(array('error' => true, 'message' => ' No Record found', 'data' => []), 200);
}
	       
	

	}

}

// Update Notification settings
public function NotificationSetting(Request $request){
	$validator = Validator::make($request->all(), [
		'user_id' => 'required|exists:users,id',
		'notification_settings' => 'required'
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
		$userobj= User::find($request->user_id);
		$userobj->notification_settings=$request->notification_settings;
		$userobj->save();
		
		return response()->json(array('error' => false, 'message' => 'Updated successfully', 'data' =>$userobj), 200);

	}

}

	public function GetNotificationbyChild(Request $request){
	$validator = Validator::make($request->all(), [
		'user_id' => 'required|exists:users,id',
		'children_id' => 'required|exists:users,id'
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{

		/*$children_id=$request->children_id;
		$user_id=$request->user_id;*/
		 /*$notifications=Notification::where(function ($query) use ($children_id, $user_id) {
            $query->where('user_id', $children_id)->where('from_user_id', $user_id);
          })->oRwhere(function ($query) use ($children_id, $user_id) {
            $query->where('from_user_id', $children_id)->where('user_id', $user_id);
          })->select(DB::raw("DATE(created_at) as notification_date"),"notification.*")->with('User')->get();*/
$notifications=	Notification::where('from_user_id' , $request->children_id)->where('user_id' , $request->user_id)->orderBy('id', 'DESC')->select(DB::raw("DATE(created_at) as notification_date"),"notification.*")->with('User')->get();
if(count($notifications)>0){
	return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);
}else{
	return response()->json(array('error' => true, 'message' => ' No Record found', 'data' => []), 200);
}
	      
}
}

public function GetNotificationbyClass(Request $request){
	$validator = Validator::make($request->all(), [
		'teacher_id' => 'required|exists:users,id',
		'class_id' => 'required|exists:class_code,id'
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
	$notifications=	Notification::where('user_id' , $request->teacher_id)->where('class_id' , $request->class_id)->orderBy('id', 'DESC')->select(DB::raw("DATE(created_at) as notification_date"),"notification.*")->with('User')->get();
if(count($notifications)>0){
	return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);
}else{
	return response()->json(array('error' => true, 'message' => ' No Record found', 'data' => []), 200);
}
	      
}
}
    public function UnreadNotifications(Request $request){
	$validator = Validator::make($request->all(), [
	    'user_id' => 'required|exists:users,id',
            'role_type' => 'required|in:all,child,class',
            'type' => 'required_if:role_type, =,all',
//            'children_id' => 'required_if:role_type, =,child',
//            'class_id' => 'required_if:role_type, =,class',
            
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
            if($request->role_type == 'child'){
//                $notifications= Notification::where('from_user_id' , $request->children_id)->where('user_id' , $request->user_id)->where('is_read' , '0')->count(); 
                $notifications=	Notification::where('user_id' , $request->user_id)->where('is_all_read' , '0')->count(); 
            }else if($request->role_type == 'class'){
//                $notifications=	Notification::where('user_id' , $request->user_id)->where('class_id' , $request->class_id)->where('is_read' , '0')->count(); 
                $notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->where('is_all_read' , '0')->count(); 
            } else{
               if($request->type=='school_notification'){
			$notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->where('is_all_read' , '0')->count();
		}else{
			$notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'social_notification')->where('is_all_read' , '0')->count();
		} 
            }
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);
	       
	

	}

    }
    public function MarkAllNotificationsRead(Request $request){
	$validator = Validator::make($request->all(), [
	    'user_id' => 'required|exists:users,id',
            'role_type' => 'required|in:all,child,class',
            'type' => 'required_if:role_type, =,all',
//            'children_id' => 'required_if:role_type, =,child',
//            'class_id' => 'required_if:role_type, =,class',
            
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
            if($request->role_type == 'child'){
//                $notifications= Notification::where('from_user_id' , $request->children_id)->where('user_id' , $request->user_id)->where('is_read' , '0')->count(); 
                Notification::where('user_id' , $request->user_id)->update(['is_all_read' => '1']);
                $notifications=	Notification::where('user_id' , $request->user_id)->get();
            }else if($request->role_type == 'class'){
//                $notifications=	Notification::where('user_id' , $request->user_id)->where('class_id' , $request->class_id)->where('is_read' , '0')->count(); 
                Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->update(['is_all_read' => '1']); 
                $notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->get();
            } else{
               if($request->type=='school_notification'){
			Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->update(['is_all_read' => '1']);
                        $notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'school_notification')->get();
		}else{
			Notification::where('user_id' , $request->user_id)->where('type' , 'social_notification')->update(['is_all_read' => '1']);
                        $notifications=	Notification::where('user_id' , $request->user_id)->where('type' , 'social_notification')->get();
		} 
            }
//            $this->pusher->trigger('notification-channel', 'notification_all_read', $notifications);
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);
	       
	

	}

    }
    
    public function MarkNotificationsRead(Request $request){
	$validator = Validator::make($request->all(), [
		'user_id' => 'required|exists:users,id',
                'notification_id' => 'required|exists:notification,id',
            
	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
		Notification::where('user_id' , $request->user_id)->where('id' , $request->notification_id)->update(['is_read' => '1']);
                $notifications = Notification::where('user_id' , $request->user_id)->where('id' , $request->notification_id)->first();
//                $this->pusher->trigger('notification-channel', 'notification_all_read', $notifications);

                return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $notifications), 200);
	       
	

	}

    }
}
?> 