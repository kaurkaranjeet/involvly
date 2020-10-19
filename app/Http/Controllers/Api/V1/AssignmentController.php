<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\UserClassCode;
use App\Models\Assignment;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class AssignmentController extends Controller {

    public function __construct() {
        
    }

    // Create assignment
    public function AddAssignment(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'teacher_id' => 'required|exists:users,id',
                    'assignments_name' => 'required',
                    'assignments_description' => 'required',
                    'assignments_special_instruction' => 'required',
                    'assignments_date' => 'required',
                    'assignments_attachement' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $task = new Assignment; //then create new object
            $task->teacher_id = $request->teacher_id;
            $task->assignments_name = $request->assignments_name;
            $task->assignments_description = $request->assignments_description;
            $task->assignments_special_instruction = $request->assignments_special_instruction;
            $task->assignments_date = $request->assignments_date;
            
            $data = [];
            if($request->hasfile('assignments_attachement'))
            {
                foreach($request->file('assignments_attachement') as $key=>$file)
                {
                    dd($file);
                $name=time().$key.'.'.$file->getClientOriginalExtension();    
                $file->move(public_path().'/assignment_doc/', $name);      
                $data[$key] = URL::to('/').'/assignment_doc/'.$name;  
                }
            }
            $task->assignments_attachement = $data;
            $task->save();
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        }
    }

    //Get ClassesByTeacher
    public function GetClassesByTeacher(Request $request) {
        $validator = Validator::make($request->all(), [
                    'teacher_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $class = UserClassCode::with('Class')->where('user_id', $request->teacher_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $class), 200);
        }
    }

    //Get StudentsByClass
    public function GetStudentsByClass(Request $request) {
        $validator = Validator::make($request->all(), [
                    'class_id' => 'required|exists:class_code,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $value = '2';
            $class = UserClassCode::with(['User' => function($q) use($value){
                $q->where('role_id', '=', $value);
            }])->where('class_id', $request->class_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $class), 200);
        }
    }
    
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
