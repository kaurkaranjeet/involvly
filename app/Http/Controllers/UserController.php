<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller {

    public function adminLogin(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = User::where('email', '=', $request->input('email'))->where('role_id', 1)->first();
        return response()->json(compact('accessToken', 'user'));
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = User::where('email', '=', $request->input('email'))->whereIn('role_id', [5, 1])->first();
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
                $data[] = $rr->views;
            }
        }
        $Studentseries[]['data'] = $data;

        $teachers = User::getteachers($id)->count();
        $tseries_sql = User::getteachers($id)->select(DB::raw('DATE(created_at) as date'), DB::raw('count(id) as views'))->groupBy('date')->get();
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
            $users = User::where('role_id', 4)->where('status', 1)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.class_code) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'))->get();
        } else if ($request->type == 'student') {
            $users = User::where('role_id', 2)->where('status', 1)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.class_code) AS class_codes from user_class_code inner join class_code as u ON user_class_code.class_id=u.id where user_id=users.id) as class_codes ,users.*'))->get();
        } else {

            $users = User::where('role_id', 3)->where('status', 1)->where('school_id', $id)->select(DB::raw('(select GROUP_CONCAT(u.name) AS childrens from parent_childrens inner join users as u ON parent_childrens.children_id=u.id where parent_id=users.id) as associated_child ,users.*'))->get();
        }
        //  print_r(DB::getQueryLog());die;

        if (isset($users) && count($users) > 0) {
            return response()->json(compact('users'), 200);
        } else {
            return response()->json(['error' => 'true', 'users' => [], 'message' => 'No record found'], 200);
        }
    }

    public function fetchUser($id) {
        $data = User::fetchUser($id);
        if (isset($data)) {
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function UpdateProfile(Request $request) {
        $name = $request->first_name . ' ' . $request->last_name;
        User::where('id', $request->id)->update(['first_name' => $request->first_name, 'last_name' => $request->last_name, 'name' => $name, 'status' => $request->status]);
        $data = User::find($request->id);
        if (!empty($data)) {
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function RemoveUser($id) {
        $data = User::where('id', $id)->delete();
        return response()->json(compact('data'), 200);
    }

    public function getRequest($school_id) {
        $data = User::where('role_id', 4)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->get();
        return response()->json(compact('data'), 200);
    }

    public function getStudentRequest($school_id) {
        $data = User::where('role_id', 2)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->get();
        return response()->json(compact('data'), 200);
    }

    public function getParentRequest($school_id) {
        $data = User::where('role_id', 3)->where('school_id', $school_id)->where('status', 0)->select('id', 'name', 'email', DB::raw('DATE(created_at) as date'), 'id', 'status')->get();
        return response()->json(compact('data'), 200);
    }

}

?>