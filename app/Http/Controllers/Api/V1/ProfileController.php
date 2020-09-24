<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Queue;
use App\LikeUnlike;
use Pusher\Pusher;
use App\FollowUnfollow;
use App\Comment;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Notification;
use Carbon\Carbon;

class ProfileController extends Controller {
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

    public function updateProfile(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'username' => 'required',
                    'user_id' => 'required|exists:users,id'
        ]);
       
       if ($validator->fails()) {
            return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
        }  
        else{ 
       $count_user=User::where('id','!=', $request->user_id)->where('username','=',$request->username)->count();
         
          if($count_user==0){
            $updateData = User::where('id', $request->user_id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'bio' => $request->bio,
                'insta_key' => $request->insta_key
            ]);
            // upload image file
            if ($request->hasfile('image')) {
              $video = $request->file('image');
              $name = time() . '.' . trim($video->getClientOriginalExtension());
              $destinationPath = public_path('/uploads');
              $video->move($destinationPath, $name);
              $videourl = url('/') . '/uploads/' . $name;
              $updateData = User::where('id', $request->user_id)->update([
                'image' => $videourl
              ]);
            }
            $update= User::find( $request->user_id);
            return response()->json(array('error' => false, 'message' => 'profile update successfully', 'data' => $update), 200);
          }
        else{
           return response()->json(array('error' => true, 'message' => 'Username already exist', 'data' => []), 200);
        }
      }
       
    }

    public function Getuserprofile(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required'

        ]);    
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
        }  
        else{ 
            $data = User::fetchUser($request->user_id);
            $get_followers=User::FollowedUsers($request->user_id);
            $following_users=User::FollowingUser($request->user_id);
            $data->followers=$get_followers;
            $data->following=$following_users;
            if(isset($request->viewing_user_id)){
                $folow_or_not=FollowUnfollow::where('following_user_id',$request->viewing_user_id)->where('followed_user_id',$request->user_id)->count();
                if($folow_or_not >0){
                    $data->is_follow=1;
                }else{
                  $data->is_follow=0;
                }
            }
           // DB::enableQueryLog(); 

            $streams=  Queue::select(DB::raw('GROUP_CONCAT(id) as streams'))->where('user_id' , $request->user_id)->first();
            if(!empty($streams->streams)){
              $results = DB::select( DB::raw("select count(*) as total_likes from `like_unlike` where `stream_id` in (".$streams->streams.") and `like` = 1") );

              if(isset($results[0])){
                $data->total_likes=number_format_short($results[0]->total_likes);
              }
              else{
               $data->total_likes=0;
             }

           }
            else{
               $data->total_likes=0;
             }

            return response()->json(array('error' => false, 'message' => 'profile fetched successfully', 'data' => $data), 200);

        }

    }

    public function LikeUnlikeStream(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required|exists:users,id',
    'stream_id' => 'required|exists:queues,id',
        'like' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
    }
    else{
    $like_unlike_id = LikeUnlike::where('user_id', $request->user_id)->where('stream_id', $request->stream_id)->select('id')->first();
      if(!empty($like_unlike_id)){
     $flight = LikeUnlike::find($like_unlike_id->id);
  } 
  else
  {
  $flight= new LikeUnlike;//then create new object
}
    $flight->user_id=$request->user_id;
    $flight->stream_id=$request->stream_id;
    $flight->like=$request->like;
    $flight->save();
  // total likes
   $results = DB::select( DB::raw("select count(*) as total_likes from `like_unlike` where `stream_id` = ".$request->stream_id." and `like` = 1") );

   
           if(isset($results[0])){
            $flight->total_likes=$results[0]->total_likes;
        }
        else{
             $flight->total_likes=0;
        }

        if($request->like=='1'){
          $like='liked';
         $this->pusher->trigger('like-channel', 'like_stream', $flight);
       }
       else{
        $like='unliked';
         $this->pusher->trigger('like-channel', 'dislike_stream', $flight);
       }

        // send notification
       $user=$flight->Streamuser($request->stream_id);

        $message= $flight->User->name .' has '.$like.' your video';
    Notification::create(['user_id'=>$user->id,'notification_message'=>$message,'type'=>'like','is_follow'=>0,'stream_id'=> $request->stream_id,'notify_id'=>$request->user_id]);         

       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);


}
}

public function AddComments(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required',
        'stream_id' => 'required',
        'comment' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
    }
    else{
    $Comment_id = Comment::where('user_id', $request->user_id)->where('stream_id', $request->stream_id)->select('id')->first();
    if(!empty($Comment_id)){
     $flight = Comment::find($Comment_id->id);
    }
    else {
    $flight= new Comment;//then create new object
    }
    $flight->user_id=$request->user_id;
    $flight->stream_id=$request->stream_id;
    $flight->comment=$request->comment;
    $flight->save();
    $flight->name= $flight->User->name;
    // total comments
        $results1 = DB::select( DB::raw("select count(*) as total_comments from `comments` where `stream_id` = ".$request->stream_id."") );
        if(isset($results1[0])){
            $flight->total_comments=$results1[0]->total_comments;
        }
        else{
             $flight->total_comments=0;
        }

          $this->pusher->trigger('comment-channel', 'add_comment', $flight);

          // send Notification
          //echo $request->stream_id;die;
         /* $user= $flight->Streamuser($request->stream_id);
          if(!empty($user->device_token) &&  $user->device_type=='ios'){
            $message= $flight->name .' has commented on your video ';
            SendIosNotification($user->device_token,$message);
          }*/
       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);
}
}

    public function FollowUnfollowUser(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'following_user_id' => 'required',
        'followed_user_id' => 'required',
        'follow' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
    }
    else{
    $FollowUnfollow = FollowUnfollow::where('following_user_id', $request->following_user_id)->where('followed_user_id', $request->followed_user_id)->select('id')->first();
      if(!empty($FollowUnfollow)){
     $follow_obj = FollowUnfollow::find($FollowUnfollow->id);
  } 
  else
  {
  $follow_obj= new FollowUnfollow;//then create new object
}
    $follow_obj->following_user_id=$request->following_user_id;
    $follow_obj->followed_user_id=$request->followed_user_id;
    $follow_obj->follow=$request->follow;
    $follow_obj->save();

    $following_user_name= $follow_obj->FetchfollowingUser->name;
    $followed_user_name= $follow_obj->FetchfollowedUser->name;
    $device_token=$follow_obj->FetchfollowedUser->device_token;
    //if(!empty($device_token)){
      if( $follow_obj->follow=='1'){
       $message=$following_user_name.' has started following you';
     }else{
      $message=$following_user_name.' has started unfollowing you';
    }

    		SendAllNotification($device_token,$message,'follow_user');
        //  }
        Notification::create(['user_id'=>$request->followed_user_id,'notification_message'=>$message,'type'=>'follow','is_follow'=>$request->follow,'stream_id'=>0,'notify_id'=>$request->following_user_id]);
    	
  
    

if($request->follow=='1'){
     $this->pusher->trigger('follow-channel', 'follow_user', $follow_obj);
}
else{
     $this->pusher->trigger('follow-channel', 'unfollow_user', $follow_obj);
}
  
       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $follow_obj), 200);


}
}

public function Sendtwominutes(){
 // echo Carbon::now();
	$queues=Queue::join('users', function ($join) {
            $join->on('users.id', '=', 'queues.user_id')
                 ->whereRaw('DATE(queues.created_at) = CURDATE()');
        })->select('users.device_token','users.device_type','queues.*')->get();
//echo "<pre>";print_r($queues);
foreach($queues as $single_queue){
date_default_timezone_set($single_queue->timezone);

$the_date = strtotime($single_queue->start_time);

date_default_timezone_set("UTC");
$date2 =strtotime(date("Y-m-d H:i:s", $the_date));
$date1 = strtotime(date("Y-m-d H:i:s"));
$diff = $date2 - $date1;
//echo $date2 - $date1."<br>";
//$check_minus=strpos($diff,"-");
$check_minus = strpos('going'.$diff, "-");
if($check_minus==false ){

$minutes= floor(abs($diff) / 60);  
//$single_queue->id."id". $minutes."<br>";			
if($minutes=="2"){
 
	$message=' Your Live performance is going to start in 2 minutes';	
		SendAllNotification($single_queue->device_token,$message,'two_minutes');
     // Notification::create(['user_id'=>$single_queue->user_id,'notification_message'=>$message]);
	
	// Send Notification to all users

	$users=User::where('id','!=', $single_queue->user_id)->whereHas(
		'roles', function($q){
			$q->where('id', '2')->whereNotNull('device_token');
		}
	)->get();	
	foreach($users as $user){
	
		if(!empty($user->device_type)){
			
			SendAllNotification($user->device_token,$message,'two_minutes');
			$message=' Live performance is going to start in 2 minutes';
		//	Notification::create(['user_id'=>$user->id,'notification_message'=>$message]);
		}
	}
}

}

}
}
}
