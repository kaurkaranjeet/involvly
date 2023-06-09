<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use Exception;
use App\Models\UserClassCode;
use App\Models\ClassCode;
use App\Models\School;
use App\Models\State;
use App\Models\Timezone;

class LoginController extends Controller {

    /**
     * login
     * @param Request $request
     * @return type
     */
    public function login(Request $request) {
         
        //email,adriod_token,ios_token,access_token,profile picture
        $input = $request->all();
        $validator = Validator::make($input, [
                    'email' => 'required',
                    'password' => 'required',
                    'device_token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $credentials = $request->only(['email', 'password']);
            try {
                if (!$token = JWTAuth::attempt($credentials)) {
                    return response()->json(['message' => 'Invalid Credentials', 'error' => true], 200);
                }
            } catch (JWTException $e) {
                return response()->json(['message' => 'could_not_create_token', 'error' => true], 500);
            }
                $user_details = User::validateLogin($request->all());
            //Update Device Token andJWY token
            User::where('id',$user_details->id)->update(['device_token' => $request->device_token,'ActiveJwttoken'=>$token]);
            $user_details->device_token=$request->device_token;
            // get class code 
           // if ($user_details->role_id == 2) {
                $classCode = UserClassCode::where('user_id', $user_details->id)->first();

                
                if (!empty($classCode)) {
                    $class_code = ClassCode::where('id', $classCode->class_id)->first();
                    $user_details->class_id = $classCode->class_id;
                    $user_details->class_name = $class_code->class_name;
                    $user_details->school_status= $classCode->active;
                }
                else{
                  $user_details->class_id = '';
                  $user_details->class_name = '';  
                  $user_details->school_status = '0';
              }
              //check school approval status 
              $school_details = School::where('id', $user_details->school_id)->first();
              if(!empty($school_details)){
                  if($school_details->approved == 1){
                     $user_details->school_live = '1'; 
                  }else{
                     $user_details->school_live = '0'; 
                  }
              }else{
                    $user_details->school_live = '0';
              }


            //}
            $user_details->ActiveJwttoken=$token;
 
            if ($user_details->role_id == $request->role_id) {
                $user_details->role_id =$user_details->role_id;
                $user_details->state_id = strval($user_details->state_id);
                $user_details->school_id = strval($user_details->school_id);
                $user_details->status = strval($user_details->status);
                $user_details->join_community = strval($user_details->join_community);
                /*****get timezone data*******/
            if($user_details->type_of_schooling == 'school'){
                if(empty($user_details->timezone_id) || $user_details->timezone_id == ''){
                    //get school timezone
                    $schooldata = School::where('id', $user_details->school_id)->first();
                    $statedata = State::where('id', $schooldata->state_id)->first();
                    $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                    // $single_notification->timezone = $timezone;
                    $user_details->timezone_offset = $timezone->utc_offset;
                    $user_details->timezone_name = $timezone->timezone_name;
                    
                }else{
                    //get user timezone
                    $timezone = Timezone::where('id', $user_details->timezone_id)->first();
                    // $single_notification->timezone = $timezone;
                    $user_details->timezone_offset = $timezone->utc_offset;
                    $user_details->timezone_name = $timezone->timezone_name;
                }
            }
                if (!empty($user_details)) {
                    return response()->json(array('error' => false, 'data' => $user_details, 'message' => 'Login Successfully'), 200);
                } else {
                    return response()->json(array('error' => true, 'data' => [], 'message' => 'Something went wrong'), 200);
                }
            } else {
                return response()->json(array('error' => true, 'data' => [], 'message' => 'You are not allowed to login'), 200);
            }
        }
    }

    public function Logout(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $u = User::find($request->user_id);
            $u->device_token = null;
            $u->save();
            return response()->json(array('error' => false, 'message' => 'Logout Successfully', 'data' => $u), 200);
        }
    }

    public function forgot_password(Request $request) {
        try {
            $input = $request->all();
            $rules = array(
                'email' => "required|email",
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $otp_code = rand(1000, 9999);
                User::where("email", $request->email)->update(["otp_code" => $otp_code]);
                $datauser = User::where("email", $request->email)->first();
                if (!empty($datauser)) {
                    $data = array("name" => $datauser->first_name, "otp_code" => $datauser->otp_code);
                    Mail::send("email.reset-password", $data, function ($m) use ($datauser) {
                        $m->from('involvvely@gmail.com', 'Involvvely');
                        $m->to($datauser->email);
                        $m->subject('One time password');
                    });
                    $arr = array("error" => false, "message" => 'Otp has been sent', "data" => $data);
                } else {

                    throw new Exception('Email does not exist');
                }
            }
        } catch (Exception $ex) {
            $arr = array("error" => true, "message" => $ex->getMessage(), "data" => []);
        }

        return \Response::json($arr);
    }

    public function VerifyOtp(Request $request) {
        $input = $request->all();
        $rules = array(
            'email' => "required",
            'otp_code' => "required",
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $arr = array("error" => true, "message" => $validator->errors()->first(), "data" => array());
        } else {
            try {
                $datauser = User::where("email", $request->email)->first();
                if ($datauser->otp_code == $request->otp_code) {
                    $arr = array("error" => false, "message" => 'Your otp is verfied.', "data" => $datauser);
                } else {
                    throw new Exception('Incorrect Otp');
                }
            } catch (Exception $ex) {
                $arr = array("error" => true, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }

    public function ChnagePassword(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'email' => 'required',
                    'confirm_password' => 'required',
                    'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            try {
                if ($request->confirm_password == $request->new_password) {
                    $datauser = User::where("email", $request->email)->update(["password" => Hash::make($request->input('new_password'))]);
                    $arr = array("error" => false, "message" => 'Your password is changed', "data" => $datauser);
                } else {
                    throw new Exception('Passwords do not match. Please try again.');
                }
            } catch (Exception $ex) {
                $arr = array("error" => true, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }

    public function EmailExist(Request $request) {
        //email,adriod_token,ios_token,access_token,profile picture
        $input = $request->all();
        $validator = Validator::make($input, [
                    'email' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {

            $exist = User::where('email', $request->email)->count();
            if ($exist > 0) {
                return response()->json(array('error' => true, 'message' => 'This email is already being used'), 200);
            } else {
                return response()->json(array('error' => false, 'message' => 'Email does not exist'), 200);
            }
        }
    }

}
