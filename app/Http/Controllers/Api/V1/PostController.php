<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Post;
use App\Models\LikeUnlike;
use App\Models\Comment;
use Pusher\Pusher;
use Illuminate\Support\Facades\Validator;
use URL;
use DB;
class PostController extends Controller {

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

	public function AddPost(Request $request){
		$validator = Validator::make($request->all(), [
		'post_name' => 'required',
		'user_id' => 'required|exists:users,id'

	]);
	if($validator->fails()){
		return response()->json(array('errors' => $validator->errors(),'error' => true));
	}
	else{
	$PostObj = new Post();		
	$PostObj->post_name=$request->post_name;
	$PostObj->user_id=$request->user_id;
  $PostObj->is_image=0;
  $data = [];
   if($request->hasfile('image'))
   {
    $PostObj->is_image=1;
    foreach($request->file('image') as $key=>$file)
    {
      $name=time().'.'.$file->getClientOriginalExtension();    
      $file->move(public_path().'/images/', $name);      
      $data[$key] = URL::to('/').'/images/'.$name;  
    }
  }
  $PostObj->image=json_encode($data);
  $PostObj->save();
	return response()->json(array('error' => true, 'message' => 'Record found', 'data' => $PostObj), 200);
	}
}
public function GetPostHomefeed(Request $request){
		
        $posts = Post::with('user')->withCount('likes','comments')->get();
	//$posts=	Post::with('user')->get();
	return response()->json(array('error' => true, 'message' => 'Record found', 'data' => $posts), 200);

	}

	  public function AddComments(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required|exists:users,id',
        'post_id' => 'required|exists:posts,id',
        'comment' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
    }
    else{
    
    $flight= new Comment;//then create new object
    $flight->user_id=$request->user_id;
    $flight->post_id=$request->post_id;
    $flight->comment=$request->comment;
    $flight->save();
    $flight->name= $flight->User->name;
    // total comments
        $results1 = DB::select( DB::raw("select count(*) as total_comments from `comments` where `post_id` = ".$request->post_id."") );
        if(isset($results1[0])){
            $flight->total_comments=(int)$results1[0]->total_comments;
        }
        else{
             $flight->total_comments=0;
        }

            $flight->user_id=(int) $request->user_id;
    $flight->post_id=(int) $request->post_id;

        $this->pusher->trigger('comment-channel', 'add_comment', $flight);
       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);
}
}

public function GetComments(Request $request){
    $validator = Validator::make($request->all(), [
        'post_id' => 'required|exists:posts,id'
    ]);
    if($validator->fails()){
        return response()->json(array('errors' => $validator->errors(),'error' => true));
    }
    else{
    $comments=  Comment::with('User')->where('post_id' , $request->post_id)->get();
    return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $comments), 200);

    }

}

    public function LikeUnlikePost(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required|exists:users,id',
        'post_id' => 'required|exists:posts,id',
        'like' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
    }
    else{
    $like_unlike_id = LikeUnlike::where('user_id', $request->user_id)->where('post_id', $request->post_id)->first();
    if(!empty($like_unlike_id)){
    /* $notify_id=1;
     if($like_unlike_id->like!=$request->like){
      $notify_id=0;
    }*/
     $flight = LikeUnlike::find($like_unlike_id->id);
  } 
  else
  {
  //$notify_id=0;
  $flight= new LikeUnlike;//then create new object
}
    $flight->user_id=$request->user_id;
    $flight->post_id=$request->post_id;
    $flight->like=$request->like;
    $flight->save();
  // total likes
  /* $results = DB::select( DB::raw("select count(*) as total_likes from `like_unlike` where `post_id` = ".$request->stream_id." and `like` = 1") );

   
           if(isset($results[0])){
            $flight->total_likes=$results[0]->total_likes;
        }
        else{
             $flight->total_likes=0;
        }*/

        if($request->like=='1'){
         // $like='liked';
         $this->pusher->trigger('like-channelpost', 'like_post', $flight);
       }
       else{
     //   $like='unliked';
         $this->pusher->trigger('like-channelpost', 'dislike_post', $flight);
       }
       /*if($notify_id==0){
        // send notification
          $user=$flight->Streamuser($request->stream_id);
         $message= $flight->User->name .' has '.$like.' your video';
         Notification::create(['user_id'=>$user->id,'notification_message'=>$message,'type'=>'like','is_follow'=>0,'stream_id'=> $request->stream_id,'notify_id'=>$request->user_id]);  
       }   */    

       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);


}
}
	
}
