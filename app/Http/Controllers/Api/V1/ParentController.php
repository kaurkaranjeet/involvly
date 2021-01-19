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
use Pusher\Pusher;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class ParentController extends Controller {

      public function __construct() {
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
//                    'task_assigned_to' => 'required',
                    'task_name' => 'required',
                    'schedule_id' => 'required|exists:schedules,id',
//                  'task_time' => 'required',
                   
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
             // $task->notes=$request->notes;
                $days_data = [];
                $dates=[];

            if (!empty($request->selected_days)) {
              $selected_days=explode(",",$request->selected_days);
              foreach ($selected_days as $key => $selected_day) {
                $dates[]=date("d/m/Y", strtotime($selected_day));
                // $selected_day = date("d/m/Y", strtotime($selected_day));
                $days_data[$key] = $selected_day;
              }
            }
            $task->selected_days = $days_data;

            $task->save();
            //get task_assigned_to values
            $taskassignedids = Schedule::where('id', $request->schedule_id)->first();
              
             $dates_implode=implode(',', $dates);
          
           // $users_explode = explode(',', $request->task_assigned_to);
            $user_data_by = User::where('id', $request->task_assigned_by)->first();
           // foreach ($users_explode as $single) {

            $task_assigned = new ParentTaskAssigned; //then create new object
            $task_assigned->task_id = $task->id;
//            $task_assigned->task_assigned_to = $request->task_assigned_to; 
            $task_assigned->task_assigned_to = $taskassignedids->assigned_to; 
            if($request->task_assigned_by == $taskassignedids->assigned_to){
            $task_assigned->handover = '1';     
            }else{
            $task_assigned->handover = $taskassignedids->handover; 
            }
           
            $task_assigned->save();

            if( $taskassignedids->handover=='1'){

              $addded = ParentTask::select((DB::raw("( CASE WHEN EXISTS (
              SELECT *
              FROM parent_task_assigned
              WHERE parent_task_assigned.task_id = parent_tasks.id  AND parent_task_assigned.accept_reject = 3
              ) THEN TRUE
              ELSE FALSE END)
              AS is_complete,parent_tasks.*")))->with('User')->where('id', $task->id)->first();
//             $addded->task_assigned_to=$request->task_assigned_to;
             $addded->task_assigned_to=$taskassignedids->assigned_to;
             $this->pusher->trigger('task-channel', 'task_add', $addded);
           
            $user_data_to = User::where('id', $task_assigned->task_assigned_to)->first();
            //email notification 
       
          

              $message='A new task has been assigned to you in this schedule \''. $taskassignedids.'\'';
              if (!empty($user_data_to->device_token)) { 
                SendAllNotification($user_data_to->device_token, $message, 'school_notification');
              }
              $notificationobj=new Notification;
              $notificationobj->user_id=$user_data_to->id;
              $notificationobj->notification_message=$message;
              $notificationobj->notification_type='task_assign';
              $notificationobj->task_id=$task->id;
              $notificationobj->schedule_id=$task->schedule_id;
              $notificationobj->type='school_notification';
              $notificationobj->from_user_id=$user_data_by->id;
              $notificationobj->save();
       
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
            //}
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
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
           $selected_days=explode(",",$request->selected_days);
        $contain= Schedule:: whereRaw('JSON_CONTAINS(selected_days,'.$selected_days.')')->where('created_by',$request->created_by)->count();
          if($contain > 0){
   return response()->json(array('error' => true, 'message' =>'You have already selected this date'), 200);
          }
         // Schedule::whereJsonContains('selected_days', 1)
            $task = new Schedule; //then create new object
            $task->created_by = $request->created_by;
            $task->schedule_name = $request->schedule_name; 
            $task->from_time = $request->from_time;
            $task->to_time = $request->to_time;
            $task->assigned_to = $request->assigned_to;
            $task->description = $request->description;
            if($request->created_by == $request->assigned_to){
            $task->handover = '1';     
            }else{
            $task->handover = '0'; 
            }
            $days_data = [];

            if (!empty($request->selected_days)) {
             
              foreach ($selected_days as $key => $selected_day) {
             //  $selected_day = date("d/m/Y", strtotime($selected_day));
                $days_data[$key] = $selected_day;
              }
            }
            $task->selected_days = $days_data;
            $task->save();

//            $user= User::whereIn('id', explode(',',$task->assigned_to))->select('name','id')->get(); 
            $user = [];
            $user= User::where('id', $task->assigned_to)->select('name','id')->get();
            $task->assigned_to=$user;

            $task->User;
            if($task->handover=='1'){
               $task->is_accept='0';

            $this->pusher->trigger('schedule-channel', 'schedule_user', $task);
          }
           
            $user_data_by = User::where('id', $request->created_by)->first();



//            $users_explode = explode(',', $request->assigned_to);
//            foreach ($users_explode as $single) {   
             $user_data_to = User::where('id', $request->assigned_to)->first();
            //email notification             
        if($request->notify_parent=='1'){
           $notify_date=date('d/m/Y',strtotime($request->notify_date));
//Good News! Your schedule |schedule name here| is set for Saturday , January 4th, 2018 to Sunday, January 5th, 2018.
              $message='A new schedule has been assigned to you on '.$notify_date.' at '.$request->notify_time ;
              if (!empty($user_data_to->device_token)) { 
                SendAllNotification($user_data_to->device_token, $message, 'school_notification');
              }
              $notificationobj=new Notification;
              $notificationobj->user_id=$user_data_to->id;
              $notificationobj->notification_message=$message;
              $notificationobj->notification_type='schedule_assign';
              $notificationobj->type='school_notification';
              $notificationobj->from_user_id=$user_data_by->id;
              $notificationobj->schedule_id=$task->id;
              $notificationobj->save();
        
            
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
          $tasks = Schedule::select((DB::raw("(SELECT CASE
            WHEN  FIND_IN_SET(".$request->user_id." ,schedules.accept_reject_schedule ) THEN 1

            WHEN FIND_IN_SET(".$request->user_id.", schedules.rejected_user) THEN 2
            ELSE 0
            END
            )
            AS is_accept,schedules.*")))->with('User')->whereRaw('( (FIND_IN_SET('.$request->user_id.', assigned_to ) AND  handover=1)  OR  created_by=' .$request->user_id.')  AND  ( FIND_IN_SET('.$request->user_id.' ,rejected_user) IS NULL )')->orderBy('id', 'DESC')->get();
          foreach($tasks as $singlke_task){
             $user= User::whereIn('id', explode(',',$singlke_task->assigned_to))->select('name','id')->get();
           $singlke_task->assigned_to=$user;
          }



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
            $tasks = ParentTask::select((DB::raw("( CASE WHEN EXISTS (
              SELECT *
              FROM parent_task_assigned
              WHERE parent_task_assigned.task_id = parent_tasks.id  AND parent_task_assigned.accept_reject = 3
              ) THEN TRUE
              ELSE FALSE END)
              AS is_complete,parent_tasks.*")))->with('User')->whereRaw('( id IN (SELECT task_id
              FROM parent_task_assigned
              WHERE parent_task_assigned.task_id = parent_tasks.id  AND parent_task_assigned.handover = 1 AND task_assigned_to=' .$request->user_id.')  OR  task_assigned_by=' .$request->user_id.')')->where('schedule_id', $request->schedule_id)->orderBy('id', 'DESC')->get();
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
//           $accept_reject_data= ParentTaskAssigned::with('User','ParentTask.User')->where(['task_id'=>$request->task_id ,'task_assigned_to'=>$request->parent_id, 'handover'=>'1'])->first();
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
       $tasks = ParentTask::select((DB::raw("( CASE WHEN EXISTS (
              SELECT *
              FROM parent_task_assigned
              WHERE parent_task_assigned.task_id = parent_tasks.id
               AND parent_task_assigned.accept_reject = 3
              ) THEN TRUE
              ELSE FALSE END)
              AS is_complete,parent_tasks.*")))->with('User:id,name')->where('id', $request->task_id)->get();
       foreach($tasks as $signle_user){
            $task_user=  ParentTaskAssigned::select('image','notes','task_assigned_to')->with('User:id,name')->where('task_id',$request->task_id)->first();
  $signle_user->assigned_to= $task_user;
       }
     

  

    

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
       
         return response()->json(array('error' => false, 'message' => 'this task is pending', 'data' => []), 200);
    }
  }

   //Schedule Detail
    public function GetScheduleDetail(Request $request){
        $validator = Validator::make($request->all(), [
                    'schedule_id' => 'required|exists:schedules,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
         
          $tasks = Schedule::select((DB::raw("(SELECT CASE
            WHEN  FIND_IN_SET(".$request->user_id." ,schedules.accept_reject_schedule ) THEN 1
            WHEN FIND_IN_SET(".$request->user_id.", schedules.rejected_user) THEN 2
            ELSE 0
            END
            )
            AS is_accept,schedules.*")))->with('User:id,name')->where('id', $request->schedule_id)->first();
           $user= User::whereIn('id', explode(',',$tasks->assigned_to))->select('name')->get();
           $tasks->assigned_to=$user;

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
              $task_first= ParentTask::where('id',$request->task_id)->first();

               $delete= ParentTask::where('id',$request->task_id)->delete();
               $delete_assigned =  ParentTaskAssigned::where('task_id',$request->task_id)->delete();   
                $this->pusher->trigger('remove-channel', 'remove_task', $task_first);               
             if ($delete) {
                 return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
             } else {
                 return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
             }
         }
     }


       //remove task
    public function RemoveSchedule(Request $request){

        $validator = Validator::make($request->all(), [
         'schedule_id' => 'required|exists:schedules,id',
          'user_id' => 'required|exists:users,id',
 
     ]);
         if ($validator->fails()) {
             return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
         } else {
         $schedule= Schedule::where('id',$request->schedule_id)->first();
               $delete= Schedule::where('id',$request->schedule_id)->where('created_by',$request->user_id)->delete();
                 $this->pusher->trigger('delete-schedule', 'delete_schedule', $schedule);                
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
//         $results= ParentChildrens::select(DB::raw('DISTINCT parent_id'))->with('ParentDetails')->whereRaw('children_id IN('.$childrens.')')->where('parent_id','!=',$request->parent_id)->get();
         $results= ParentChildrens::select(DB::raw('DISTINCT parent_id'))->with('ParentDetails')->whereRaw('children_id IN('.$childrens.')')->get();
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
                    // 'accept_reject' => 'required',
                     'parent_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {

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

         $task_schedule= ParentTask::find($request->task_id);
          

          ParentTaskAssigned::where(['task_id'=>$request->task_id ,'task_assigned_to'=>$request->parent_id])->update(['accept_reject'=>'3','image'=>$data,'notes'=>$request->notes]);
         $accept_reject_data= ParentTaskAssigned::with('User','ParentTask.User')->where(['task_id'=>$request->task_id ,'task_assigned_to'=>$request->parent_id])->first();
         if(!empty($accept_reject_data)){

          $obj=new ParentController();
          $obj->task_id=$request->task_id;
          $obj->is_complete='1';
          $obj->schedule_id=$task_schedule->schedule_id;
          $this->pusher->trigger('task-channel', 'complete_task', $obj);
       
           $message=$accept_reject_data->User->name.' has completed the task ' .$accept_reject_data->ParentTask->task_name;
           if (!empty($accept_reject_data->ParentTask->User->device_token)) { 
              SendAllNotification($accept_reject_data->ParentTask->User->device_token, $message, 'school_notification');
            }
            $notificationobj=new Notification;
            $notificationobj->user_id=$accept_reject_data->ParentTask->User->id;
            $notificationobj->notification_message=$message;
            $notificationobj->notification_type='complete_task';
            $notificationobj->type='school_notification';
            $notificationobj->from_user_id=$accept_reject_data->User->id;
             $notificationobj->task_id=$accept_reject_data->task_id;
                $notificationobj->schedule_id=$task_schedule->schedule_id;
            $notificationobj->save();
          }
          else{
            $accept_reject_data=new ParentController();
          }



            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $accept_reject_data), 200);
        }
    }
 public function SendAcceptNotification(Request $request){
        $validator = Validator::make($request->all(), [
                    'schedule_id' => 'required|exists:schedules,id'
                    
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
      
        $scheduke=Schedule::find($request->schedule_id);
        $scheduke->handover= '1';
        $scheduke->rejected_user= null;
        $scheduke->save();


        $tasks=  ParentTask::with('AssignedUser')->where('schedule_id',$request->schedule_id)->first();
        ParentTaskAssigned::where('task_id',$tasks->id)->update(['handover' => '1']);
        $user= User::where('id', $tasks->AssignedUser->task_assigned_to)->select('name','id','device_token')->get();
        if(!empty($user[0]->device_token)){

         SendAllNotification($user[0]->device_token, 'A Schedule has been handed over to you.', 'school_notification');
       }

            $notificationobj=new Notification;
            $notificationobj->user_id=$tasks->AssignedUser->id;
            $notificationobj->notification_message='A Schedule has been handed over to you';
            $notificationobj->notification_type='task_assign';
            $notificationobj->type='school_notification';
            $notificationobj->from_user_id=$tasks->task_assigned_by;
            $notificationobj->task_id=$tasks->id;
            $notificationobj->schedule_id=$tasks->schedule_id;
            $notificationobj->save();
           
              $scheduke->User;
             $scheduke->assigned_to=$user;
                $scheduke->is_accept='0';
              $this->pusher->trigger('schedule-channel', 'schedule_user', $scheduke);
        /*foreach($tasks  as $single_task){
        ParentTaskAssigned::where('task_id',$single_task->id)->update(['handover' => '1']);
        $task_assigned= ParentTaskAssigned::with('AssignedTo')->where('task_id',$single_task->id)->get();

        if(!empty($task_assigned)){
          foreach($task_assigned as $single_task_to){
             if (!empty($single_task_to->AssignedTo->device_token)) { 
              SendAllNotification($single_task_to->AssignedTo->device_token, 'A task has been assigned to you.', 'school_notification');
            }
          $notificationobj=new Notification;
            $notificationobj->user_id=$single_task_to->task_assigned_to;
            $notificationobj->notification_message='A task has been assigned to you.';
            $notificationobj->notification_type='task_assign';
            $notificationobj->type='school_notification';
            $notificationobj->from_user_id=$single_task->task_assigned_by;
             $notificationobj->task_id=$single_task->id;
              $notificationobj->schedule_id=$single_task->schedule_id;
            $notificationobj->save();
          }
        }
        }*/
        return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
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
            if(!empty($schedule->accept_reject_schedule)){
              $schedule->accept_reject_schedule=$schedule->accept_reject_schedule.','.$request->parent_id;

            }else{
              $schedule->accept_reject_schedule=$request->parent_id;
            }

            $message=$user->name.' has accepted the schedule ' .$schedule->schedule_name; 
          }
          if($request->accept_reject==2){
            if(!empty($schedule->rejected_user)){
              $schedule->rejected_user=$schedule->rejected_user.','.$request->parent_id;

            }else{
              $schedule->rejected_user=$request->parent_id;
            }

            $schedule->handover='0';
            


            $message=$user->name.' has rejected the schedule ' .$schedule->schedule_name;
          }

                       $schedule->save();
                           if($request->accept_reject==2){
                    $this->pusher->trigger('decline-channel', 'decline_schedule', $schedule);
                  }
                 if (!empty($schedule->User->device_token)) { 
              SendAllNotification($schedule->User->device_token,$message, 'school_notification');
            }


            $notificationobj=new Notification;
            $notificationobj->user_id=$schedule->created_by;
            $notificationobj->notification_message=$message;
            $notificationobj->notification_type='accept_reject';
            $notificationobj->type='school_notification';
            $notificationobj->from_user_id=$user->id;
             $notificationobj->schedule_id=$schedule->id;
            $notificationobj->save();
          
          

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $schedule), 200);
          }
        }
    }

}
