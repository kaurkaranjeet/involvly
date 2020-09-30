<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;

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
                if (!$token = JWTAuth::attempt($credentials)) {
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
}
