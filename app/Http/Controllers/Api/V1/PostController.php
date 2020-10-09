<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\Post;
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
	
}
