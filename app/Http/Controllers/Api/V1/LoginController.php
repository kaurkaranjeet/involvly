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

    /**
     * facebook/google login/signup
     * @param Request $request
     * @return type
     */
    public function socialSignupLogin(Request $request) {
        $input = $request->all();
        //check authentication keys exist or not 
        if (User::where('authenticiation_key', '=', $request->authenticiation_key)->exists() ) {
            //login
           
                if($request->type!='snapchat'){
                    $user_details = User::validateLogin($request->all()); 
                }else{
                    $user_details = User::validateautheticationkey($request->all()); 
                }


                if (!empty($user_details)) {
                    $affected = User::where('id', $user_details->id)->update(['image' => $request->image,'device_token' => $request->device_token,'device_type' => $request->device_type]);
                    return response()->json(array('error' => false, 'message' => 'social login successfully', 'data' => $user_details), 202);

                }
                           
            return response()->json(array('error' => true, 'message' => 'email is empty', 'data' => []), 200);
        

    } else {
           if (User::where('email', '=', $request->email)->exists()) {
                $user_details = User::validateLogin($request->all());
                 if (!empty($user_details)) {               
              
                $affected = User::where('id', $user_details->id)->update(['authenticiation_key' => $request->authenticiation_key,'device_token' => $request->device_token,'type' => $request->type,'image' => $request->image,'device_token' => $request->device_token,'device_type' => $request->device_type]);
                 $user_details = User::validateLogin($request->all());
                  return response()->json(array('error' => false, 'message' => 'social login successfully', 'data' => $user_details), 202);
            }

           }else{

            $validator = Validator::make($input, [
                        'name' => 'required',
                        'email' => 'required_if:type,facebook||required_if:type,google|unique:users,email',
                        'authenticiation_key' => 'required',
                        'type' => 'required',
                        'device_token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
            } else {
                $uersObj = new User;
                $request->request->add([
                    'email_verified_at' => null,
                    'password' => '',
                    'remember_token' => '',
                ]);
                //signup
                $addUser = $uersObj->store($request);
                if (!empty($addUser)) {
                    //login
                    DB::table('role_user')->insert(
                        ['role_id' => '2', 'user_id' => $addUser->id]
                    );
                 
                   if($request->type!='snapchat' ||  $request->type!='spotify'){
                    $user_details = User::validateLogin($request->all()); 
                }else{
                    $user_details = User::validateautheticationkey($request->all()); 
                }
                                    
                    if (!empty($user_details)) {
                        return response()->json(array('error' => false, 'message' => 'social login successfully', 'data' => $user_details), 202);
                    }
                    return response()->json(array('error' => true, 'message' => 'something went wrong', 'data' => []), 200);
                } else {
                    return response()->json(array('error' => true, 'message' => 'user not added', 'data' => []), 200);
                }
            }
        }
        }
    }
    
    /**
     * spotify/soundcloud login/signup
     * @param Request $request
     * @return type
     */
    public function musicSignupLogin(Request $request){
        $input = $request->all();
        //check authentication keys exist or not 
        if (User::where('authenticiation_key', '=', $request->authenticiation_key)->exists()) {
            //login
            if (!empty($request->email)) {
                $user_details = User::validateLogin($request->all());
                if (!empty($user_details)) {
                    return response()->json(array('error' => false, 'message' => 'social login successfully', 'data' => $user_details), 202);
                }
            }
            return response()->json(array('error' => true, 'message' => 'email is empty', 'data' => []), 200);
        } else {
            $validator = Validator::make($input, [
                        'email' => 'required|unique:users,email',
                        'authenticiation_key' => 'required',
                        'type' => 'required',
                        'device_token' => 'required',
                        'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
            } else {
                $uersObj = new User;
                $request->request->add([
                    'email_verified_at' => null,
                    'password' => '',
                    'remember_token' => '',
                ]);
                //signup
                $addUser = $uersObj->store($request);
                if (!empty($addUser)) {
                    //login
                    $user_details = User::validateLogin($request->all());
                    if (!empty($user_details)) {
                        return response()->json(array('error' => false, 'message' => 'social login successfully', 'data' => $user_details), 202);
                    }
                    return response()->json(array('error' => true, 'message' => 'something went wrong', 'data' => []), 200);
                } else {
                    return response()->json(array('error' => true, 'message' => 'user not added', 'data' => []), 200);
                }
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
