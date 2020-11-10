<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\User;
use App\Models\ClassCode;
use App\Models\ClassSubjects;
use App\Models\Subject;
use App\Models\State;
use App\Models\Cities;
use App\Models\School;
use App\Models\JoinedStudentClass;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use DB;
use Hash;
use Illuminate\Support\Facades\Artisan;

class CommonController extends Controller {

    public function __construct() {
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

    public function GetStates(Request $request) {
        try {

            $states = State::all();
            if (!empty($states)) {
                return response()->json(array('error' => false, 'data' => $states), 200);
            } else {
                throw new Exception('No Record');
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function GetClasses(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                        'school_id' => 'required|exists:schools,id'
            ]);
            if ($validator->fails()) {
                return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
            } else {
                $states = ClassCode::where('school_id', $request->school_id)->get();
                if (!empty($states)) {
                    return response()->json(array('error' => false, 'data' => $states), 200);
                } else {
                    throw new Exception('No class in this school.');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function GetSubjectsByClass(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
//                        'class_id' => 'required|exists:class_code,id',
                        'student_id' => 'required|exists:users,id',
                        'class_id' => 'required|exists:class_code,id',
                        'school_id' => 'required|exists:schools,id',
            ]);
            if ($validator->fails()) {
                return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
            } else {
                //get subjects
               
                $states = ClassSubjects::select((DB::raw("( CASE WHEN EXISTS (
        SELECT *
        FROM joined_student_classes
        WHERE class_id = class_code_subject.class_code_id
        AND student_id= ".$request->student_id." AND subject_id = class_code_subject. subject_id
        ) THEN TRUE
        ELSE FALSE END)
        AS already_join  ,(SELECT name from users WHERE users.id=assigned_teachers.teacher_id) as name, class_code_subject.*, assigned_teachers.teacher_id")))->with('subjects')
                                ->leftJoin('assigned_teachers', 'class_code_subject.subject_id', '=', 'assigned_teachers.subject_id')
                                ->where('class_code_id', $request->class_id)->groupBy('subject_id')->get();

            


//         
                if (!empty($states)) {
                    return response()->json(array('error' => false, 'message' => 'Subjects fetched successfully', 'data' => $states), 200);
                } else {
                    throw new Exception('No Subjects in this school.');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function GetCities(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                        'state_id' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors());
            } else {
                $cities = Cities::where('state_id', $request->state_id)->get();
                if (!empty($cities)) {
                    return response()->json(array('error' => false, 'data' => $cities), 200);
                } else {
                    throw new Exception('No City found');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function GetSchools(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                        'city_id' => 'required|exists:cities,id'
            ]);
            if ($validator->fails()) {
                return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
            } else {
                $states = School::where('city_id', $request->city_id)->get();
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

    public function GetSubjects(Request $request) {
        try {

            $Subject = Subject::whereNull('school_id')->get();
            if (!empty($Subject)) {
                return response()->json(array('error' => false, 'data' => $Subject), 200);
            } else {
                throw new Exception('No Record');
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function Joincommunity(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                        'user_id' => 'required',
                        'join_community' => 'required',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                User::where('id', $request->user_id)->update(['join_community' => '1']);
                return response()->json(array('error' => false, 'data' => [], 'message' => 'Updated Successfully'), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function UpdateUserImage(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            // upload image file
            if ($request->hasfile('image')) {
                $video = $request->file('image');
                $name = time() . '.' . trim($video->getClientOriginalExtension());
                $destinationPath = public_path('/uploads');
                $video->move($destinationPath, $name);
                $videourl = url('/') . '/uploads/' . $name;
                $updateData = User::where('id', $request->user_id)->update([
                    'profile_image' => $videourl
                ]);
            }
            $update = User::find($request->user_id);
            return response()->json(array('error' => false, 'message' => 'Profile updated successfully', 'data' => $update), 200);
        }
    }

    public function UpdateUserName(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            if (!empty($request->first_name)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'first_name' => $request->first_name,
                ]);
            }
            if (!empty($request->last_name)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'last_name' => $request->last_name,
                ]);
            }
            if (!empty($request->facebook_url)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'facebook_url' => $request->facebook_url,
                ]);
            }
            if (!empty($request->linkedin_url)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'linkedin_url' => $request->linkedin_url,
                ]);
            }
            if (!empty($request->twitter_url)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'twitter_url' => $request->twitter_url,
                ]);
            }
            $update = User::find($request->user_id);
            return response()->json(array('error' => false, 'message' => 'Profile updated successfully', 'data' => $update), 200);
        }
    }

    public function UpdateUserPassword(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'user_id' => 'required|exists:users,id',
                    'old_password' => 'required',
                    'confirm_password' => 'required',
                    'new_password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            try {
                if ($request->confirm_password == $request->new_password) {
                    $user = User::where('id', '=', $request->user_id)->first();
                    if (Hash::check($request->old_password, $user->password)) {
                        $datauser = User::where("id", $request->user_id)->update([
                            "password" => Hash::make($request->new_password)
                        ]);
                        $arr = array("error" => false, "message" => 'Your password is changed', "data" => $user);
                    } else {
                        throw new Exception('Old password do not match');
                    }
                } else {
                    throw new Exception('Confirm password do not match');
                }
            } catch (Exception $ex) {
                $arr = array("error" => true, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }

    public function DeleteAccount(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
                    'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $delete = User::where('id', $request->user_id)->delete();
            if ($delete) {
                return response()->json(array('error' => false, 'message' => 'Account deleted successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    public function RunMigration() {
        Artisan::call('migrate');
    }

}
