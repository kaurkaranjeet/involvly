<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\ClassCode;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;
class ParentController extends Controller {
    public function __construct()
    { 
    }

       // Register Student
    public function ParentRegister(Request $request){
      try {

       $input = $request->all();
       $validator = Validator::make($input, [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|unique:users',
        'password' => 'required',
        'type_of_schooling' => 'required',
        'country' => 'required',
        'state_id' => 'required|exists:states,id',
        'city_id' => 'required|exists:cities,id',
        'school_id' => 'required_if:type_of_schooling, =,school'
      ]);
       
       if ($validator->fails()) {
         throw new Exception($validator->errors()->first());
       }  
       else{ 
        $student_obj=new User;
        $addUser = $student_obj->store($request);
        $token = JWTAuth::fromUser($addUser);
         $addUser->token=$token;
        //clascodes
        if(!empty( $addUser )){
          if(!empty($request->class_code)) {
        	$class_code=  ClassCode::where('class_code',$request->class_code)->first();
        	if(!empty($class_code)){
        		DB::table('user_class_code')->updateOrInsert(
        			['user_id' =>$addUser->id, 'class_id' => $class_code->id]);
        	}else{
        		$obj_class=new ClassCode;
        		$obj_class->class_name=$request->class_code;
        		$obj_class->class_code=$request->class_code;
        		$obj_class->approved=0;
        		$obj_class->save();
        		DB::table('user_class_code')->insert(
        			['user_id' =>$addUser->id, 'class_id' => $obj_class->id]);
          }
        	}

           if(isset($request->student_id)) {
            $insert=array();
            $explode=explode(',',$request->student_id);
            foreach($explode as $single){
            
            DB::table('parent_childrens')->updateOrInsert(
             [
                    'parent_id' => $addUser->id,
                    'children_id' => $single,
                     'relationship' => $request->relationship
                   ]);
           }
         }
         return response()->json(array('error' => false, 'data' =>$addUser ), 200);
       }
       else{
         throw new Exception('Something went wrong');
       }
     }
   } catch (\Exception $e) {
     return response()->json(array('error' => true, 'errors' => $e->getMessage()), 200);
   }

 }

 public function GetStudents(Request $request){
 $input = $request->all();
  $validator = Validator::make($input, [
        'school_id' => 'required'
      ]);
       
       if ($validator->fails()) {
         throw new Exception($validator->errors()->first());
       }  
       else{ 
        $students=User::with('SchoolDetail')->where('role_id',2)->get();
         return response()->json(array('error' => false, 'message' => 'Students fetched successfully', 'data' => $students), 200);

       }

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
}
