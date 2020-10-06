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
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'errors' => $validator->errors()->first()), 200);
        } else {
            $credentials = $request->only(['email', 'password']);
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['message' => 'Invalid Credentials', 'error' => true], 200);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            $user_details = User::validateLogin($request->all());
            $user_details->token = $token;
            if (!empty($user_details)) {
                return response()->json(array('error' => false, 'data' => $user_details), 202);
            } else {
                return response()->json(array('error' => true, 'data' => []), 200);
            }
        }
    }
    
    

    public function Logout(Request $request){
     $input = $request->all();
     $validator = Validator::make($input, [
        'user_id' => 'required',

    ]);
     if ($validator->fails()) {
        return response()->json(array('error' => true, 'errors' => $validator->errors()->first()), 200);
    } else {
        $u =User::find($request->user_id); 
        $u->device_token =null; 
        $u->save(); 
        return response()->json(array('error' => false, 'message' => 'logout', 'data' => $u), 200);
    }
}

public function forgot_password(Request $request)
{
     try {
    $input = $request->all();
    $rules = array(
        'email' => "required|email",
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
          throw new Exception($validator->errors()->first());


    } else {
        $otp_code=rand ( 1000 , 9999 );        
        User::where("email", $request->email)->update(["otp_code"=>$otp_code]);      
        $datauser= User::where("email",$request->email)->first();
             if(!empty($datauser)){
             $data=array("name"=>$datauser->first_name,"otp_code"=>$datauser->otp_code);
            Mail::send("email.reset-password", $data, function ($m) use ($datauser) {
            $m->from('testinvolvly@gmail.com','Involvly');
            $m->to($datauser->email);
            $m->subject('One time password');
        
          

    });
            $arr = array("error"=>false, "message" =>'One time password', "data" => $data); 
        }
        else{

                  throw new Exception('Email is not valid');
            
        } 
        }   
        } catch (Exception $ex) {
            $arr = array("error"=>true, "message" => $ex->getMessage(), "data" => []);
        }
    
    return \Response::json($arr);
}

public function VerifyOtp(Request $request)
{
    $input = $request->all();
    $rules = array(
      'email' => "required",
       'otp_code' => "required",
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array(  "error"=>true, "message" => $validator->errors()->first(), "data" => array());
    } else {       
        try {
             $datauser=  User::where("email",$request->email)->first();
            if($datauser->otp_code==$request->otp_code){
             $arr = array("error"=>false, "message" =>'Your otp is verfied.', "data" => $datauser); 
            }else{
                  throw new Exception('Incorrect Otp');
            }            
        } catch (Exception $ex) {
            $arr = array("error"=>true, "message" => $ex->getMessage(), "data" => []);
        }
    }
    return \Response::json($arr);
}

public function ChnagePassword(Request $request)
{
  $input = $request->all();
         $validator = Validator::make($input, [
                    'user_id' => 'required',
                    'confirm_password' => 'required',                  
                    'new_password' => 'required',
                  
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        }  else {       
        try {          
            if($request->confirm_password==$request->new_password){
             $datauser=  User::find($request->user_id)->update(["password" =>Hash::make($request->input('new_password'))]);
             $arr = array("error"=>false, "message" =>'Your password is changed', "data" => $datauser); 
            }else{
                  throw new Exception('Confirm password do not match');
            }            
        } catch (Exception $ex) {
            $arr = array("error"=>true, "message" => $ex->getMessage(), "data" => []);
        }
    }
    return \Response::json($arr);
}
}
