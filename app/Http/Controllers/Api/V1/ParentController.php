<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\ClassCode;
use App\Models\ParentTask;
use App\Models\ParentChildrens;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class ParentController extends Controller {

    public function __construct() {
        
    }

    // Register Student
    public function ParentRegister(Request $request) {
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

                    if (isset($request->student_id)) {
                        $explode = explode(',', $request->student_id);
                        foreach ($explode as $single) {

                            DB::table('parent_childrens')->updateOrInsert(
                                    [
                                        'parent_id' => $addUser->id,
                                        'children_id' => $single,
                                        'relationship' => $request->relationship
                            ]);
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

    public function GetStudents(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'school_id' => 'required',
                    'class_code' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        } else {
            DB::enableQueryLog();
            //  $students=User::with('SchoolDetail')

            $students = User::with('SchoolDetail:id,school_name')
                            ->leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')
                            ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
                            ->select('users.*', 'class_code.class_name')->where('role_id', 2)->where('class_code.class_code', $request->class_code)->where('users.school_id', $request->school_id)->get();
            return response()->json(array('error' => false, 'message' => 'Students fetched successfully', 'data' => $students), 200);
        }
    }

    public function GethomeStudents(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        } else {
            DB::enableQueryLog();
            //  $students=User::with('SchoolDetail')

            $students = User::leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')
                            ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
                            ->select('users.*', 'class_code.class_name')->where('role_id', 2)->where('users.type_of_schooling', 'home')->where('users.city', $request->city_id)->get();
            return response()->json(array('error' => false, 'message' => 'Students fetched successfully', 'data' => $students), 200);
        }
    }

    // Register Student
    public function AddChildren(Request $request) {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                        'type_of_schooling' => 'required',
                        'parent_id' => 'required',
                        'school_id' => 'required_if:type_of_schooling, =,school',
                        'class_code' => 'required_if:type_of_schooling, =,school',
                        'student_id' => 'required_if:type_of_schooling, =,school'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                //clascodes
                if (!empty($request->class_code)) {
                    $class_code = ClassCode::where('class_code', $request->class_code)->first();
                    if (!empty($class_code)) {
                        DB::table('user_class_code')->insert(
                                ['user_id' => $request->parent_id, 'class_id' => $class_code->id]);
                    } else {
                        return response()->json(array('error' => true, 'message' => 'Class code is not valid.'), 200);
                    }
                }

                if (!empty($request->student_id)) {

                    $addUser = DB::table('parent_childrens')->insert(
                            [
                                'parent_id' => $request->parent_id,
                                'children_id' => $request->student_id,
                                'relationship' => $request->relationship
                    ]);

                    $addUser = DB::table('parent_childrens')->where('parent_id', $request->parent_id)->where('children_id', $request->children_id)->first();
                }
                return response()->json(array('error' => false, 'data' => $addUser, 'message' => 'Child added successfully.'), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Create task 
    public function AddScheduleTask(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'task_assigned_by' => 'required|exists:users,id',
                    'task_assigned_to' => 'required',
                    'task_name' => 'required',
                    'task_date' => 'required',
                    'task_time' => 'required',
                    'task_description' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
        } else {
            $task = new ParentTask; //then create new object
            $task->task_assigned_by = $request->task_assigned_by;
                $task->task_assigned_to = $request->task_assigned_to; 
            $task->task_name = $request->task_name;
            $task->task_date = $request->task_date;
            $task->task_time = $request->task_time;
            $task->task_description = $request->task_description;
            $task->save();
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        }
    }

    //Get tasks
    public function GetScheduleTask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->errors(), 'error' => true));
        } else {
            $tasks = ParentTask::with('User')->where('task_assigned_by', $request->user_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }

    public function GetRelatedParents(Request $request) {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                        'parent_id' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
       }  
       else{ 
  $results= ParentChildrens::select( DB::raw('GROUP_CONCAT(children_id) AS childrens'))->where('parent_id',$request->parent_id)->first();
   $childrens= $results->childrens;
  if(!empty($childrens)){
 $results= ParentChildrens::select('parent_id')->with('ParentDetails')->whereIn('children_id', array($childrens))->get();
if(!empty($results)){
   return response()->json(array('error' => false, 'data' =>$results,'message' => 'Parents fetched successfully.' ), 200);
}else{
                        throw new Exception('No another parents');
                    }
  }
  else{
                    throw new Exception('No childrens');
                }
            }
}
 catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

}
