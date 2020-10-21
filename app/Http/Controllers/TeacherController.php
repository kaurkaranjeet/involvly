<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\ClassCode;
use App\Models\AssignedTeacher;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class TeacherController extends Controller {

    public function Approveteacher($id) {
        $data = User::where('id', $id)->update(['status' => '1']);
        return response()->json(compact('data'), 200);
    }

    public function DisApproveteacher($id) {
        $data = User::where('id', $id)->update(['status' => '0']);
        return response()->json(compact('data'), 200);
    }

    //Assigned teachers
    public function fetchAssignedTeachersToClasses(Request $request) {
        DB::enableQueryLog();
        // $teachers = User::with('SchoolDetail:id,school_name')
        //                     ->leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')
        //                     ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
        //                     ->select('users.*','class_code.class_name')->where('role_id', 4)->where('user_class_code.class_id', $id)->get();
        $teachers = User::where('role_id', 4)->where('school_id',$request->school_id)->get();
                            foreach($teachers as $teacher){
                                $is_added = AssignedTeacher::where('subject_id',$request->subject_id)->where('class_id',$request->class_id)->where('school_id',$request->school_id)->where('teacher_id',$teacher->id)->count();
                                 if( $is_added>0){
                                    $teacher->is_added=1;
                                 }else{
                                    $teacher->is_added=0;
                                 }
                             }
        if (!empty($teachers)) {
            return response()->json(compact('teachers'), 200);
        } else {
            return response()->json(['error' => 'true', 'teachers' => [], 'message' => 'No record found'], 200);
        }
    }
    //Add subject to teacher
    public function AddTeacherSubject(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'subject_id' => 'required',
        'class_id' => 'required',
        'school_id' => 'required',
        'teacher_id' => 'required',
    ]);

    if($validator->fails()){
            return response()->json([ 'error' =>true, 'message'=>$validator->errors()->first()], 200);
    }  

    $data=new  AssignedTeacher;
    $data->class_id =$request->class_id;
    $data->subject_id =$request->subject_id;
    $data->school_id =$request->school_id;
    $data->teacher_id =$request->teacher_id;
    $data->save();

    $data->is_added=0;
    return response()->json(compact('data'),200);
      
    }
    //remove subject to teacher
    public function RemoveTeacherSubject(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'subject_id' => 'required',
        'class_id' => 'required',
        'school_id' => 'required',
        'teacher_id' => 'required',
    ]);

    if($validator->fails()){
            return response()->json([ 'error' =>true, 'message'=>$validator->errors()->first()], 200);
    }
   $data = AssignedTeacher::where('class_id' , $request->class_id)->where('subject_id', $request->subject_id)->where('school_id', $request->school_id)->where('teacher_id', $request->teacher_id)->delete();
   $data=new SubjectController;
   $data->is_added=1;
    return response()->json(compact('data'),200);
      
    }

}

?>