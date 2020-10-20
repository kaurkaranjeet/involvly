<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\ClassCode;
use App\Models\ParentTask;
use App\Models\ParentTaskAssigned;
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
   public function AddChildren(Request $request){
      try {

       $input = $request->all();
       $validator = Validator::make($input, [
        'type_of_schooling' => 'required',
        'parent_id' => 'required',
        'school_id' => 'required_if:type_of_schooling, =,school',
        //'class_code' => 'required_if:type_of_schooling, =,school',
        'student_id' => 'required_if:type_of_schooling, =,school'
      ]);
       
       if ($validator->fails()) {
         throw new Exception($validator->errors()->first());
       }  
       else{     
        //clascodes
        if(!empty($request->class_code)) {
          $class_code=  ClassCode::where('class_code',$request->class_code)->first();
          if(!empty($class_code)){
            DB::table('user_class_code')->insert(
              ['user_id' =>$request->parent_id, 'class_id' => $class_code->id]);
          }else{
           return response()->json(array('error' => true, 'message' =>'Class code is not valid.'), 200);
         }
       }

       if(!empty($request->student_id)) {  
        $explode=explode(',', $request->student_id);
        if(!empty($explode)){
          foreach($explode as $student_id){
            DB::table('parent_childrens')->insert(
             [
              'parent_id' =>$request->parent_id,
              'children_id' =>$student_id,
              'relationship' => $request->relationship
            ]);
          }
        }
    $addUser= DB::table('parent_childrens')->where('parent_id',$request->parent_id)->whereIn('children_id', array($request->student_id))->get();
        return response()->json(array('error' => false, 'data' =>$addUser,'message' => 'Child added successfully.' ), 200);
      }
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
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $task = new ParentTask; //then create new object
            $task->task_assigned_by = $request->task_assigned_by;
            $task->task_name = $request->task_name;
            $task->task_date = $request->task_date;
            $task->task_time = $request->task_time;
            $task->task_description = $request->task_description;
            $task->save();
            $tasks = [];
            $users_explode = explode(',', $request->task_assigned_to);
            foreach ($users_explode as $single) {
             
            $task_assigned = new ParentTaskAssigned; //then create new object
            $task_assigned->task_id = $task->id;
            $task_assigned->task_assigned_to = $single; 
            $task_assigned->save();
            array_push($tasks , $task_assigned);
            //get data according to user id
            $user_data_by = User::where('id', $request->task_assigned_by)->first();
            $user_data_to = User::where('id', $single)->first();
            //email notification 
               $data=array(
                   'name'=>$user_data_to->name,
                   'email'=>$user_data_to->email,
                   'task_creator' => $user_data_by->name,
                   'task_name' => $request->task_name,
                   'task_date' => $request->task_date,
                   'task_time' => $request->task_time,
                   'task_description' => $request->task_description,
                );
               Mail::send("email.assigned-task", $data, function ($m) use ($user_data_to) {
               $m->from('involvvely@gmail.com','Involvvely');
               $m->to($user_data_to->email);
               $m->subject('Assigned Task');
               }); 
            }
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $tasks), 200);
        }
    }

    //Get tasks
    public function GetScheduleTask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $tasks = ParentTask::with('User')->where('task_assigned_by', $request->user_id)->orderBy('id', 'DESC')->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }
    
    //task detail
    public function GetScheduleTaskDetail(Request $request){
        $validator = Validator::make($request->all(), [
                    'task_id' => 'required|exists:parent_tasks,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $tasks = ParentTask::with('User')->with('AssignedUser.User')->where('id', $request->task_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }

    //remove task
    public function RemoveScheduleTask(Request $request){

        $validator = Validator::make($request->all(), [
         'task_id' => 'required|exists:parent_tasks,id',
 
     ]);
         if ($validator->fails()) {
             return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
         } else {
               $delete= ParentTask::where('id',$request->task_id)->delete();
               $delete_assigned =  ParentTaskAssigned::where('task_id',$request->task_id)->delete();                 
             if ($delete) {
                 return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
             } else {
                 return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
             }
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
