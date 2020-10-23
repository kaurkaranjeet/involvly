<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\ClassCode;
use App\Models\JoinedStudentClass;
use App\Models\ParentChildrens;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;

class StudentController extends Controller {

    public function __construct() {
        
    }

    // Register Student
    public function StudentRegister(Request $request) {
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
            } else {

                $student_obj = new User;
                $addUser = $student_obj->store($request);
                $token = JWTAuth::fromUser($addUser);
                $addUser->token = $token;

                //clascodes
                if (!empty($addUser)) {
                    if (!empty($request->class_code)) {
                        $class_code = ClassCode::where('class_code', $request->class_code)->first();
                        if (!empty($class_code)) {
                            DB::table('user_class_code')->updateOrInsert(
                                    ['user_id' => $addUser->id, 'class_id' => $class_code->id]);
                        } else {
                            return response()->json(array('error' => true, 'message' => 'Class code is not valid.'), 200);
                        }
                    }
                    return response()->json(array('error' => false, 'data' => $addUser), 200);
                } else {
                    throw new Exception('Something went wrong');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function Checkifclassvalid(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                        'class_code' => 'required'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $class_code = ClassCode::where('class_code', $request->class_code)->first();
                if (!empty($class_code)) {
                    return response()->json(array('error' => false, 'message' => 'Class Code is valid', 'data' => $class_code), 200);
                } else {
                    throw new Exception('Class code is not valid.');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // JoinStudentByClasscode
    public function JoinStudentByClass(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'student_id' => 'required|exists:users,id',
                    'subject_id' => 'required|exists:class_code_subject,id',
                    'class_id' => 'required|exists:class_code,id',
                    'school_id' => 'required|exists:schools,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $joined = new JoinedStudentClass; //then create new object
            $joined->student_id = $request->student_id;
            $joined->subject_id = $request->subject_id;
            $joined->class_id = $request->class_id;
            $joined->school_id = $request->school_id;
            $joined->join_date = $request->join_date;
            $joined->status = 1;
            $joined->save();
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $joined), 200);
        }
    }
     //LeaveStudentByClass
    public function LeaveStudentByClass(Request $request){

        $validator = Validator::make($request->all(), [
                    'student_id' => 'required|exists:users,id',
                    'subject_id' => 'required|exists:class_code_subject,id',
                    'class_id' => 'required|exists:class_code,id',
                    'school_id' => 'required|exists:schools,id',
 
     ]);
         if ($validator->fails()) {
             return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
         } else {
               $delete= JoinedStudentClass::where('student_id',$request->student_id)->where('subject_id',$request->subject_id)->where('class_id',$request->class_id)->where('school_id',$request->school_id)->delete();
             if ($delete) {
                 return response()->json(array('error' => false, 'message' => 'Student leave class successfully', 'data' => []), 200);
             } else {
                 return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
             }
         }
     }

}
