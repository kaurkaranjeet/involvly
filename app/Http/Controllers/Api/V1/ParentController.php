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
use App\Models\ClassUser;
use App\Models\UnapproveStudent;
use App\Models\ParentTask;
use App\Models\ParentTaskAssigned;
use App\Models\ParentChildrens;
use App\Models\Schedule;
use App\Notification;
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
                       //  'device_token' => 'required',
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
                $addUser->jwt_token = $token;
                //clascodes
                if (!empty($addUser)) {
                   //User::where('id',$addUser->id)->update(['device_token' => $request->device_token]);
                    if (!empty($request->class_code)) {
                        $class_code = ClassCode::where('class_code', $request->class_code)->first();
                        if (!empty($class_code)) {
                          $classobj=  ClassUser::create(
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
                     // Store unapprove Student
                    if($request->has('full_name')){
                     $data = [];
                     if($request->hasfile('documents'))
                     {                     
                      foreach($request->file('documents') as $key=>$file)
                      {
                        $name=time().$key.'.'.$file->getClientOriginalExtension();    
                        $file->move(public_path().'/documents/', $name);      
                        $data[$key] = URL::to('/').'/documents/'.$name;  
                      }
                    }


                      
                 $unapprove_student= UnapproveStudent::create(['full_name'=>$request->full_name,'parent_id'=>$addUser->id,'school_id'=>$request->school_id,'documents'=>$data,'status'=>'0','class_code_id'=>$request->class_id]);

                    }
                   $addUser= User::with('StateDetail')->with('CityDetail')->with('SchoolDetail')->where('id',$addUser->id)->first();
                   if(isset($classobj)){
                    $addUser->class_id=$class_code->id;
                    $addUser->class_name=$class_code->class_name;
                  }else{
                   $addUser->class_id='';
                   $addUser->class_name=='';
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

            $students = User::where('role_id', 2)->where('users.type_of_schooling', 'home')->where('users.city', $request->city_id)->get();
            
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
       // 'student_id' => 'required_if:type_of_schooling, =,school'
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
   
        User::where('id',$request->parent_id)->update(['type_of_schooling' => $request->type_of_schooling]);
        if(isset($request->school_id)){
        User::where('id',$request->parent_id)->whereNull('school_id')->update(['school_id' => $request->school_id]);
    }
  }

$data_document = [];
   // Store unapprove Student
                    if($request->has('full_name')){
                     
                     if($request->hasfile('documents'))
                     {                     
                      foreach($request->file('documents') as $key=>$file)
                      {
                        $name=time().$key.'.'.$file->getClientOriginalExtension();    
                        $file->move(public_path().'/documents/', $name);      
                        $data_document[$key] = URL::to('/').'/documents/'.$name;  
                      }
                    }
                      
                 $addUser= UnapproveStudent::create(['full_name'=>$request->full_name,'parent_id'=>$request->parent_id,'school_id'=>$request->school_id,'documents'=>$data_document,'status'=>'0','class_code_id'=>$request->class_id]);

                    }

        return response()->json(array('error' => false, 'data' =>$addUser,'message' => 'Child added successfully.','documents' => $data_document), 200);
      
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
                    'schedule_id' => 'required|exists:schedules,id',
                    'task_time' => 'required',
                   
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
          } else {
            $task = new ParentTask; //then create new object
            $task->task_assigned_by = $request->task_assigned_by;
            $task->task_name = $request->task_name;
            $task->schedule_id = $request->schedule_id;            
            $task->task_time = $request->task_time;
            $task->task_description = $request->task_description;
            $data = [];
            if($request->hasfile('image'))
            {

              foreach($request->file('image') as $key=>$file)
              {
                $name=time().$key.'.'.$file->getClientOriginalExtension();    
                $file->move(public_path().'/images/', $name);      
                $data[$key] = URL::to('/').'/images/'.$name;  
              }
            }
            $task->image=$data;
              $task->notes=$request->notes;
                $days_data = [];

            if (!empty($request->selected_days)) {
              $selected_days=explode(",",$request->selected_days);
              foreach ($selected_days as $key => $selected_day) {
                // $selected_day = date("d/m/Y", strtotime($selected_day));
                $days_data[$key] = $selected_day;
              }
            }
            $task->selected_days = $days_data;

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
           // if($request->notify_parent=='1'){
              $message='A new task has been assigned to you';
              if (!empty($user_data_to->device_token)) { 
                SendAllNotification($user_data_to->device_token, $message, 'school_notification');
              }
              $notificationobj=new Notification;
              $notificationobj->user_id=$user_data_to->id;
              $notificationobj->notification_message=$message;
              $notificationobj->notification_type='TaskAssigned';
              $notificationobj->type='school_notification';
              $notificationobj->from_user_id=$user_data_by->id;
              $notificationobj->save();
          //  }
               $data=array(
                   'name'=>$user_data_to->name,
                   'email'=>$user_data_to->email,
                   'task_creator' => $user_data_by->name,
                   'task_name' => $request->task_name,
                   'task_date' =>$days_data,
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


    public function AddSchedule(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'created_by' => 'required|exists:users,id',
                    'assigned_to' => 'required',
                    'schedule_name' => 'required',                 
                    'from_time' => 'required',
                    'to_time' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $task = new Schedule; //then create new object
            $task->created_by = $request->created_by;
            $task->schedule_name = $request->schedule_name; 
            $task->from_time = $request->from_time;
            $task->to_time = $request->to_time;
            $task->assigned_to = $request->assigned_to;
            $task->description = $request->description;
            $days_data = [];

            if (!empty($request->selected_days)) {
              $selected_days=explode(",",$request->selected_days);
              foreach ($selected_days as $key => $selected_day) {
             //  $selected_day = date("d/m/Y", strtotime($selected_day));
                $days_data[$key] = $selected_day;
              }
            }
            $task->selected_days = $days_data;
            $task->save();
            
           
            $user_data_by = User::where('id', $request->created_by)->first();
   
            $users_explode = explode(',', $request->assigned_to);
            foreach ($users_explode as $single) {   
             $user_data_to = User::where('id', $single)->first();
            //email notification 
            if($request->notify_parent=='1'){
              $message='A new schedule has been assigned to you';
              if (!empty($user_data_to->device_token)) { 
                SendAllNotification($user_data_to->device_token, $message, 'school_notification');
              }
              $notificationobj=new Notification;
              $notificationobj->user_id=$user_data_to->id;
              $notificationobj->notification_message=$message;
              $notificationobj->notification_type='TaskAssigned';
              $notificationobj->type='school_notification';
              $notificationobj->from_user_id=$user_data_by->id;
              $notificationobj->save();
            }
               $data=array(
                   'name'=>$user_data_to->name,
                   'email'=>$user_data_to->email,
                   'task_creator' => $user_data_by->name,
                   'task_name' => $request->schedule_name,
                   'task_date' =>$days_data,
                   'from_time' => $request->from_time,
                    'to_time' => $request->to_time,
                   'task_description' => $request->description,
                );
               Mail::send("email.assigned-schedule", $data, function ($m) use ($user_data_to) {
               $m->from('involvvely@gmail.com','Involvvely');
               $m->to($user_data_to->email);
               $m->subject('Assigned Task');
               }); 
            }
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        
    }

  }



    //Get tasks
    public function Getschedules(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $tasks = Schedule::with('User')->where('created_by', $request->user_id)->orderBy('id', 'DESC')->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }


    //Get tasks
    public function GetScheduleTask(Request $request) {
        $validator = Validator::make($request->all(), [
                    'schedule_id' => 'required|exists:schedules,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $tasks = ParentTask::with('User')->where('schedule_id', $request->schedule_id)->orderBy('id', 'DESC')->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }

     //Get tasks assigned to me 
    public function TaskAssignedToMe(Request $request) {
        $validator = Validator::make($request->all(), [
                    'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $tasks = ParentTaskAssigned::with('User','ParentTask')->where('task_assigned_to', $request->user_id)->orderBy('id', 'DESC')->get();
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
           $accept_reject_data= ParentTaskAssigned::with('User','ParentTask.User')->where(['task_id'=>$request->task_id ,'task_assigned_to'=>$request->parent_id])->first();
         if(!empty($accept_reject_data)){
       
          if($request->accept_reject==1){
            $message=$accept_reject_data->User->name.' has accepted the task ' .$accept_reject_data->ParentTask->task_name;
          }
          else
          {
           $message=$accept_reject_data->User->name.' has rejected the task ' .$accept_reject_data->ParentTask->task_name;

         }
        // SElect parent_tasks.*, (CASE WHEN (Select Count(id) from parent_task_assigned where task_id=parent_tasks.id AND (parent_task_assigned.accept_reject=1 OR parent_task_assigned.accept_reject=0)) > 0  THEN parent_task_assigned.accept_reject ELSE '2' END )   from parent_tasks 
       }
       else{
  

       }
            $tasks = ParentTask::with('User:id,name')->with('AssignedUser.AssignedTo')->where('id', $request->task_id)->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
       
         return response()->json(array('error' => false, 'message' => 'this task is pending', 'data' => []), 200);
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


        //remove task
    public function RemoveAssignScheduleTask(Request $request){

        $validator = Validator::make($request->all(), [
         'task_id' => 'required|exists:parent_tasks,id',
         'task_assigned_to' => 'required|exists:users,id',

     ]);
         if ($validator->fails()) {
             return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
         } else {
               $delete_assigned =  ParentTaskAssigned::where('task_id',$request->task_id)->where('task_assigned_to',$request->task_assigned_to)->delete();                 
             if ($delete_assigned) {
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
         $results= ParentChildrens::select(DB::raw('DISTINCT parent_id'))->with('ParentDetails')->whereRaw('children_id IN('.$childrens.')')->where('parent_id','!=',$request->parent_id)->get();
         if(!empty($results)){
           return response()->json(array('error' => false, 'data' =>$results,'message' => 'Parents fetched successfully.' ), 200);
         }else{
          throw new Exception('No another parents');
        }
      }
      else{
        throw new Exception('No children');
      }
    }
  }
  catch (\Exception $e) {
    return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
  }
}

     public function AcceptRejectTask(Request $request){
        $validator = Validator::make($request->all(), [
                    'task_id' => 'required|exists:parent_tasks,id',
                     'accept_reject' => 'required',
                     'parent_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {

          ParentTaskAssigned::where(['task_id'=>$request->task_id ,'task_assigned_to'=>$request->parent_id])->update(['accept_reject'=>$request->accept_reject]);
         $accept_reject_data= ParentTaskAssigned::with('User','ParentTask.User')->where(['task_id'=>$request->task_id ,'task_assigned_to'=>$request->parent_id])->first();
         if(!empty($accept_reject_data)){
          if($request->accept_reject==1){
            $message=$accept_reject_data->User->name.' has accepted the task ' .$accept_reject_data->ParentTask->task_name;
            if (!empty($accept_reject_data->ParentTask->User->device_token)) { 
              SendAllNotification($accept_reject_data->ParentTask->User->device_token, $message, 'school_notification');
            }
          }
           if($request->accept_reject==2){
           $message=$accept_reject_data->User->name.' has rejected the task ' .$accept_reject_data->ParentTask->task_name;
           if (!empty($accept_reject_data->ParentTask->User->device_token)) { 
              SendAllNotification($accept_reject_data->ParentTask->User->device_token, $message, 'school_notification');
            }

         }
         
            
            $notificationobj=new Notification;
            $notificationobj->user_id=$accept_reject_data->ParentTask->User->id;
            $notificationobj->notification_message=$message;
            $notificationobj->notification_type='TaskAssigned';
            $notificationobj->type='school_notification';
            $notificationobj->from_user_id=$accept_reject_data->User->id;
            $notificationobj->save();
          }
          else{
            $accept_reject_data=new ParentController();
          }

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $accept_reject_data), 200);
        }
    }


 public function AcceptRejectSchedule(Request $request){
        $validator = Validator::make($request->all(), [
                    'schedule_id' => 'required|exists:schedules,id',
                     'accept_reject' => 'required',
                     'parent_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
         $schedule= Schedule::with('User')->find($request->schedule_id);
         $user=User::find($request->parent_id);        
         if(!empty($schedule)){
          if($request->accept_reject==1){
           
              $array=$schedule->accept_reject_schedule;
              array_push($array,$request->parent_id);

           

            $message=$user->name.' has accepted the schedule ' .$schedule->schedule_name; 
          }
          if($request->accept_reject==2){
            $message=$user->name.' has rejected the schedule ' .$schedule->schedule_name;
          }
               $schedule->save();


            $notificationobj=new Notification;
            $notificationobj->user_id=$schedule->created_by;
            $notificationobj->notification_message=$message;
            $notificationobj->notification_type='ScheduleAssign';
            $notificationobj->type='school_notification';
            $notificationobj->from_user_id=$user->id;
            $notificationobj->save();
          
          

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $schedule), 200);
          }
        }
    }

}
