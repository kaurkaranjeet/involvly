<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\ClassCode;
use App\Models\AssignedTeacher;
use App\Models\ClassUser;
use App\Models\ClassSubjects;

use Pusher\Pusher;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Exception;
use App\Events\AssignEvent;
use Tymon\JWTAuth\Exceptions\JWTException;

class ParentController extends Controller {


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

       // Register Student
    public function ParentRegister(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                        'first_name' => 'required',
                       //  'device_token' => 'required',
                        'last_name' => 'required',
                        'email' => 'required|unique:users',
                        // 'password' => 'required',
                        'password' => [
                            'required',
                            'string',
                            'min:6',             // must be at least 6 characters in length
                            // 'regex:/[a-z]/',      // must contain at least one lowercase letter
                            'regex:/[A-Z]/',      // must contain at least one uppercase letter
                            'regex:/[0-9]/',      // must contain at least one digit
                            'regex:/[@$!%*#?&]/', // must contain a special character
                        ],
                        'type_of_schooling' => 'required',
                        'country' => 'required',
                        'state_id' => 'required|exists:states,id',
                        'city' => 'required|exists:cities,id',
                        'school_id' => 'required_if:type_of_schooling, =,school'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                if (!empty($request->family_code)) {
                    $code = User::where('family_code', $request->family_code)->count();
                    if ($code == 0) {
                        throw new Exception('Invalid family code');
                    }
                }
                $student_obj = new User;
                $addUser = $student_obj->store($request);
                $token = JWTAuth::fromUser($addUser);
                $addUser->jwt_token = $token;
                //clascodes
                if (!empty($addUser)) {
                   //User::where('id',$addUser->id)->update(['device_token' => $request->device_token]);
                  User::where('id',$addUser->id)->update(['status' =>1]);
                  //familycode
                if (empty($request->family_code)) {
                    $digits = 5;
                    $family_code = $this->random_strings(5);
                    User::where('id',$addUser->id)->update(['family_code' => $family_code,'update_detail' => '1']);
                }else{
                    User::where('id',$addUser->id)->update(['family_code' => $request->family_code,'update_detail' => '1']);
                }
                    if (isset($request->student_id)) {
                        $explode = explode(',', $request->student_id);
                        foreach ($explode as $single) {

                            DB::table('parent_childrens')->updateOrInsert(
                                    [
                                        'parent_id' => $addUser->id,
                                        'children_id' => $single,
                                        'relationship' => $request->relationship
                            ]);
                        }
                    }
                  
                    return response()->json(array('error' => false, 'data' => $addUser), 200);
                } else {
                    throw new Exception('Something went wrong');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    function random_strings($length_of_string) {

        // String of all alphanumeric character 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(str_shuffle($str_result),
                0, $length_of_string);
    }


}

?>