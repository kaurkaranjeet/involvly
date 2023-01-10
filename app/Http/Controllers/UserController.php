<?php

namespace App\Http\Controllers;

// use App\Jobs\ProcessRequest;

use App\Jobs\ProcessRequest;
use App\Jobs\SendNotification;
use App\Models\ClassCode;
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
use App\Models\ReportUser;
use Tymon\JWTAuth\Exceptions\JWTException;
use Pusher\Pusher;
use App\Notification;
use App\Models\TeachingProgram;
use App\Models\Comment;

use App\Models\CommentReply;
use App\Models\DiscussionComment;
use App\Models\DiscussionCommentReply;

use App\Models\ParentChildrens;
use App\Models\Subject;
use App\Models\TeachingProgramReq;
use App\UserSubject;
use Exception;
use Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{

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

    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid Credentials'], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = User::where('email', '=', $request->input('email'))->where('role_id', 1)->first();
        if (empty($user)) {
            return response()->json(['message' => 'Invalid Credentials'], 200);
        }
        return response()->json(compact('accessToken', 'user'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid Credentials'], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'could_not_create_token'], 500);
        }

        $users = User::where('email', '=', $request->input('email'))->where('role_id', 5)->first();
        if (empty($users)) {
            return response()->json(['message' => 'Invalid Credentials'], 200);
        } else {
            $user = User::where('email', '=', $request->input('email'))->where('role_id', 5)->where('status', 1)->first();
            if (empty($user)) {
                return response()->json(['message' => 'Your account approval is pending'], 200);
            }
        }
        return response()->json(compact('accessToken', 'user'));
    }

    // Register API
    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
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
        $name = $request->first_name . ' ' . $request->last_name;
        $user->name = $name;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name; 
        $user->email = $request->email;
        $user->password = $request->password;
        $user->city = $request->city;
        $user->state_id = $request->state_id;
        $user->school_id = $request->school_id;
        $user->country = $request->country;
        $user->position = $request->position;
        $user->status = 1;
        $user->password = Hash::make($request->password);
        $user->role_id = 5;
        $user->save();
        
        //school approved code
        School::where('id', $request->school_id)->update(['approved' => '1']);
        if ($request->hasfile('documents')) {
            foreach ($request->file('documents') as $key => $file) {
                $name = time() . $key . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/images/', $name);
                DB::table('teacher_documents')->insert(
                    ['user_id' => $user->id, 'document_name' => $name, 'document_url' => URL::to('/') . '/images/' . $name]
                );
            }
        }

        $group_count = Group::where('school_id', $request->school_id)->where('type', 'school_admin')->count();
        if ($group_count == 0) {
            $school_name = School::where('id', $request->school_id)->select('school_name')->first();
            $group_obj = new  Group;
            $group_obj->group_name = $school_name->school_name;
            $group_obj->school_id = $request->school_id;
            $group_obj->user_id = $user->id;
            $group_obj->type = 'school_admin';
            $group_obj->save();
        }
        $token = JWTAuth::fromUser($user);
        $error = false;

        return response()->json(compact('user', 'token', 'error'), 200);
    }

    public function gettotalStatistic($id)
    {
        $students = User::getstudents($id)->count();
        $data = array();
        $series_sql = User::getstudents($id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($series_sql)) {

            foreach ($series_sql as $rr) {

                $rv = ['x' => $rr->date, 'y' => $rr->views];
                $data[] = $rv;
            }
        }
        $Studentseries[]['data'] = $data;

        $teachers = User::getteachers($id)->count();
        $tseries_sql = User::getteachers($id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($tseries_sql)) {


            foreach ($tseries_sql as $rr) {
                $rva = ['x' => $rr->date, 'y' => $rr->views];
                $tdata[] = $rva;
            }
        }
        $Teacherseries[]['data'] = $tdata;
        return response()->json(compact('students', 'teachers', 'Studentseries', 'Teacherseries'), 200);
    }


    public function gettotalRecords(Request $request)
    {
        $students = User::where('role_id', 5)->where('status', 1)->count();
        $data = array();
        $tdata = array();
        $Studentseries = array();
        $series_sql =  User::where('role_id', 5)->where('status', 1)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($series_sql)) {

            foreach ($series_sql as $rr) {
                $rva = ['x' => $rr->date, 'y' => $rr->views];
                $data[] = $rva;
                // $data[] = $rr->views;
            }
        }
        $Studentseries[]['data'] = $data;

        $teachers = User::where('role_id', 4)->where('type_of_schooling', 'home')->where('status', 1)->count();
        $tseries_sql = User::where('role_id', 4)->where('status', 1)->where('type_of_schooling', 'home')->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
        if (!empty($tseries_sql)) {

            foreach ($tseries_sql as $rr) {
                $rva = ['x' => $rr->date, 'y' => $rr->views];
                $tdata[] = $rva;
                // $tdata[] = $rr->views;
            }
        }
        $Teacherseries[]['data'] = $tdata;
        return response()->json(compact('students', 'teachers', 'Studentseries', 'Teacherseries'), 200);
    }

    public function getAuthenticatedUser()
    {
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
    public function manageUsers(Request $request, $id)
    {
        DB::enableQueryLog();

       
        if ($request->type == 'student') {
            $users = User::where('role_id', 2)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.class_name) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'));
        } elseif ($request->type == 'searchdata') {
            $users = User::where('role_id', 4)->where('school_id', $id)
            ->leftJoin('teaching_program', 'teaching_program.user_id', '=', 'users.id') 
            ->select(DB::raw('(select GROUP_CONCAT(subjects.subject_name) AS subject_pr from user_subjects inner join subjects ON user_subjects.subject_id=subjects.id WHERE user_subjects.user_id= users.id) as subject_pr ,(select GROUP_CONCAT(class_code.class_name) AS class_name from user_class inner join class_code ON user_class.class_id=class_code.id WHERE user_class.user_id= users.id) as class_name ,availability,hourly_rate, location,preferences,users.*,teachin_program_requests.request_status as request_status'));
        } elseif ($request->type == 'teacher') {
            $users = User::where('role_id', 4)->where('school_id', $id)
            ->whereNotExists(function($query)
            {
                $query->select(DB::raw(1))
                      ->from('teaching_program')
                      ->whereRaw('teaching_program.user_id = users.id');
            })
            ->select(DB::raw('(select GROUP_CONCAT(DISTINCT u.class_name) AS class_codes from assigned_teachers inner join class_code as u ON assigned_teachers.class_id=u.id WHERE  assigned_teachers.teacher_id= users.id) as class_codes ,users.*'));
        } elseif ($request->type == 'contractual-teacher') {
            $users = User::where('role_id', 4)->where('school_id', $id)
            ->rightJoin('teaching_program', 'teaching_program.user_id', '=', 'users.id')
            ->leftJoin('teachin_program_requests', 'teachin_program_requests.to_user', '=', 'users.id')
            ->select(DB::raw('(select GROUP_CONCAT(subjects.subject_name) AS subject_pr from user_subjects inner join subjects ON user_subjects.subject_id=subjects.id WHERE user_subjects.user_id= users.id) as subject_pr ,(select GROUP_CONCAT(class_code.class_name) AS class_name from user_class inner join class_code ON user_class.class_id=class_code.id WHERE user_class.user_id= users.id) as class_name ,availability,hourly_rate, location,preferences,users.*,teachin_program_requests.request_status as request_status'));
        } else {
            $users = User::where('role_id', 3)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.name) AS childrens from parent_childrens inner join users as u ON parent_childrens.children_id=u.id where parent_id=users.id) as associated_child ,users.*'));
        }
        $users = $users->orderBy('id', 'DESC')->get();

        //  print_r(DB::getQueryLog());die;
        foreach ($users as $user) {
            $user->subjects = 'Hindi';
        }

        if (isset($users) && count($users) > 0) {
            return response()->json(compact('users'), 200);
        } else {
            return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
        }
    }

    public function Getrecord(Request $request)
    {
        //DB::enableQueryLog();
        $query = User::join('assigned_teachers', 'users.id', '=', 'assigned_teachers.teacher_id')->where('role_id', 4)->where('status', 1)->where('users.school_id', $request->school_id);
        if ($request->class_id) {
            $query->where('assigned_teachers.class_id', $request->class_id);
        }
        if ($request->subject_id) {
            $query->where('assigned_teachers.subject_id', $request->subject_id);
        }
        $users = $query->select(DB::raw('(select GROUP_CONCAT(u.class_name) AS class_codes from assigned_teachers inner join class_code as u ON assigned_teachers.class_id=u.id WHERE  assigned_teachers.teacher_id= users.id) as class_codes ,users.*'))->orderBy('id', 'DESC')->get();

        //print_r(DB::getQueryLog());die;
        if (isset($users) && count($users) > 0) {
            return response()->json(compact('users'), 200);
        } else {
            return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
        }
    }

    //fetch all admin users
    public function manageAdminUsers(Request $request)
    { 
        DB::enableQueryLog();
        if ($request->type == 'teacher') {
            $users = User::with('SchoolDetail')->where('role_id', 4)->where('status', 1)
            ->whereNotExists(function($query)
            {
                $query->select(DB::raw(1))
                      ->from('teaching_program')
                      ->whereRaw('teaching_program.user_id = users.id');
            })
            ->select(DB::raw('(select GROUP_CONCAT(u.class_code) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'))->orderBy('id', 'DESC')->get();
            foreach ($users as $user) {
                if ($user->type_of_schooling == 'home') {
                    $user->type_of_schooling = 'Not Assigned';
                    $user->school_name = '-';
                } else {
                    $user->type_of_schooling = 'School';
                    if (!empty($user->SchoolDetail)) {
                        $user->school_name = $user->SchoolDetail->school_name;
                    } else {
                        $user->school_name = '-';   
                    }
                }
            }
        } else if ($request->type == 'program-teacher') {
            $users = User::with('SchoolDetail')->where('role_id', 4)->where('status', 1)
            ->rightJoin('teaching_program', 'teaching_program.user_id', '=', 'users.id')
            ->select(DB::raw('(select GROUP_CONCAT(u.class_code) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,hourly_rate,availability,users.*'))->orderBy('id', 'DESC')->get();
            foreach ($users as $user) {
                if ($user->type_of_schooling == 'home') {
                    $user->type_of_schooling = 'Not Assigned';
                    $user->school_name = '-';
                } else {
                    $user->type_of_schooling = 'School';
                    if (!empty($user->SchoolDetail)) {
                        $user->school_name = $user->SchoolDetail->school_name;
                    } else {
                        $user->school_name = '-';
                    }
                }
            }
        } else if ($request->type == 'student') {
            $users = User::with('SchoolDetail')->where('role_id', 2)->where('status', 1)->select(DB::raw('(select GROUP_CONCAT(u.class_name) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'))->orderBy('id', 'DESC')->get();
            foreach ($users as $user) {
                if ($user->type_of_schooling == 'home') {
                    $user->type_of_schooling = 'Home Schooling';
                    $user->school_name = '-';
                } else {
                    $user->type_of_schooling = 'School';
                    if (!empty($user->SchoolDetail)) {
                        $user->school_name = $user->SchoolDetail->school_name;
                    } else {
                        $user->school_name = '-';
                    }
                }
            }
        } else if ($request->type == 'school_admins') {

            $users = User::with('SchoolDetail')->leftJoin('schools', 'schools.id', '=', 'users.school_id')->where('role_id', 5)->where('status', 1)

                ->select('users.*', 'school_name')
                ->orderBy('id', 'DESC')->get();
            // foreach ($users as $user) {
            //     $user->school_name = $user->SchoolDetail->school_name;
            // }
        } else {
            $users = User::with('SchoolDetail')->where('role_id', 3)->where('status', 1)->select(DB::raw('(select GROUP_CONCAT(u.name) AS childrens from parent_childrens inner join users as u ON parent_childrens.children_id=u.id where parent_id=users.id) as associated_child ,users.*'))->orderBy('id', 'DESC')->get();
            foreach ($users as $user) {
                if ($user->type_of_schooling == 'home') {
                    $user->school_name = '-';
                } else {
                    if (!empty($user->SchoolDetail)) {
                        $user->school_name = $user->SchoolDetail->school_name;
                    } else {
                        $user->school_name = '-';
                    }
                }
            }
        }
        //  print_r(DB::getQueryLog());die;

        if (isset($users) && count($users) > 0) {
            return response()->json(compact('users'), 200);
        } else {
            return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
        }
    }
    public function fetchUser($id)
    {
        $data = User::with('role')->with('StateDetail')->with('CityDetail')->with('SchoolDetail')->with('documents')->with('Timetables')->where('id', $id)->first();
        // print_r($data->StateDetail);die;
        $UnapproveStudent = [];
        $relationshipStudent = [];
        $relationshipParent = [];
        if (!empty($data->StateDetail)) {
            $data->state_name = $data->StateDetail->state_name;
        } else {
            $data->state_name = '';
        }
        //    if(!empty($data->CityDetail)){
        //         $data->city= $data->CityDetail->city;
        //     }else{
        //         $data->city= '';
        //     }   
        if (count($data->documents)) {
            $data->is_document = 1;
        }
        if ($data->role_id == 3) {
            $UnapproveStudent =   UnapproveStudent::where('parent_id', $id)->get();
            $relationshipParent = ParentChildrens::with('ChildDetails.SchoolDetail')->where('parent_id', $id)->get();
            $data['relationshipParent'] = $relationshipParent;
        }
        if ($data->role_id == 2) {
            $relationshipStudent = ParentChildrens::with('ParentDetails')->where('children_id', $id)->get();
            $data['relationshipStudent'] = $relationshipStudent;
        }
        if (isset($data)) {
            $data['unapproveStudent'] = $UnapproveStudent;
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function UpdateProfile(Request $request)
    {
        $name = $request->first_name . ' ' . $request->last_name;
        if ($request->role_id == '1') {
            User::where('id', $request->id)->update(['name' => $name, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'status' => 1]);
        } else {
            User::where('id', $request->id)->update(['state_id' => $request->state_id, 'city' => $request->city, 'name' => $name, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'status' => 1]);
        }
        $data = User::find($request->id);
        if (!empty($data)) {
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function UpdateSchoolProfile(Request $request)
    {
        $name = $request->first_name . ' ' . $request->last_name;
        User::where('id', $request->user_id)->update(['state_id' => $request->state, 'city' => $request->city, 'name' => $name, 'first_name' => $request->first_name, 'last_name' => $request->last_name, 'position' => $request->position, 'school_id' => $request->school]);
        $data = User::find($request->id);
        if (!empty($data)) {
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function RemoveUser($id)
    {
        $userobj = User::where('id', $id)->first();
        $data = User::where('id', $id)->delete();

        $this->pusher->trigger('remove-channel', 'delete_user', $userobj);
        //notification or comments
        Notification::where('user_id', $id)->delete();
        Comment::where('user_id', $id)->delete();
        CommentReply::where('user_id', $id)->delete();
        DiscussionComment::where('user_id', $id)->delete();
        DiscussionCommentReply::where('user_id', $id)->delete();
        Group::where('user_id', $id)->delete();
        return response()->json(compact('data'), 200);
    }
    // Place a request function
    public function PlaceUser(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required|integer|exists:users,id',
                'request_status' => 'required|integer|in:0,1',
                'from_user' => 'required|integer|exists:users,id',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                if (!empty($input)) {
                    $data['id'] = $request->id;
                    $data['from_user'] = $request->from_user;
                    $data['request_status'] = $request->request_status;
                    $users = TeachingProgramReq::requestStatus($data);

                    if (!empty($users)) {
                        return response()->json(['message' =>'Updated req!', 'data' => $users], 200);
                    }  
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }
    
    public function getRequest($school_id)
    {
        $data = User::where('role_id', 4)->where('school_id', $school_id)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
        $count = User::where('role_id', 4)->where('school_id', $school_id)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();

        return response()->json(compact('data', 'count'), 200);
    }

    public function getteacherRequest()
    {
        $data = User::where('role_id', 4)->where('status', 1)->where('type_of_schooling', 'home')->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
        $count = User::where('role_id', 4)->where('status', 1)->where('type_of_schooling', 'home')->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
        return response()->json(compact('data', 'count'), 200);
    }


    public function WebSchoolAdmins()
    {
        $data = User::where('role_id', 5)->where('status', 0)->with('SchoolDetail:id,school_name')->select('id', 'name', 'email', 'school_id', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
        $count = User::where('role_id', 5)->where('status', 0)->with('SchoolDetail:id,school_name')->select('id', 'name', 'email', 'school_id', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
        return response()->json(compact('data', 'count'), 200);
    }
    public function getStudentRequest($school_id)
    {
        $data = User::where('role_id', 2)->where('school_id', $school_id)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
        $count = User::where('role_id', 2)->where('school_id', $school_id)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
        return response()->json(compact('data', 'count'), 200);
    }

    public function getParentRequest($school_id)
    {
        $data = User::where('role_id', 3)->where('school_id', $school_id)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->orderBy('id', 'DESC')->get();
        $count = User::where('role_id', 3)->where('school_id', $school_id)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->count();
        return response()->json(compact('data', 'count'), 200);
    }


    public function RefreshToken(Request $request)
    {

        /* $accessToken = JWTAuth::refresh();
                $user = JWTAuth::setToken($accessToken)->toUser();*/

        //  return response()->json(compact('accessToken'), 200);
    }
    // send mail for forgot password
    public function ForgotPasswordEmail(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 200);
        } else {
            $uersObj = new User;
            //chech entered email is exist or not
            $user = User::where("email", $request->email)->where('role_id', '=', '5')->first();
            if (!empty($user)) {
                $token = Str::random(60);
                User::where("email", $request->email)->update(["remember_token" => $token]);
                $data = array("name" => $user->name, "url" => url('reset/password?token=' . $token));
                Mail::send("email.forgot-password-admin", $data, function ($m) use ($user) {
                    $m->from('involvvely@gmail.com', 'Involvvely');
                    $m->to($user->email);
                    $m->subject('Forgot Password Link');
                });
                $arr = array("error" => false, "message" => 'Forgot Password email has been sent', "data" => $data);
                return response()->json(compact('user'), 200);
            } else {
                return response()->json(['message' => 'Invalid Email'], 200);
            }
        }
    }
    // send mail for forgot password
    public function adminForgotPasswordEmail(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()], 200);
        } else {
            $uersObj = new User;
            //chech entered email is exist or not
            $user = User::where("email", $request->email)->where('role_id', '=', '1')->first();
            if (!empty($user)) {
                $token = Str::random(60);
                User::where("email", $request->email)->update(["remember_token" => $token]);
                $data = array("name" => $user->name, "url" => url('reset/password?token=' . $token));
                Mail::send("email.forgot-password-admin", $data, function ($m) use ($user) {
                    $m->from('involvvely@gmail.com', 'Involvvely');
                    $m->to($user->email);
                    $m->subject('Forgot Password Link');
                });
                $arr = array("error" => false, "message" => 'Forgot Password email has been sent', "data" => $data);
                return response()->json(compact('user'), 200);
            } else {
                return response()->json(['message' => 'Invalid Email'], 200);
            }
        }
    }
    public function changePassword(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $userpassword = User::where("id", $request->user_id)->first();
            //check current password is matched
            if (Hash::check($request->current_password, $userpassword->password)) {
                if (Hash::check($request->new_password, $userpassword->password)) {
                    return response()->json(['error' => 'true', 'message' => 'You can not set old password again'], 200);
                } else {
                    if ($request->confirm_password == $request->new_password) {
                        $datauser = User::where("id", $request->user_id)->update(["password" => Hash::make($request->input('new_password'))]);
                        return response()->json(compact('datauser'), 200);
                    } else {
                        return response()->json(['error' => 'true', 'message' => 'Passwords do not match. Please try again.'], 200);
                    }
                }
            } else {
                return response()->json(['error' => 'true', 'message' => 'Your current password is invalid'], 200);
            }
        }
    }
    public function resetchangePassword(Request $request)
    {
        $token = $request->token;
        $user_id = DB::table('users')->where('remember_token', $token)->first();
        if (isset($user_id)) {
            $user = User::where('email', $user_id->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    return response()->json(['message' => 'New Password should not match the old one!'], 200);
                } else {
                    $new_data['password'] = Hash::make($request->password);
                    $new_data['remember_token'] = Str::random(50);
                    $resp = User::where('id', $user->id)->update($new_data);
                    if ($resp) {
                        return response()->json(compact('user'), 200);
                    } else {
                        return response()->json(['message' => 'Something went wrong!'], 200);
                    }
                }
            } else {
                return response()->json(['message' => 'User Does Not Exist!'], 200);
            }
        } else {
            return response()->json(['message' => 'Invalid token!'], 200);
        }
    }
    // token checking
    public function TokenChecking(Request $request)
    {
        $token = $request->token;
        $user = DB::table('users')->where('remember_token', $token)->first();
        if (isset($user)) {
            return response()->json(compact('user'), 200);
        } else {
            return response()->json(['message' => 'Token Expired! Please create new link to reset the password'], 200);
        }
    }

    //admin schools list 
    public function AdminGetSchools(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'city_id' => 'required|exists:cities,id'
            ]);
            if ($validator->fails()) {
                return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
            } else {
                $states = School::where('city_id', $request->city_id)->where('approved', '0')->get();
                if (!empty($states)) {
                    return response()->json(array('error' => false, 'data' => $states), 200);
                } else {
                    throw new Exception('No Schools in this city.');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }
    //fetch all admin users
    public function manageReportUsers(Request $request)
    {
        DB::enableQueryLog();
        $users = ReportUser::with('FromDetail')->with('ToDetail')->orderBy('id', 'DESC')->get();
        if (isset($users) && count($users) > 0) {
            return response()->json(compact('users'), 200);
        } else {
            return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
        }
    }
    //send mail to reported users
    public function SendWarningMail(request $request)
    {
        $user = User::where("id", $request->user_id)->first();
        $data = array("name" => $user->name, "reason" => $request->reason);
        Mail::send("email.warning-report-mail", $data, function ($m) use ($user) {
            $m->from('involvvely@gmail.com', 'Involvvely');
            $m->to($user->email);
            $m->subject('Warning Mail');
        });
        $arr = array("error" => false, "message" => 'Warning email has been sent', "data" => $data);
        return response()->json(compact('user'), 200);
    }
}
