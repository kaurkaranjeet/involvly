<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Post;
use App\Events\CommentEvent;
use App\Events\LikeEvent;
use App\Models\LikeUnlike;
use App\Events\ReplyEvent;
use App\Events\CommentCountEvent;
use App\Models\Comment;
use App\Models\Notification;
use App\Models\CommentReply;
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
		'user_id' => 'required|exists:users,id'

	]);
	if($validator->fails()){
		return response()->json(array('message' => $validator->errors(),'error' => true));
	}
	else{
   $PostObj = new Post();		
   $PostObj->post_name=$request->post_name;
   $PostObj->user_id=$request->user_id;
   $PostObj->post_description=$request->post_description;
  $PostObj->is_image=0;
  $data = [];
   if($request->hasfile('image'))
   {
    $PostObj->is_image=1;
    foreach($request->file('image') as $key=>$file)
    {
      $name=time().$key.'.'.$file->getClientOriginalExtension();    
      $file->move(public_path().'/images/', $name);      
      $data[$key] = URL::to('/').'/images/'.$name;  
    }
  }
  $PostObj->image=$data;
  $PostObj->save();
	return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $PostObj), 200);
	}
}
public function GetPostHomefeed(Request $request){
  if($request->sort_by_popularity==1){
     $posts = Post::select((DB::raw("( CASE WHEN EXISTS (
              SELECT *
              FROM likes
              WHERE posts.id = likes.post_id
              AND likes.user_id = ".$request->user_id."  AND likes.like = 1
              ) THEN TRUE
              ELSE FALSE END)
              AS is_like,posts.*")))->with('user','user.CityDetail','user.StateDetail','user.SchoolDetail')->withCount('likes','comments')->orderBy('likes_count', 'desc')->get();
   }else{
     $posts = Post::select((DB::raw("( CASE WHEN EXISTS (
              SELECT *
              FROM likes
              WHERE posts.id = likes.post_id
              AND likes.user_id = ".$request->user_id."  AND likes.like = 1
              ) THEN TRUE
              ELSE FALSE END)
              AS is_like,posts.*")))->with('user','user.CityDetail','user.SchoolDetail','user.StateDetail')->withCount('likes','comments')->orderBy('id', 'DESC')->get();
   }
        
       
       
    return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $posts), 200);

 

    }

	  public function AddComments(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required|exists:users,id',
        'post_id' => 'required|exists:posts,id',
        'comment' => 'required'

    ]);    
       if ($validator->fails()) {
        return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
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

         $comments=  Comment::with('User')->withCount('replycomments')->with('replycomments')->where('id' , $flight->id)->first();
         $comments->comment_id=(int) $comments->comment_id;
         $post_user=Post::with('user')->where('id',$request->post_id)->first();
        // send notification         
       $message= $comments->User->name .' has commented on your post.';
       if(!empty($post_user->user->device_token)){
        SendAllNotification($post_user->user->device_token,$message,'comment_post');          
      }
       Notification::create(['user_id'=>$post_user->user->id,'notification_message'=>$message,'type'=>'social_notification','notification_type'=>'comment']);

        $this->pusher->trigger('comment-channel', 'add_comment', $comments);

    $posts = Post::select((DB::raw("( CASE WHEN EXISTS (
      SELECT *
      FROM likes
      WHERE posts.id = likes.post_id
      AND likes.user_id = ".$request->user_id."  AND likes.like = 1
      ) THEN TRUE
      ELSE FALSE END)
      AS is_like,posts.*")))->with('user')->withCount('likes','comments')->where('id', $request->post_id)->orderBy('id', 'DESC')->first();

    $this->pusher->trigger('count-channel', 'comment_count', $posts);
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


    $comments= Comment::with('User')->withCount('replycomments')->with('replycomments.User')->where('post_id' , $request->post_id)->get();

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
        return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
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

        if($request->like=='1'){
         // $like='liked';
         $this->pusher->trigger('like-channelpost', 'like_post', $flight);
       }
       else{
     //   $like='unliked';
         $this->pusher->trigger('like-channelpost', 'dislike_post', $flight);
       }

       $posts = Post::select((DB::raw("( CASE WHEN EXISTS (
        SELECT *
        FROM likes
        WHERE posts.id = likes.post_id
        AND likes.user_id = ".$request->user_id."  AND likes.like = 1
        ) THEN TRUE
        ELSE FALSE END)
        AS is_like,posts.*")))->with('user')->withCount('likes','comments')->where('id', $request->post_id)->orderBy('id', 'DESC')->first();
       $this->pusher->trigger('count-channel', 'like_count', $posts);
       $post_user=Post::with('user')->where('id',$request->post_id)->first();
        // send notification         
       $message= $flight->User->name .' has liked your post.';
       if(!empty($post_user->user->device_token)){
        SendAllNotification($post_user->user->device_token,$message,'like_post');        
      }
      Notification::create(['user_id'=>$post_user->user->id,'notification_message'=>$message,'type'=>'social_notification','notification_type'=>'like']);  
       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);


}
}

 public function RemovePost(Request $request){

       $validator = Validator::make($request->all(), [
        'post_id' => 'required|exists:posts,id',

    ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
              $delete= Post::where('id',$request->post_id)->delete();                   
            if ($delete) {
                return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

     public function AddReplyComments(Request $request){
            $input = $request->all();
            $validator = Validator::make($input, [
             'user_id' => 'required|exists:users,id',
             'post_id' => 'required|exists:posts,id',
             'comment_id' => 'required|exists:comments,id',
             'reply_comment' => 'required'

 

         ]);    
            if ($validator->fails()) {
             return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
         }
         else{

 

         $reply= new CommentReply;//then create new object
         $reply->user_id=$request->user_id;
         $reply->post_id=$request->post_id;
         $reply->comment_id=$request->comment_id;
         $reply->reply_comment=$request->reply_comment;
         $reply->save();
         $reply->name= $reply->User->name;
//          total reply comments
         $results1 = DB::select( DB::raw("select count(*) as total_reply_comments from `comment_replies` where `comment_id` = ".$request->comment_id."") );
             if(isset($results1[0])){
                 $reply->total_reply_comments=(int)$results1[0]->total_reply_comments;
             }
             else{
                  $reply->total_reply_comments=0;
             }

 

         $reply->user_id=(int) $request->user_id;
         $reply->post_id=(int) $request->post_id;
         $reply->comment_id=(int) $request->comment_id;
 

           $this->pusher->trigger('reply-channel', 'add_reply', $reply);
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $reply), 200);
     }
    }

    public function GetReplyComments(Request $request){
      $validator = Validator::make($request->all(), [
          'comment_id' => 'required|exists:comments,id'
      ]);
      if($validator->fails()){
          return response()->json(array('errors' => $validator->errors(),'error' => true));
      }
      else{
      $replycomments= CommentReply::with('User')->where('comment_id' , $request->comment_id)->get();
      return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $replycomments), 200);
  
      }
  
  }
	
}
