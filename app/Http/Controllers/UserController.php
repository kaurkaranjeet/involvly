<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Models\UnapproveStudent;
use App\Models\Group;
use App\Models\School;
use Tymon\JWTAuth\Exceptions\JWTException;
use Pusher\Pusher;
use App\Models\ParentChildrens;

class UserController extends Controller {

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

    public function adminLogin(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid Credentials'], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = User::where('email', '=', $request->input('email'))->where('role_id', 1)->first();
        if(empty($users)){
            return response()->json(['message' => 'Invalid Credentials'], 200); 
        }
        return response()->json(compact('accessToken', 'user'));
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid Credentials'], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }

        $users = User::where('email', '=', $request->input('email'))->where('role_id', 5)->first();
        if(empty($users)){
            return response()->json(['message' => 'Invalid Credentials'], 200); 
        }else{
           $user = User::where('email', '=', $request->input('email'))->where('role_id', 5)->where('status', 1)->first();
           if(empty($user)){
              return response()->json(['message' => 'Your account approval is pending'], 200);            }   
        }
        return response()->json(compact('accessToken', 'user'));
    }

    // Register API
    public function register(Request $request) {


        $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:6',
                    'city' => 'required',
                    'state_id' => 'required',
                    'school_id' => 'required',
                    'country' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 200);
        }


        $user = new User;
        $name = explode(',', $request->name);
        $user->first_name = $name[0];
        if (isset($name[1])) {
            $user->last_name = $name[1];
        } else {
            $user->last_name = '';
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->city = $request->city;
        $user->state_id = $request->state_id;
        $user->school_id = $request->school_id;
        $user->country = $request->country;
        $user->position = $request->position;
        $user->status = 0;
        $user->password = Hash::make($request->password);
        $user->role_id = 5;
        $user->save();
        if ($request->hasfile('documents')) {
            foreach ($request->file('documents') as $key => $file) {
                $name = time() . $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/', $name);
                DB::table('teacher_documents')->insert(
                        ['user_id' => $user->id, 'document_name' => $name, 'document_url' => URL::to('/') . '/images/' . $name]);
            }
        }

        $group_count= Group::where('school_id',$request->school_id)->where('type','school_admin')->count();
        if($group_count==0){
         $school_name=School::where('id',$request->school_id)->select('school_name')->first();
         $group_obj= new  Group;
         $group_obj->group_name= $school_name->school_name;
         $group_obj->school_id=$request->school_id;
         $group_obj->user_id=$user->id;
         $group_obj->type='school_admin';
         $group_obj->save();
     }
        $token = JWTAuth::fromUser($user);
        $error = false;

        return response()->json(compact('user', 'token', 'error'), 200);
    }

    public function gettotalStatistic($id) {
        $students = User::getstudents($id)->count();
        $data = array();
        $series_sql = User::getstudents($id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($series_sql)) {

            foreach ($series_sql as $rr) {

                $rv= ['x'=>$rr->date,'y'=>$rr->views];
                $data[] = $rv;
            }
        }
        $Studentseries[]['data'] = $data;

        $teachers = User::getteachers($id)->count();
        $tseries_sql = User::getteachers($id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($tseries_sql)) {


            foreach ($tseries_sql as $rr) {
                 $rva= ['x'=>$rr->date,'y'=>$rr->views];
                $tdata[] = $rva;
            }
        }
        $Teacherseries[]['data'] = $tdata;
        return response()->json(compact('students', 'teachers', 'Studentseries', 'Teacherseries'), 200);
    }


     public function gettotalRecords(Request $request) {
        $students = User::where('role_id',5)->where('status',1)->count();
        $data = array();
        $tdata=array();
        $Studentseries=array();
        $series_sql =  User::where('role_id',5)->where('status',1)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($series_sql)) {

            foreach ($series_sql as $rr) {
                $data[] = $rr->views;
            }
        }
        $Studentseries[]['data'] = $data;

        $teachers = User::where('role_id',4)->where('type_of_schooling','home')->where('status',1)->count();
        $tseries_sql =User::where('role_id',4)->where('status',1)->where('type_of_schooling','home')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($tseries_sql)) {

            foreach ($tseries_sql as $rr) {
                $tdata[] = $rr->views;
            }
        }
        $Teacherseries[]['data'] = $tdata;
        return response()->json(compact('students', 'teachers', 'Studentseries', 'Teacherseries'), 200);
    }

    public function getAuthenticatedUser() {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'), 200);
    }

    public function manageUsers(Request $request, $id) {
        DB::enableQueryLog();


        if ($request->type == 'teacher') {
        

            $users = User::where('role_id', 4)->where('status', 1)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.class_name) AS class_codes from assigned_teachers inner join class_code as u ON assigned_teachers.class_id=u.id WHERE  assigned_teachers.teacher_id= users.id) as class_codes ,users.*'))->orderBy('id', 'DESC')->get();
        } else if ($request->type == 'student') {
            $users = User::where('role_id', 2)->where('status', 1)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.class_name) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'))->orderBy('id', 'DESC')->get();
        } else {

            $users = User::where('role_id', 3)->where('status', 1)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.name) AS childrens from parent_childrens inner join users as u ON parent_childrens.children_id=u.id where parent_id=users.id) as associated_child ,users.*'))->orderBy('id', 'DESC')->get();
        }
        //  print_r(DB::getQueryLog());die;
        foreach($users as $user){
            $user->subjects='Hindi';
        }

        if (isset($users) && count($users) > 0) {
            return response()->json(compact('users'), 200);
        } else {
            return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
        }
    }

    public function Getrecord(Request $request){
 //DB::enableQueryLog();
        $users = User::join('assigned_teachers', 'users.id', '=', 'assigned_teachers.teacher_id')->where('role_id', 4)->where('status', 1)->where('users.school_id', $request->school_id)->where('class_id',$request->class_id)->where('subject_id',$request->subject_id)->select(DB::raw('(select GROUP_CONCAT(u.class_name) AS class_codes from assigned_teachers inner join class_code as u ON assigned_teachers.class_id=u.id WHERE  assigned_teachers.teacher_id= users.id) as class_codes ,users.*'))->orderBy('id', 'DESC')->get();

        //print_r(DB::getQueryLog());die;
     if (isset($users) && count($users) > 0) {
        return response()->json(compact('users'), 200);
    } else {
        return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
    }

}

    //fetch all admin users
    public function manageAdminUsers(Request $request) {
        DB::enableQueryLog();

        if ($request->type == 'teacher') {
            $users = User::where('role_id', 4)->where('status', 1)->select(DB::raw('(select GROUP_CONCAT(u.class_code) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'))->get();
        } else if ($request->type == 'student') {
            $users = User::where('role_id', 2)->where('status', 1)->select(DB::raw('(select GROUP_CONCAT(u.class_code) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'))->get();
        }
        else if ($request->type == 'school_admins') {
            $users = User::with('SchoolDetail')->where('role_id', 5)->where('status', 1)->get();
            foreach($users as $user){
                $user->school_name= $user->SchoolDetail->school_name;
            }
        }


         else {
            $users = User::where('role_id', 3)->where('status', 1)->select(DB::raw('(select GROUP_CONCAT(u.name) AS childrens from parent_childrens inner join users as u ON parent_childrens.children_id=u.id where parent_id=users.id) as associated_child ,users.*'))->get();
        }
        //  print_r(DB::getQueryLog());die;

        if (isset($users) && count($users) > 0) {
            return response()->json(compact('users'), 200);
        } else {
            return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
        }
    }

    public function fetchUser($id) {
    $data = User::with('role')->with('StateDetail')->with('CityDetail')->with('SchoolDetail')->with('documents')->with('Timetables')->where('id', $id)->first();
    // print_r($data->StateDetail);die;
        $UnapproveStudent=[];
        $relationshipStudent=[];
        $relationshipParent=[];
       $data->state_name= $data->StateDetail->state_name;
        $data->city= $data->CityDetail->city;
        if(count($data->documents)){
             $data->is_document= 1;
        }
        if($data->role_id==3){
          $UnapproveStudent=   UnapproveStudent::where('parent_id',$id)->get();
          $relationshipParent = ParentChildrens::with('ChildDetails')->where('parent_id',$id)->get();
          $data['relationshipParent'] = $relationshipParent;
        }
        if($data->role_id==2){
          $relationshipStudent = ParentChildrens::with('ParentDetails')->where('children_id',$id)->get();
          $data['relationshipStudent'] = $relationshipStudent;
        }
        if (isset($data)) {
         $data['unapproveStudent']=$UnapproveStudent;
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function UpdateProfile(Request $request) {
        $name = $request->first_name . ' ' . $request->last_name;
        User::where('id', $request->id)->update(['first_name' => $request->first_name,'state_id' => $request->state_id,'city' => $request->city, 'last_name' => $request->last_name, 'name' => $name, 'status' => $request->status]);
        $data = User::find($request->id);
        if (!empty($data)) {
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function RemoveUser($id) {
        $userobj=User::where('id', $request->user_id)->first();
        $data = User::where('id', $id)->delete();

        $this->pusher->trigger('remove-channel', 'delete_user', $userobj);
        return response()->json(compact('data'), 200);
    }

    public function getRequest($school_id) {
        $data = User::where('role_id', 4)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
        $count = User::where('role_id', 4)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
    
        return response()->json(compact('data','count'), 200);
    }

      public function getteacherRequest() {
        $data = User::where('role_id', 4)->where('status', 0)->where('type_of_schooling','home')->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
        $count = User::where('role_id', 4)->where('status', 0)->where('type_of_schooling','home')->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
            return response()->json(compact('data','count'), 200);
    }

      public function WebSchoolAdmins() {
        $data = User::where('role_id', 5)->where('status', 0)->with('SchoolDetail:id,school_name')->select('id', 'name', 'email', 'school_id', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
              $count = User::where('role_id', 5)->where('status', 0)->with('SchoolDetail:id,school_name')->select('id', 'name', 'email', 'school_id', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
        return response()->json(compact('data','count'), 200);
    }
    public function getStudentRequest($school_id) {
        $data = User::where('role_id', 2)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
          $count = User::where('role_id', 2)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
        return response()->json(compact('data','count'), 200);
    }

    public function getParentRequest($school_id) {
        $data = User::where('role_id', 3)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
         $count = User::where('role_id', 3)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
        return response()->json(compact('data','count'), 200);
    }


    public function RefreshToken(Request $request){

          /* $accessToken = JWTAuth::refresh();
                $user = JWTAuth::setToken($accessToken)->toUser();*/

         //  return response()->json(compact('accessToken'), 200);
    }

}

?>