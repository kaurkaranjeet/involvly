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
use App\Models\UserClassCode;
use App\Models\School;
use App\Models\JoinedStudentClass;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use DB;
use Hash;
use Illuminate\Support\Facades\Artisan;
use App\Notification;
use App\Models\Comment;
use App\Models\CommentReply;
use App\Models\DiscussionComment;
use App\Models\DiscussionCommentReply;
use App\Models\Group;
use App\Models\TeachingProgram;
use App\Models\TeachingProgramReq;
use App\UserClass;
use App\UserSubject;
use App\Models\Timezone;

class CommonController extends Controller
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

    public function GetStates(Request $request)
    {
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

    public function GetClasses(Request $request)
    {
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

    public function GetSubjectsByClass(Request $request)
    {
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
                /*  select *,( SELECT teacher_id FROM assigned_teachers 
                WHERE class_id= class_code_subject .class_code_id AND
                subject_id=class_code_subject .subject_id) as teacher_id ,
                ( SELECT name FROM assigned_teachers INNER JOIN users
                ON users.id= assigned_teachers.teacher_id WHERE
                class_id= class_code_subject .class_code_id AND subject_id=class_code_subject .subject_id) as name
                from class_code_subject LEFT JOIN assigned_teachers ON assigned_teachers.subject_id=class_code_subject.subject_id
                where class_code_id=12 AND assigned_teachers.subject_id= class_code_subject.subject_id GROUP BY class_code_subject.id
                */
                $count_classcode = UserClassCode::where('class_id', $request->class_id)->where('active', '1')->where('user_id', $request->student_id)->count();
                if ($count_classcode > 0) {
                    $states = ClassSubjects::select((DB::raw("( CASE WHEN EXISTS (
        SELECT *
        FROM joined_student_classes
        WHERE class_id = class_code_subject.class_code_id
        AND student_id= " . $request->student_id . " AND subject_id = class_code_subject. subject_id
        ) THEN TRUE
        ELSE FALSE END)
        AS already_join  , ( SELECT teacher_id FROM assigned_teachers 
WHERE class_id= class_code_subject .class_code_id AND
 subject_id=class_code_subject .subject_id) as teacher_id , ( SELECT name FROM assigned_teachers INNER JOIN users
 ON users.id= assigned_teachers.teacher_id WHERE
 class_id= class_code_subject .class_code_id AND subject_id=class_code_subject .subject_id) as name, class_code_subject.*")))->with('subjects')
                        ->where('class_code_id', $request->class_id)->groupBy('subject_id')->get();

                } else {

                    return response()->json(array('error' => false, 'message' => 'You are not connected to this school', 'data' => []), 200);
                }


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

    public function GetCities(Request $request)
    {
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

    public function GetSchools(Request $request)
    {
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

    public function GetParentSchools(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'parent_id' => 'required|exists:users,id'
            ]);
            if ($validator->fails()) {
                return response()->json(array('error' => true, 'message' => $validator->errors()), 200);
            } else {
                $Schoolsa = User::Join('parent_childrens', 'parent_childrens.children_id', '=', 'users.id')->Join('schools', 'schools.id', '=', 'users.school_id')->leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')->select('users.school_id', 'schools.school_name')->where('parent_id', $request->parent_id)->where('user_class_code.active', '1')->distinct()->get();
                $user_school = User::Join('schools', 'schools.id', '=', 'users.school_id')->select('users.school_id', 'schools.school_name')->where('users.id', $request->parent_id)->get();

                $collection = collect($Schoolsa);
                $mergedCollection = $collection->merge($user_school);
                $mergedCollection = $mergedCollection->unique(function ($item) {

                    return $item;

                });
                $result = $mergedCollection->all();
                // return $result;
                //  $Schools = $Schoolsa->merge($user_school);
                //$Schools = $merged->all();



                if (!empty($result)) {
                    return response()->json(array('error' => false, 'data' => $result), 200);
                } else {
                    throw new Exception('No Schools in this city.');
                }
            }

        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function GetSubjects(Request $request)
    {
        try {


            $Subject = Subject::whereNotNull('school_id')->get();
            if (!empty($Subject)) {
                return response()->json(array('error' => false, 'data' => $Subject), 200);
            } else {
                throw new Exception('No Record');
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function Joincommunity(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'user_id' => 'required',
                'join_community' => 'required',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                User::where('id', $request->user_id)->update(['join_community' => $request->join_community]);
                return response()->json(array('error' => false, 'data' => [], 'message' => 'Updated Successfully'), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function JoinProgram(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'user_id' => 'required',
                'join_program_status' => 'required',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $users = User::where('id', $request->user_id)->update(['join_teaching_program' => $request->join_program_status]);
                return response()->json(array('error' => false, 'data' => $users, 'message' => 'Updated Successfully'), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }
    public function GetProgramList(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'school_id' => 'required',
                // 'selected_class' => 'exists:class_code,class_name',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $classes = ClassCode::select('id', 'class_name')->where('school_id', $request->school_id)->get();
                $subject = array();
                $subject = array();
                if (!empty($request->selected_class)) {

                    //dd($request->selected_class);
                    foreach ($request->selected_class as $key => $data) {
                        $subjects = Subject::select('id', 'subject_name')
                            ->where('school_id', $request->school_id);
                        if (strpos($data, 'Grade') !== false) {
                            $subjects->where('subject_name', 'not like', 'General' . '%');
                        } else {
                            $subjects->where('subject_name', 'like', '%' . $data);
                        }
                    $subjects = $subjects->get()->toArray();
                    $subject =  array_unique(array_merge($subject, $subjects), SORT_REGULAR);
                    }  
                }
                $location = Cities::select('county', 'id')->groupBy('county')->havingRaw('count(*) > 1')->get();
                $data = array('classes' => $classes, 'subjects' => $subject, 'location' => $location);
                return response()->json(array('error' => false, 'data' => $data, 'message' => 'Filtered Successfully'), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function AddTeachingProgram(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'user_id' => 'required|exists:users,id',
                'class_id' => 'required',
                'subject_id' => 'required',
                'hourly_rate' => 'required',
                'availability' => 'required|in:Full-Time,Part-Time,Both',
                'location' => 'required',
                'preferences' => 'required|in:On-Site,Remote',

            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $input['id'] = '1';
                 $data = TeachingProgram::add($input);
               
                if (is_array($request->subject_id)) {
                    foreach ($request->subject_id as $key => $value) {
                        $subject['subject_id'] = $value;
                        $subject['user_id'] = $request->user_id;
                         $subjects= UserSubject::add($subject);
                    } 
                }
                if (is_array($request->class_id)) {
                    foreach ($request->class_id as $key => $value) {
                        $class_id['class_id'] = $value;
                        $class_id['user_id'] = $request->user_id;
                        UserClass::add($class_id);
                    }
                }

                // User::where('id', $request->user_id)->update(['join_teaching_program' => $request->join_program_status]);
                return response()->json(array('error' => false, 'data' => $data, 'message' => 'Updated Successfully'), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function UpdateUserImage(Request $request)
    {
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

    public function UpdateUserName(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $update_name = User::find($request->user_id);
            $name = '';
            if (!empty($request->first_name)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'first_name' => $request->first_name,

                ]);

                $name = $request->first_name;
            } else {
                $name = $update_name->first_name;
            }
            if (!empty($request->last_name)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'last_name' => $request->last_name,

                ]);

                $name .= ' ' . $request->last_name;
            } else {
                $name .= ' ' . $update_name->last_name;
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
            if (!empty($name)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'name' => $name,
                ]);
            }
            if (!empty($request->timezone_id)) {
                $updateData = User::where('id', $request->user_id)->update([
                    'timezone_id' => $request->timezone_id,
                ]);
            }
            //$update = User::find($request->user_id);
            $update = User::where('id', $request->user_id)->first();
            if ($update->type_of_schooling == 'school') {
                if (empty($update->timezone_id) || $update->timezone_id == '') {
                    //get school timezone
                    $schooldata = School::where('id', $update->school_id)->first();
                    $statedata = State::where('id', $schooldata->state_id)->first();
                    $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                    // $single_notification->timezone = $timezone;
                    $update->timezone_offset = $timezone->utc_offset;
                    $update->timezone_name = $timezone->timezone_name;

                } else {
                    //get user timezone
                    $timezone = Timezone::where('id', $update->timezone_id)->first();
                    // $single_notification->timezone = $timezone;
                    $update->timezone_offset = $timezone->utc_offset;
                    $update->timezone_name = $timezone->timezone_name;
                }
            }
            return response()->json(array('error' => false, 'message' => 'Profile updated successfully', 'data' => $update), 200);
        }
    }

    public function UpdateUserPassword(Request $request)
    {
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
                    throw new Exception('Passwords do not match. Please try again.');
                }
            } catch (Exception $ex) {
                $arr = array("error" => true, "message" => $ex->getMessage(), "data" => []);
            }
        }
        return \Response::json($arr);
    }

    public function DeleteAccount(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $userobj = User::where('id', $request->user_id)->first();
            $delete = User::where('id', $request->user_id)->delete();
            $this->pusher->trigger('remove-channel', 'delete_user', $userobj);
            if ($delete) {
                //notification or comments
                Notification::where('user_id', $request->user_id)->delete();
                Comment::where('user_id', $request->user_id)->delete();
                CommentReply::where('user_id', $request->user_id)->delete();
                DiscussionComment::where('user_id', $request->user_id)->delete();
                DiscussionCommentReply::where('user_id', $request->user_id)->delete();
                Group::where('user_id', $request->user_id)->delete();
                return response()->json(array('error' => false, 'message' => 'Account deleted successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    public function GetUsers(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $userobj = User::where('id', $request->user_id)->first();
            if (!empty($userobj)) {
                return response()->json(array('error' => false, 'message' => 'User get successfully', 'data' => $userobj), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    public function GetTimezone(Request $request)
    {
        try {
            $timezone = Timezone::get();
            if (!empty($timezone)) {
                return response()->json(array('error' => false, 'data' => $timezone), 200);
            } else {
                throw new Exception('No timezone in the list.');
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    public function RunMigration()
    {
        Artisan::call('migrate');
    }

}