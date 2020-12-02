<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\Events\GroupEvent;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\ParentChildrens;

use Pusher\Pusher;
use App\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class GroupController extends Controller {

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

    // Group List
    public function GroupList(Request $request) {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                        'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
              $user=User::find($request->user_id);

             

              $teachers = ParentChildrens::Join('users', 'users.id', '=', 'parent_childrens.children_id')
               ->select(DB::raw('group_concat(DISTINCT(school_id)) as schools'))
              ->where('parent_id', $user->id)->groupBy('parent_id')->first();

               $classes = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')
               ->select(DB::raw('group_concat(DISTINCT(class_id)) as classes'))
              ->where('parent_id', $user->id)->groupBy('parent_id')->first();
              if(!empty($teachers->schools)){
               $sql= Group::whereRaw("type='parent_community' OR type='school' OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))   AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message");
               if(!empty($classes->classes)){
                 $sql= Group::whereRaw("type='parent_community' OR type='school' OR ( type='school_admin' AND school_id IN('".$teachers->schools."'))  OR ( type='class_group' AND class_id IN('".$classes->classes."'))   AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message");;
               }

             }else{
              $sql= Group::whereRaw("type='parent_community' OR type='school') AND status=1")->selectRaw(" groups.* ,(SELECT message FROM group_messages WHERE group_id=groups.id ORDER by id DESC limit 1) as last_message");;
            }
               $groups=$sql->get();
                 
               foreach($groups as $single_group){
                if($single_group->type=='parent_community'){
                 $count= User::where('role_id',3)->where('join_community',1)->where('status',1)->count();
                 $single_group->member_count=$count;
               }
               if($single_group->type=='school'){
                 $count= User::where('city',$user->city)->where('join_community',1)->where('status',1)->count();
                 $single_group->member_count=$count;
               }

               if($single_group->type=='school_admin'){
                 $count=User::where('role_id',3)->where('school_id',$user->school_id)->where('status',1)->count();
                 $single_group->member_count=$count;
               }

               if($single_group->type=='class_group'){
                $count = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')->Join('users', 'users.id', '=', 'parent_childrens.parent_id')
                ->select(DB::raw('Distinct count(parent_id))'))
                ->where('class_id', $single_group->class_id)->count();
                $single_group->member_count=$count;
              }

            }
                 return response()->json(array('error' => false, 'data' => $groups), 200);
               
          }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


     public function SendGroupMessage(Request $request) {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                       'message' => 'required',
                         'user_id' => 'required|exists:users,id',
                        'group_id' => 'required|exists:groups,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
              $user=User::find($request->user_id);
              $random_number=rand();
              if($request->group_id=='1'){
                $users= User::where('role_id',3)->where('join_community',1)->where('status',1)->where('id','!=',$user->id)->get();
               if($request->hasfile('images'))
               {
                foreach($request->file('images') as $key=>$single)
                {
                   $random_number=rand();

                  $name=time().$key.'.'.$single->getClientOriginalExtension();    
                  $single->move(public_path().'/assignment_doc/', $name);      
                  $filename= URL::to('/').'/assignment_doc/'.$name; 
                  if($key>0){
                    $request->message='';
                  }
                
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->id,$request,$random_number,$filename); 
                }
              }
            }
            else{
               foreach($users as $single){ 
                $this->Sendsinglemessage($single->id,$request,$random_number); 
              }
            } 
          }
                elseif($request->group_id=='2'){
              $users= User::where('city',$user->city)->where('join_community',1)->where('status',1)->where('id','!=',$user->id)->get();

              if($request->hasfile('images'))
               {
                foreach($request->file('images') as $key=>$single)
                {
                     $random_number=rand();
                  $name=time().$key.'.'.$single->getClientOriginalExtension();    
                  $single->move(public_path().'/assignment_doc/', $name);      
                  $filename= URL::to('/').'/assignment_doc/'.$name; 
                  if($key>0){
                    $request->message='';
                  }
                
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->id,$request,$random_number,$filename); 
                }
              }
            }
            else{
              foreach($users as $single){
                $this->Sendsinglemessage($single->id,$request,$random_number);
                                              }       
            }

            }
           else if(Group::where('group_id',$request->group_id)->where('type','school_admin')->exists()){
             $users= User::where('role_id',3)->where('school_id',$user->school_id)->where('id','!=',$user->id)->where('status',1)->get();
             if($request->hasfile('images'))
               {
                foreach($request->file('images') as $key=>$single)
                {

                     $random_number=rand();
                  $name=time().$key.'.'.$single->getClientOriginalExtension();    
                  $single->move(public_path().'/assignment_doc/', $name);      
                  $filename= URL::to('/').'/assignment_doc/'.$name; 
                  if($key>0){
                    $request->message='';
                  }
                
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->id,$request,$random_number,$filename); 
                }
              }
            }else{
              foreach($users as $single){
               $this->Sendsinglemessage($single->id,$request,$random_number);
              
             }
           }
           }

           else if(Group::where('group_id',$request->group_id)->where('type','class_group')->exists()){
             $class_id= Group::where('group_id',$request->group_id)->where('type','class_group')->first();           
             $users = ParentChildrens::Join('user_class_code', 'user_class_code.user_id', '=', 'parent_childrens.children_id')->Join('users', 'users.id', '=', 'parent_childrens .parent_id')
             ->select(DB::raw('DIstinct users.id'))->where('class_id', $class_id->class_id)->get();
             if($request->hasfile('images'))
             {
              foreach($request->file('images') as $key=>$single)
              {
                $name=time().$key.'.'.$single->getClientOriginalExtension();    
                $single->move(public_path().'/assignment_doc/', $name);      
                $filename= URL::to('/').'/assignment_doc/'.$name; 
                if($key>0){
                  $request->message='';
                }
                
                foreach($users as $single){ 
                  $this->Sendsinglemessage($single->id,$request,$random_number,$filename); 
                }
              }
            }   else{
             foreach($users as $single){
               $this->Sendsinglemessage($single->id,$request,$random_number);              
             }
           }
         }

  $group_data= GroupMessage::with('User')->where('group_id',$request->group_id)->where('from_user_id',$request->user_id)->groupBy('group_number')->orderBy('id', 'DESC')->first();

  $this->pusher->trigger('group-channel', 'group_user', $group_data);
                
         return response()->json(array('error' => false, 'data' => $group_data), 200);
               
          }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }


    public function Sendsinglemessage($to_user_id,$input,$random_number,$file_name=''){
      
          $groups=new GroupMessage;
          $groups->to_user_id=$to_user_id;
          $groups->from_user_id=$input->user_id;
          $groups->group_id=$input->group_id;
          $groups->message=$input->message;
          $groups->group_number=$random_number;
          $groups->is_read=0;
          $groups->file=$file_name;
          $groups->save();
      //    $messsage='';
      

    }
     // Group Messages List
    public function GroupMessages(Request $request) {
      try {

        $input = $request->all();
        $validator = Validator::make($input, [
          'group_id' => 'required',
        ]);

        if ($validator->fails()) {
          throw new Exception($validator->errors()->first());
        } else {
          $group_data= GroupMessage::with('User')->where('group_id',$request->group_id)->groupBy('group_number')->orderBy('id', 'ASC')->get();           
         return response()->json(array('error' => false, 'data' => $group_data), 200);

       }
     } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

  

}
