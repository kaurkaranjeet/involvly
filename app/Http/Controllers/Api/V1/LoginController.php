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
                    return response()->json(['message' => 'invalid_credentials', 'error' => true], 200);
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
    $input = $request->all();
    $rules = array(
        'email' => "required|email",
    );
    $validator = Validator::make($input, $rules);
    if ($validator->fails()) {
        $arr = array(  "error"=>true, "message" => $validator->errors()->first(), "data" => array());
    } else {
        try {
            Mail::send(["email.reset-password"], $data=array(), function($message) {
         $message->to('harpreet.kaur@digimantra.com', 'Tutorials Point')->subject
            ('Laravel Basic Testing Mail');
         $message->from('harpreet.kaur@digimantra.com','Virat Gandhi');
      });
           
            
        } catch (Exception $ex) {
            $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);
        }
    }
    return \Response::json($arr);
}
}
