<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use App\Models\ClassCode;
use App\Models\Subject;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;
class TeacherController extends Controller {
    public function __construct()
    { 
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
        'password' => 'required',
        'type_of_schooling' => 'required',
        'country' => 'required',
        'state_id' => 'required|exists:states,id',
        'city_id' => 'required|exists:cities,id',
       'school_id' => 'required_if:type_of_schooling, =,school'
      ]);
       
       if ($validator->fails()) {
         throw new Exception($validator->errors()->first());
       }  
       else{ 
         $names=array();
        $student_obj=new User;
        $addUser = $student_obj->store($request);
       // DB::commit();
        $token = JWTAuth::fromUser($addUser);
         $addUser->token=$token;
       
         if($request->type_of_schooling=='home'){
          if(Subject::where('id', '=',$request->subject_id)->exists()) {
          $this->AddteacherSubject($request->subject_id, $addUser->id);
          
        } else{
            throw new Exception('Subject id is not valid');
        }

        
          
          if($request->hasfile('documents'))
          {
        
             foreach($request->file('documents') as $key=>$file)
            {
             $name = time().$key.'.'.$file->getClientOriginalExtension();
             $file->move(public_path().'/files/', $name);  
             $names[]=$name;
             DB::table('teacher_documents')->insert(
              ['user_id' =>$addUser->id, 'document_name' => $name, 'document_url' => URL::to('/').'/files/'.$name]);
           }

           
         }
       }

        DB::commit();
        //clascodes
        if(!empty( $addUser )){
          User::where('id',$addUser->id)->update(['device_token' => $request->device_token]);
         $addUser->documents= $names;
         if(!empty($request->class_code)) {
          $class_code=  ClassCode::where('class_code',$request->class_code)->first();
          if(!empty($class_code)){
            DB::table('user_class_code')->insert(
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

 public function AddteacherSubject($subject_id,$user_id){

  $teacher_subject=DB::table('teacher_subjects')->insert(
              ['user_id' =>$user_id, 'subject_id' => $subject_id]);

return $teacher_subject;

 }
    public function updateProfile(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'username' => 'required',
                    'user_id' => 'required|exists:users,id'
        ]);
       
       if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
        }  
        else{ 
       $count_user=User::where('id','!=', $request->user_id)->where('username','=',$request->username)->count();
         
          if($count_user==0){
            $updateData = User::where('id', $request->user_id)->update([
                'name' => $request->name,
                'username' => $request->username,
                'bio' => $request->bio,
                'insta_key' => $request->insta_key
            ]);
            // upload image file
            if ($request->hasfile('image')) {
              $video = $request->file('image');
              $name = time() . '.' . trim($video->getClientOriginalExtension());
              $destinationPath = public_path('/uploads');
              $video->move($destinationPath, $name);
              $videourl = url('/') . '/uploads/' . $name;
              $updateData = User::where('id', $request->user_id)->update([
                'image' => $videourl
              ]);
            }
            $update= User::find( $request->user_id);
            return response()->json(array('error' => false, 'message' => 'profile update successfully', 'data' => $update), 200);
          }
        else{
           return response()->json(array('error' => true, 'message' => 'Username already exist', 'data' => []), 200);
        }
      }
       
    }

    public function Getuserprofile(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required'

        ]);    
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'errors' => $validator->errors()), 200);
        }  
        else{ 
            $data = User::fetchUser($request->user_id);
            $get_followers=User::FollowedUsers($request->user_id);
            $following_users=User::FollowingUser($request->user_id);
            $data->followers=$get_followers;
            $data->following=$following_users;
            if(isset($request->viewing_user_id)){
                $folow_or_not=FollowUnfollow::where('following_user_id',$request->viewing_user_id)->where('followed_user_id',$request->user_id)->count();
                if($folow_or_not >0){
                    $data->is_follow=1;
                }else{
                  $data->is_follow=0;
                }
            }
           // DB::enableQueryLog(); 

            $streams=  Queue::select(DB::raw('GROUP_CONCAT(id) as streams'))->where('user_id' , $request->user_id)->first();
            if(!empty($streams->streams)){
              $results = DB::select( DB::raw("select count(*) as total_likes from `like_unlike` where `stream_id` in (".$streams->streams.") and `like` = 1") );

              if(isset($results[0])){
                $data->total_likes=number_format_short($results[0]->total_likes);
              }
              else{
               $data->total_likes=0;
             }

           }
            else{
               $data->total_likes=0;
             }

            return response()->json(array('error' => false, 'message' => 'profile fetched successfully', 'data' => $data), 200);

        }

    }
}
