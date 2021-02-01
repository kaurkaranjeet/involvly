<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\UserClassCode;
use App\Models\AssignedTeacher;
use App\Models\ClassSubjects;
use App\Models\Timetable;
use App\Models\ParentChildrens;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class TimetableController extends Controller {

    public function __construct() {
        
    }

    // Add assignment
    public function AddTimetable(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'teacher_id' => 'required|exists:users,id',
                    'date' => 'required',
                    'selected_days' => 'required',
                    'timetable_doc' => 'required',
                    'school_id' => 'required|exists:schools,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
        	$selected_timetable=Timetable::where('teacher_id',$request->teacher_id)->where('school_id',$request->school_id)->first();
        	if(!empty($selected_timetable)){
        		if(in_array('Everyday',$selected_timetable->selected_days)){
        			return response()->json(array('error' => true, 'message' =>'You have already selected timetable for everyday' ), 200);
        		} else{
        			$implode= implode(',' ,$selected_timetable->selected_days);
        			$explode=explode(',' ,$implode);
        			foreach ($request->selected_days as  $selected_days) {
        				if(in_array($selected_days,$explode)){
        					return response()->json(array('error' => true, 'message' =>$selected_days.' is already selected.'  ), 200);
        				}

        			}
        		}
        	}


            $task = new Timetable; //then create new object
            $task->teacher_id = $request->teacher_id;
            $task->date = $request->date;
            $task->school_id = $request->school_id;
            // $task->selected_days = $request->assignments_description;
            // $task->timetable_doc = $request->assignments_special_instruction;

            $days_data = [];
            if (!empty($request->selected_days)) {
                foreach ($request->selected_days as $key => $selected_days) {
                    $days_data[$key] = $selected_days;
                }
            }
            $task->selected_days = $days_data;
            $timetable_doc_data='';            
            if ($request->hasfile('timetable_doc')) {
            	$file=$request->file('timetable_doc');
            	$name=trim($file->getClientOriginalName());  
                $file->move(public_path() . '/timetable_doc/', $name);
                $timetable_doc_data = URL::to('/') . '/timetable_doc/' . $name;                
            }
            $task->timetable_doc = $timetable_doc_data;
            $task->save();
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        
    }
    }

    //Get timetable List
    public function getTimetable(Request $request) {
        $validator = Validator::make($request->all(), [
                    'teacher_id' => 'required|exists:users,id',
                    'school_id' => 'required|exists:schools,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $timetable = Timetable::with('User')->orderBy('id', 'DESC')->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $timetable), 200);
        }
    }

    //remove TIMETABLE
    public function RemoveTimetable(Request $request) {

        $validator = Validator::make($request->all(), [
                    'timetable_id' => 'required|exists:timetables,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $delete = Timetable::where('id', $request->timetable_id)->delete();
            if ($delete) {
                return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    //fetch assigned teachers 
    public function FetchAssignedTeachersByStudent(Request $request) {
        $validator = Validator::make($request->all(), [
                    'class_id' => 'required|exists:class_code,id',
                    'school_id' => 'required|exists:schools,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $teachers = AssignedTeacher::with('User')->with('Subjects')->where('class_id', $request->class_id)->where('school_id', $request->school_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $teachers), 200);
        }
    }

    //FetchChildByParents
    public function FetchChildByParents(Request $request) {
        $validator = Validator::make($request->all(), [
                    'parent_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
//            $teachers = ParentChildrens::with('ChildDetails')->where('parent_id', $request->parent_id)->get();
            $teachers = ParentChildrens::with('ChildDetails')
                            ->leftJoin('user_class_code', 'parent_childrens.children_id', '=', 'user_class_code.user_id')
                            ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
                            ->select('parent_childrens.*','user_class_code.class_id','class_code.class_name')
                            ->where('parent_id', $request->parent_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $teachers), 200);
        }
    }

}
