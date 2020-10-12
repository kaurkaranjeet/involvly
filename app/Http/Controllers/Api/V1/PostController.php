<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Post;
use App\Models\Comment;
use Pusher\Pusher;
use Illuminate\Support\Facades\Validator;
use URL;
class PostController extends Controller {

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
		if($request->hasFile('image')) {
        $image = $request->file('image');
        $name = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path('/images');
        $image->move($destinationPath, $name);
        $PostObj->image=URL::to('/').'/images/'.$name;
        $PostObj->is_image=1;
       
    }
		$PostObj->save();
		return response()->json(array('error' => true, 'message' => 'Record found', 'data' => $PostObj), 200);
	}
}
public function GetPostHomefeed(Request $request){
		
	$posts=	Post::with('user')->get();
	return response()->json(array('error' => true, 'message' => 'Record found', 'data' => $posts), 200);

	}

	  public function AddComments(Request $request){
       $input = $request->all();
       $validator = Validator::make($input, [
        'user_id' => 'required',
        'post_id' => 'required',
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
            $flight->total_comments=$results1[0]->total_comments;
        }
        else{
             $flight->total_comments=0;
        }

        $this->pusher->trigger('comment-channel', 'add_comment', $flight);
       return response()->json(array('error' => false, 'message' => 'Success', 'data' => $flight), 200);
}
}
	
}
