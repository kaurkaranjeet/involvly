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
class StudentController extends Controller {
    public function __construct()
    { 
    }

       // Register Student
    public function StudentRegister(Request $request){
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
             return response()->json(array('error' => true, 'data' =>'Class Code is not valid'), 200);
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
    
            
           
            return response()->json(array('error' => false, 'message' => 'profile fetched successfully', 'data' => $data), 200);

        }

    }

    public function Checkifclassvalid(Request $request) {
      try{
        $input = $request->all();
        $validator = Validator::make($input, [
          'class_code' => 'required'
        ]);    
        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        }  
        else{ 
         $class_code=  ClassCode::where('class_code',$request->class_code)->first();
         if(!empty($class_code)){
          return response()->json(array('error' => false, 'message' => 'class code is valid', 'data' => $class_code), 200);
        }  
        else{
          throw new Exception('class code is not valid');
        }
      }

    }
    catch (\Exception $e) {
     return response()->json(array('error' => true, 'errors' => $e->getMessage()), 200);
   }

 }
}
