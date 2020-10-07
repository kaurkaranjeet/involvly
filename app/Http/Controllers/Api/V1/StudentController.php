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
             return response()->json(array('error' => true, 'message' =>'Class Code is not valid'), 200);
           }
         }
           return response()->json(array('error' => false, 'data' =>$addUser ), 200);
       }
       else{
         throw new Exception('Something went wrong');
       }
     }
   } catch (\Exception $e) {
     return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
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
          return response()->json(array('error' => false, 'message' => 'Class Code is valid', 'data' => $class_code), 200);
        }  
        else{
          throw new Exception('Class Code is not valid');
        }
      }

    }
    catch (\Exception $e) {
     return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
   }

 }
}
