<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

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
		$PostObj->save();
		return response()->json(array('error' => true, 'message' => 'Record found', 'data' => $PostObj), 200);
	}
}
public function GetPostHomefeed(Request $request){
		
	$posts=	Post::with('user')->get();
	return response()->json(array('error' => true, 'message' => 'Record found', 'data' => $posts), 200);

	}
	
}
