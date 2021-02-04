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
use App\Models\ParentChildrens;
use App\Notification;
use App\Models\AssignedAssignments;
use App\Models\ClassSubjects;
use App\Models\SubmittedAssignments;
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
         $class = AssignedTeacher::with('AssignedClass')->where('teacher_id', $request->teacher_id)->groupBy('class_id')->get();
           /* $classes = collect(AssignedTeacher::with('User')->with('AssignedClass')->where('teacher_id', $request->teacher_id)->get());
            $class = $classes->unique('class_id');
            $class->values()->all();*/

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $class), 200);
        }
    }

    //Get subjects by class
    public function GetSubjectsByClassTeacher(Request $request) {
        $validator = Validator::make($request->all(), [
                    'teacher_id' => 'required|exists:users,id',
                    'class_id' => 'required|exists:class_code,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $subjects = ClassSubjects::with('subjects')
                            ->leftJoin('assigned_teachers', 'class_code_subject.subject_id', '=', 'assigned_teachers.subject_id')
                            ->leftJoin('users', 'assigned_teachers.teacher_id', '=', 'users.id')
                            ->select('class_code_subject.*')
                            ->where('class_code_id', $request->class_id)->where('class_id', $request->class_id)->where('assigned_teachers.teacher_id', $request->teacher_id)->get();

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $subjects), 200);
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
                    'assignment_type' => 'required|in:WHOLE,SELECTED',
                    'subject_id' => 'required|exists:subjects,id',
                    'class_id' => 'required|exists:class_code,id',
                    'school_id' => 'required|exists:schools,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $task = new AssignedAssignments; //then create new object
            $task->assignment_id = $request->assignment_id;
            $task->assignment_type = $request->assignment_type;
            $task->class_id = $request->class_id;
            $task->subject_id = $request->subject_id;
            $task->school_id = $request->school_id;
            if ($request->assignment_type == 'SELECTED') {
                $data = [];
                if (!empty($request->assignment_assign_to)) {
                    foreach ($request->assignment_assign_to as $key => $assignment_assign_to) {
                        $data[$key] = $assignment_assign_to;
                        //add data in submitted assignments table
                        $submitted = new SubmittedAssignments; //then create new object
                        $submitted->assignment_id = $request->assignment_id;
                        $submitted->student_id = $assignment_assign_to;
                        $submitted->class_id = $request->class_id;
                        $submitted->subject_id = $request->subject_id;
                        $submitted->submitted_attachement = [];

//                        $submitted->submit_status = '0';
                        $submitted->save();

               // $child_name=User::where('id', $assignment_assign_to)->first();

                $getData = SubmittedAssignments::with('subjects')->with('Student','Assignments')->where('student_id', $assignment_assign_to)->where('assignment_id', $request->assignment_id)->first();
                 $teacher_name=User::where('id', $getData->Assignments->teacher_id)->first();
                 $message = 'You have been given an assignment for ' .$getData->subjects->subject_name. ' by ' .$teacher_name->name.' on '.date('d-m-Y',strtotime($getData->Assignments->created_at)).'. Last Date of Submission '.date('d-m-Y',strtotime($getData->Assignments->assignments_date));
                if (!empty($getData->Student->device_token)) {  
                  //  $notify_type = 'Assignment';
                 SendAllNotification($getData->Student->device_token, $message, 'school_notification',$request->assignment_id,'add_assign');
             }
             $notificationobj=new Notification;
             $notificationobj->user_id=$assignment_assign_to;
             $notificationobj->notification_message=$message;
             $notificationobj->notification_type='Assignment';
             $notificationobj->class_id=$request->class_id;
             $notificationobj->type='school_notification';
             $notificationobj->push_type='add_assign';
             $notificationobj->assignment_id=$request->assignment_id;
             $notificationobj->from_user_id=$getData->Assignments->teacher_id;
             $notificationobj->save();
                // Notification::create(['user_id'=>$assignment_assign_to,'notification_message'=>$message,'type'=>'school_notification','notification_type'=> 'Assignment','from_user_id'=>$getData->Assignments->teacher_id]); 
                        $results = ParentChildrens::with('ChildDetails')->where('children_id', $assignment_assign_to)->groupBy('parent_id')->get();
                        if (!empty($results)) {
                            foreach ($results as $users) {
                                $usersData = User::where('id', $users->parent_id)->first();
                                $message = $getData->Student->name .' has been given an assignment for ' .$getData->subjects->subject_name. ' by ' .$teacher_name->name .' on '.date('d-m-Y',strtotime($getData->Assignments->created_at)).'. Last Date of Submission '.date('d-m-Y',strtotime($getData->Assignments->assignments_date));
                    //send notification
                                if (!empty($usersData->device_token)) { 
                                   //  $notify_type = 'Assignment';
                                    SendAllNotification($usersData->device_token, $message, 'school_notification');
                                }
                                $notificationobj=new Notification;
                                $notificationobj->user_id=$usersData->id;
                                $notificationobj->notification_message=$message;
                                $notificationobj->notification_type='Assignment';
                                $notificationobj->type='school_notification';
                                $notificationobj->class_id=$request->class_id;
                                $notificationobj->from_user_id=$getData->Student->id;
                                $notificationobj->save();
                                   /* Notification::create(['user_id'=>$usersData->id,'notification_message'=>$message,'type'=>'school_notification','notification_type'=>'Assignment','from_user_id'=>$getData->Assignments->teacher_id]); */
                                
                            }
                        }


                    }



                  

                }
                $task->assignment_assign_to = $data;
            } else {
                //get students by class_id
                $students = UserClassCode::join('users','users.id','=','user_class_code.user_id')->where('class_id', $request->class_id)->where('users.role_id', '2')->get();
                if (!empty($students)) {
                    foreach ($students as $users) {
                        //add data in submitted assignments table
                        $submitted = new SubmittedAssignments; //then create new object
                        $submitted->assignment_id = $request->assignment_id;
                        $submitted->student_id = $users->user_id;
                        $submitted->class_id = $request->class_id;
                        $submitted->subject_id = $request->subject_id;
                        $submitted->submitted_attachement = [];
//                        $submitted->submit_status = '0';
                        $submitted->save();

                        // $child_name=User::where('id', $users->user_id)->first();
                $getData = SubmittedAssignments::with('subjects')->with('Student','Assignments')->where('student_id', $users->user_id)->where('assignment_id', $request->assignment_id)->first();
                 $teacher_name=User::where('id', $getData->Assignments->teacher_id)->first();
                $message = 'You have been given an assignment for ' .$getData->subjects->subject_name. ' by ' .$teacher_name->name.' on '.date('d-m-Y',strtotime($getData->Assignments->created_at)).'. Last Date of Submission '.date('d-m-Y',strtotime($getData->Assignments->assignments_date));

                if (!empty($getData->Student->device_token)) {                                  
                 
                // $notify_type = 'Assignment';
                 SendAllNotification($getData->Student->device_token, $message, 'school_notification');
             }
              $notificationobj=new Notification;
                         $notificationobj->user_id=$getData->Student->id;
                         $notificationobj->notification_message=$message;
                         $notificationobj->notification_type='Assignment';
                          $notificationobj->type='school_notification';
                           $notificationobj->class_id = $request->class_id;
                         $notificationobj->from_user_id=$getData->Assignments->teacher_id;
                         $notificationobj->save();
                 //Notification::create(['user_id'=>$getData->Student->id,'notification_message'=>$message,'type'=>'school_notification','notification_type'=> 'Assignment','from_user_id'=>$getData->Assignments->teacher_id]); 

                        $results = ParentChildrens::with('ChildDetails')->where('children_id', $users->user_id)->groupBy('parent_id')->get();
                        if (!empty($results)) {
                            foreach ($results as $users) {
                                $usersData = User::where('id', $users->parent_id)->first();

                    //send notification
                                 $message =  $getData->Student->name .' has been given an assignment for ' .$getData->subjects->subject_name. ' by ' .$teacher_name->name .' on '.date('d-m-Y',strtotime($getData->Assignments->created_at)).'. Last Date of Submission '.date('d-m-Y',strtotime($getData->Assignments->assignments_date));
                                if (!empty($usersData->device_token)) { 
                                
                                    SendAllNotification($usersData->device_token, $message, 'school_notification');
                                }

                                $notificationobj=new Notification;
                         $notificationobj->user_id=$usersData->id;
                         $notificationobj->notification_message=$message;
                         $notificationobj->notification_type='Assignment';
                          $notificationobj->type='school_notification';
                              $notificationobj->class_id = $request->class_id;
                         $notificationobj->from_user_id=$getData->Student->id;
                         $notificationobj->save();
                                  //  Notification::create(['user_id'=>$usersData->id,'notification_message'=>$message,'type'=>'school_notification','notification_type'=>'Assignment','from_user_id'=>$getData->Assignments->teacher_id]); 
                                
                            }
                        }


                    
                    }
                }
                $task->assignment_assign_to = null;
            }
            $task->save();
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        }
    }

    //Get Assignment List
    public function GetAssignmentList(Request $request) {
        $validator = Validator::make($request->all(), [
                    'teacher_id' => 'required|exists:users,id',
                    'school_id' => 'required|exists:schools,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
//            $assignment = Assignment::with('AssignedClass')->where('teacher_id', $request->teacher_id)->get();
            $assignment = Assignment::with('User')->leftJoin('assigned_assignments', 'assignments.id', '=', 'assigned_assignments.assignment_id')
                            ->leftJoin('class_code', 'assigned_assignments.class_id', '=', 'class_code.id')
                            ->select('assignments.*', 'class_code.class_name')
                            ->where('teacher_id', $request->teacher_id)->where('class_code.school_id', $request->school_id)->orderBy('assignments.id', 'DESC')->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $assignment), 200);
        }
    }

    //Get Assignment details by assignment id
    public function GetAssignmentDetails(Request $request) {
        $validator = Validator::make($request->all(), [
                    'assignment_id' => 'required|exists:assignments,id',
                    'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $assignment = Assignment::select((DB::raw("assignments.*,( CASE WHEN EXISTS (
              SELECT id
              FROM submitted_assignments
              WHERE assignment_id = assignments.id  AND submitted_assignments.submit_status = 1
              ) THEN TRUE ELSE FALSE END) AS is_submit")))->with('User:id,name,profile_image')->where('id', $request->assignment_id)->first();

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $assignment), 200);
        }
    }

    //get student asignments details
    public function GetSubmittedAssignment(Request $request) {

        $validator = Validator::make($request->all(), [
                    'assignment_id' => 'required|exists:assignments,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            //get submitted data
            $submitted_assignments = SubmittedAssignments::with('User')->with('AssignedClass')->where('assignment_id', $request->assignment_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $submitted_assignments), 200);
        }
    }

    //get student Submitted asignments details
    public function GetSubmittedAssignmentDetails(Request $request) {

        $validator = Validator::make($request->all(), [
                    'assignment_id' => 'required|exists:assignments,id',
                    'student_id' => 'required|exists:users,id',
                    'submitted_id' => 'required|exists:submitted_assignments,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            //get submitted data
            $submitted_assignments = SubmittedAssignments::with('User')->with('Assignments')->where('assignment_id', $request->assignment_id)->where('id', $request->submitted_id)->first();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $submitted_assignments), 200);
        }
    }

    //remove assignment
    public function RemoveAssignments(Request $request) {

        $validator = Validator::make($request->all(), [
                    'assignment_id' => 'required|exists:assignments,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $delete = Assignment::where('id', $request->assignment_id)->delete();
            if ($delete) {
                return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    //Get Assignment List
    public function GetStudentAssignmentList(Request $request) {
        $validator = Validator::make($request->all(), [
                    'student_id' => 'required|exists:users,id',
                    'class_id' => 'required|exists:class_code,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $students_assignments = SubmittedAssignments::with('subjects')->with('Assignments')->where('student_id', $request->student_id)->where('class_id', $request->class_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $students_assignments), 200);
        }
    }

    // Upload Assignment By Students
    public function UploadAssignmentByStudents(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'student_id' => 'required|exists:users,id',
                    'assignment_id' => 'required|exists:assignments,id',
                    'subject_id' => 'required|exists:subjects,id',
                    'class_id' => 'required|exists:class_code,id',
                    'submitted_attachement' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {

            $data = [];
            if ($request->hasfile('submitted_attachement')) {
                foreach ($request->file('submitted_attachement') as $key => $file) {
                    $name = time() . $key . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path() . '/assignment_doc/', $name);
                    $data[$key] = URL::to('/') . '/assignment_doc/' . $name;
                }
            }
            $updateData = SubmittedAssignments::where('assignment_id', $request->assignment_id)
                    ->where('student_id', $request->student_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('class_id', $request->class_id)
                    ->update([
                'submitted_attachement' => $data,
                'submit_status' => '1'
            ]);

                     
            if ($updateData) {
                $getData = SubmittedAssignments::with('subjects')->with('Assignments','Student')->where('assignment_id', $request->assignment_id)
                        ->where('student_id', $request->student_id)
                        ->where('subject_id', $request->subject_id)
                        ->where('class_id', $request->class_id)
                        ->first();

                    $teacher_detials=  User::where('id',$getData->Assignments->teacher_id)->first();
                    if(!empty($teacher_detials)){
                          //send notification to teacher
                        $message = $getData->Student->name.' has submitted an assignment.';
                        if (!empty($teacher_detials->device_token)) { 
                         SendAllNotification($teacher_detials->device_token, $message, 'school_notification',$request->assignment_id,'submitted');
                     }
                      $notificationobj=new Notification;
                         $notificationobj->user_id=$teacher_detials->id;
                         $notificationobj->notification_message=$message;
                         $notificationobj->notification_type='Assignment';
                          $notificationobj->type='school_notification';
                           $notificationobj->push_type='submitted';
                              $notificationobj->class_id = $request->class_id;
                              $notificationobj->assignment_id =$request->assignment_id;
                         $notificationobj->from_user_id=$request->student_id;
                         $notificationobj->save();

                     //Notification::create(['user_id'=>$teacher_detials->id,'notification_message'=>$message,'type'=>'school_notification','notification_type'=> 'Assignment','from_user_id'=>$request->student_id]);
                 }
                   //send notification to parents
                   $results = ParentChildrens::with('ParentDetails')->where('children_id', $request->student_id)->groupBy('parent_id')->get();
                   if (!empty($results)) {
                    foreach ($results as $users) {
                       // $usersData = User::where('id', $users->parent_id)->first();
                    //send notification
                        $message =  $getData->Student->name .' has submitted an assignment.';
                        if (!empty($users->ParentDetails->device_token) && $users->ParentDetails->device_token != null) { SendAllNotification($users->ParentDetails->device_token, $message, 'school_notification');
                        }
                         $notificationobj=new Notification;
                         $notificationobj->user_id=$users->parent_id;
                         $notificationobj->notification_message=$message;
                         $notificationobj->notification_type='Assignment';
                          $notificationobj->type='school_notification';
                              $notificationobj->class_id = $request->class_id;
                         $notificationobj->from_user_id=$request->student_id;
                         $notificationobj->save();
                       // Notification::create(['user_id'=>$users->parent_id,'notification_message'=>$message,'type'=>'school_notification','notification_type'=>'Assignment','from_user_id'=>$request->student_id]); 

                    }
                }

                return response()->json(array('error' => false, 'message' => 'Success', 'data' => $getData), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'Something went wrong', 'data' => $updateData), 200);
            }
        }
    }

    // Get Uploaded Assignment By Students
    public function GetUploadAssignmentByStudents(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'student_id' => 'required|exists:users,id',
                    'class_id' => 'required|exists:class_code,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $getData = SubmittedAssignments::with('subjects')->with('Assignments.User')->where('student_id', $request->student_id)->where('class_id', $request->class_id)->where('submit_status', '1')->orderBy('id', 'DESC')->get();

            if ($getData) {
                return response()->json(array('error' => false, 'message' => 'Success', 'data' => $getData), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'Something went wrong', 'data' => $getData), 200);
            }
        }
    }

    // Get Pending Assignment By Students
    public function GetPendingAssignmentByStudents(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'student_id' => 'required|exists:users,id',
                    'class_id' => 'required|exists:class_code,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $getData = SubmittedAssignments::with('subjects')->with('Assignments.User')->where('student_id', $request->student_id)->where('class_id', $request->class_id)->where('submit_status', '0')->orderBy('id', 'DESC')->get();
            if ($getData) {
                return response()->json(array('error' => false, 'message' => 'Success', 'data' => $getData), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'Something went wrong', 'data' => $getData), 200);
            }
        }
    }

    //remove submitted assignment by teacher
    public function RemoveSubmittedAssignments(Request $request) {

        $validator = Validator::make($request->all(), [
                    'assignment_id' => 'required|exists:assignments,id',
                    'student_id' => 'required|exists:users,id',
                    'class_id' => 'required|exists:class_code,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $delete = SubmittedAssignments::where('assignment_id', $request->assignment_id)->where('student_id', $request->student_id)->where('class_id', $request->class_id)->where('submit_status', '1')->delete();
            if ($delete) {
                return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }


    //remove submitted assignment by teacher
    public function GetSubmittedAssignments(Request $request) {

        $validator = Validator::make($request->all(), [
                    'assignment_id' => 'required|exists:assignments,id',
                    'student_id' => 'required|exists:users,id',
                    
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
       $get = SubmittedAssignments::with('Assignments')->where('assignment_id', $request->assignment_id)->where('student_id', $request->student_id)->where('submit_status', '1')->orderBy('id', 'DESC')->get();
            if ($get) {
                return response()->json(array('error' => false, 'message' => 'Get successfully', 'data' => $get), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

}
