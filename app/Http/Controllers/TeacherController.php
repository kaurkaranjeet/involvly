<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\ClassCode;
use App\Models\AssignedTeacher;
use App\Models\ClassUser;
use App\Models\ClassSubjects;
use App\Models\Group;
use App\Models\School;
use Pusher\Pusher;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Exception;
use App\Events\AssignEvent;
use App\Events\SchoolApprovalEvent;
use Tymon\JWTAuth\Exceptions\JWTException;

class TeacherController extends Controller {


   public function __construct()
    {
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        );
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'), 
            $options
        );

       
    }

    public function Approveteacher($id) {
      $user=User::where('id', $id);
       $user_first=$user->first();
      if($user_first->role_id==5){
     Group::where('school_id',$user_first->school_id)->where('user_id',$user_first->id)->where('type','school_admin')->update(['status' => '1']);
     School::where('id',$user_first->school_id)->update(['approved' => '1']);
     $this->pusher->trigger('schoolapproval-channel', 'school-approved', $user_first);
      }
      $data = $user->update(['status' => '1']);

        return response()->json(compact('data'), 200);
    }

    public function DisApproveteacher($id) {
        $data = User::where('id', $id)->delete();
        return response()->json(compact('data'), 200);
    }

    //Assigned teachers
    public function fetchAssignedTeachersToClasses(Request $request) {
        DB::enableQueryLog();
        // $teachers = User::with('SchoolDetail:id,school_name')
        //                     ->leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')
        //                     ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
        //                     ->select('users.*','class_code.class_name')->where('role_id', 4)->where('user_class_code.class_id', $id)->get();

        $assignment_details = AssignedTeacher::select(DB::raw('group_concat(teacher_id) as names'))
                                ->where('school_id', $request->school_id)
                                 ->where('class_id', $request->class_id)
                                ->where('subject_id','!=', $request->subject_id)
                                ->first();

                                //echo $assignment_details->names;die;
                                if(!empty($assignment_details->names)){
                               $teachers = User::where('role_id', 4)->where('school_id',$request->school_id)->whereNotIn('id', [$assignment_details->names])->get();
                                }else{
                                     $teachers = User::where('role_id', 4)->where('school_id',$request->school_id)->get();
                                }
      
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

  $assigned=AssignedTeacher::where('class_id',$request->class_id)->where('school_id',$request->school_id)->where('subject_id',$request->subject_id)->first();
  if(!empty($assigned)){
     $data=AssignedTeacher::find($assigned->id);
  }else{
       $data=new  AssignedTeacher;
   } 
    $data->class_id =$request->class_id;
    $data->subject_id =$request->subject_id;
    $data->school_id =$request->school_id;
    $data->teacher_id =$request->teacher_id;
    $data->save();

    $data->is_added=0;
    $student_id=0;
   // if(!empty($request->student_id)){
    $states = ClassSubjects::select((DB::raw("( CASE WHEN EXISTS (
      SELECT *
      FROM joined_student_classes
      WHERE class_id = class_code_subject.class_code_id
      AND student_id= ".$student_id." AND subject_id = class_code_subject. subject_id
      ) THEN TRUE
      ELSE FALSE END)
      AS already_join  , class_code_subject.*, assigned_teachers.teacher_id, users.name")))->with('subjects')
    ->leftJoin('assigned_teachers', 'class_code_subject.subject_id', '=', 'assigned_teachers.subject_id')
    ->leftJoin('users', 'assigned_teachers.teacher_id', '=', 'users.id')->where('assigned_teachers.id', $data->id)->groupBy('subject_id')->first();
     $this->pusher->trigger('assign-channel', 'assign_teacher', $states);
  // }
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
   $assiged_id = AssignedTeacher::where('class_id' , $request->class_id)->where('subject_id', $request->subject_id)->where('school_id', $request->school_id)->where('teacher_id', $request->teacher_id)->first();
   $data = AssignedTeacher::where('class_id' , $request->class_id)->where('subject_id', $request->subject_id)->where('school_id', $request->school_id)->where('teacher_id', $request->teacher_id)->delete();
   $data=new SubjectController;
   $data->is_added=1;

     $student_id=0;

     $states = ClassSubjects::select((DB::raw("( CASE WHEN EXISTS (
      SELECT *
      FROM joined_student_classes
      WHERE class_id = class_code_subject.class_code_id
      AND student_id= ".$student_id." AND subject_id = class_code_subject. subject_id
      ) THEN TRUE
      ELSE FALSE END)
      AS already_join  , class_code_subject.*, assigned_teachers.teacher_id, users.name")))->with('subjects')
    ->leftJoin('assigned_teachers', 'class_code_subject.subject_id', '=', 'assigned_teachers.subject_id')
    ->leftJoin('users', 'assigned_teachers.teacher_id', '=', 'users.id')->where('assigned_teachers.id', $assiged_id->id)->groupBy('subject_id')->first();
     $this->pusher->trigger('assign-channel', 'assign_teacher', $states);
     
    return response()->json(compact('data'),200);
      
    }


        // Register Student
    public function TeacherRegister(Request $request){
      try {
        DB::beginTransaction();

       $input = $request->all();
       $validator = Validator::make($input, [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|unique:users',
        // 'password' => 'required',
        'password' => [
          'required',
          'string',
          'min:6',             // must be at least 6 characters in length
          // 'regex:/[a-z]/',      // must contain at least one lowercase letter
          'regex:/[A-Z]/',      // must contain at least one uppercase letter
          'regex:/[0-9]/',      // must contain at least one digit
          'regex:/[@$!%*#?&]/', // must contain a special character
      ],
        'country' => 'required',
        'state_id' => 'required|exists:states,id',
        'city' => 'required|exists:cities,id',
       'school_id' => 'required'
      ]);
       
       if ($validator->fails()) {
         throw new Exception($validator->errors()->first());
       }  
       else{ 
       // echo $request->school_id;die;

        $student_obj=new User;
        $addUser = $student_obj->store($request);
       // DB::commit();
        $token = JWTAuth::fromUser($addUser);
        $addUser->token=$token;   

        DB::commit();
        //clascodes
        if(!empty( $addUser )){
         if(!empty($request->class_id)) {
          $class_code=  ClassCode::where('id',$request->class_id)->first();
          if(!empty($class_code)){
            AssignedTeacher::create(['class_id'=>$class_code->id,'subject_id'=>$request->subject_id,'teacher_id'=>$addUser->id,'school_id'=>$addUser->school_id]);
            $classobj=  ClassUser::create(
              ['user_id' =>$addUser->id, 'class_id' => $class_code->id]);

          }else{
           return response()->json(array('error' => true, 'message' =>'Class Code is not valid'), 200);
         }
       }
          
         return response()->json(array('error' => false, 'data' =>$addUser ), 200);
           DB::commit();
       }
       else{
         throw new Exception('Something went wrong');
       }
     }
   } catch (\Exception $e) {
    DB::rollback();
     return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
   }

 }


}

?>