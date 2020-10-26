<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\UserClassCode;
use App\Models\AssignedTeacher;
use App\Models\Assignment;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class AssignmentController extends Controller {

    public function __construct() {
        
    }

    // Add assignment
    public function AddAssignment(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'teacher_id' => 'required|exists:users,id',
                    'assignments_name' => 'required',
                    'assignments_description' => 'required',
                    'assignments_special_instruction' => 'required',
                    'assignments_date' => 'required',
                        // 'assignments_attachement' => 'required',
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
            if ($request->hasfile('assignments_attachement')) {
                foreach ($request->file('assignments_attachement') as $key => $file) {
                    $name = time() . $key . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path() . '/assignment_doc/', $name);
                    $data[$key] = URL::to('/') . '/assignment_doc/' . $name;
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
//          $class = AssignedTeacher::with('AssignedClass')->where('teacher_id', $request->teacher_id)->distinct('class_id')->get();
            $classes = collect(AssignedTeacher::with('User')->with('AssignedClass')->where('teacher_id', $request->teacher_id)->get());
            $class = $classes->unique('class_id');
            $class->values()->all();

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $class), 200);
        }
    }

    //Get StudentsByClass
    public function GetStudentsByClass(Request $request) {
        $validator = Validator::make($request->all(), [
                    'school_id' => 'required|exists:schools,id',
                    'class_id' => 'required|exists:class_code,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            // $value = '2';
            // $class = UserClassCode::with(['User' => function($q) use($value){
            //     $q->where('role_id', '=', $value);
            // }])->where('class_id', $request->class_id)->get();
            $class = User::with('SchoolDetail:id,school_name')
                            ->leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')
                            ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
                            ->select('users.*', 'class_code.class_name')->where('role_id', 2)->where('user_class_code.class_id', $request->class_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $class), 200);
        }
    }
    
    // Add assigned asignments
    public function AddAssignedAssignment(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'assignment_id' => 'required|exists:assignments,id',
                    'assignment_type' => 'in:WHOLE,SELECTED',
                    'class_id' => 'required|exists:class_code,id',
                    'school_id' => 'required|exists:schools,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
//            $task = new Assignment; //then create new object
//            $task->teacher_id = $request->teacher_id;
//            $task->assignments_name = $request->assignments_name;
//            $task->assignments_description = $request->assignments_description;
//            $task->assignments_special_instruction = $request->assignments_special_instruction;
//            $task->assignments_date = $request->assignments_date;
//
//            $data = [];
//            if ($request->hasfile('assignments_attachement')) {
//                foreach ($request->file('assignments_attachement') as $key => $file) {
//                    $name = time() . $key . '.' . $file->getClientOriginalExtension();
//                    $file->move(public_path() . '/assignment_doc/', $name);
//                    $data[$key] = URL::to('/') . '/assignment_doc/' . $name;
//                }
//            }
//            $task->assignments_attachement = $data;
//            $task->save();
//            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        }
    }

}
