<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\ClassCode;
use App\Models\ClassUser;
use App\Models\UnapproveStudent;
use App\Models\ParentTask;
use App\Models\ParentTaskAssigned;
use App\Models\ParentChildrens;
use App\Models\Schedule;
use App\Models\AssignedTeacher;
use App\Models\School;
use App\Models\State;
use App\Models\Timezone;
use App\Notification;
use Pusher\Pusher;
use App\Events\NotificationEvent;
use App\Jobs\ProcessRequest;
use App\Models\Cities;
use App\Models\Subject;
use App\Models\TeachingProgram;
use App\Models\TeachingProgramReq;
use App\UserClass;
use App\UserSubject;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

use function GuzzleHttp\json_encode;

class ParentController extends Controller
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

    // Register Student
    public function FirststepParentRegister(Request $request)
    {

        try {
            $input = $request->all();
            $user = [
                'first_name' => 'required',
                'device_token' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'role_id' => 'required',
            ];

            $validator = Validator::make($input, $user);
            // var_dump($request->location);exit;
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                // var_dump($input['subject_id']);

                $student_obj = new User;
                $addUser = $student_obj->store($request);
                $token = JWTAuth::fromUser($addUser);

                $addUser->ActiveJwttoken = $token;
                $addUser->school_status = '0';
                $addUser->update_detail = '0';
                $addUser->school_live = '0';
                //Update jwt Token
                User::where('id', $addUser->id)->update(['ActiveJwttoken' => $token]);
                //  $addUser->relationship = $request->relationship;


                return response()->json(array('error' => false, 'message' => 'Registered Successfully', 'data' => $addUser), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function updateotherDetails(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                'user_id' => 'required',
                'country' => 'required',
                'type_of_schooling' => 'required',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                //'school_id' => 'required_if:type_of_schooling, =,school'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $family_code = '';
                $student_obj = User::find($request->user_id);
                $student_obj->country = $request->country;
                $student_obj->state_id = $request->state_id;
                $student_obj->city = $request->city_id;
                $student_obj->school_id = $request->school_id;
                $student_obj->update_detail = '1';
                if ($request->type_of_schooling == 'home') {
                    if ($student_obj->role_id == '4') {
                        $student_obj->status = '0';
                    } else {
                        $student_obj->status = '1';
                    }
                } else {
                    $student_obj->status = '1';
                }
                $student_obj->type_of_schooling = $request->type_of_schooling;
                // $student_obj->status=2;
                if ($student_obj->role_id != '4') {
                    $student_obj->status = '1';
                    if (empty($request->family_code)) {
                        $digits = 5;
                        $family_code = $this->random_strings(5);
                        ;
                        $student_obj->family_code = $family_code;
                    } else {
                        $code = User::where('family_code', $request->family_code)->count();
                        if ($code == 0) {

                            throw new Exception('Invalid family code');
                        }
                    }
                }



                if ($request->has('school_name')) {
                    $school_details = School::where('school_name', $request->school_name)->first();
                    if (empty($school_details)) {
                        $school_obj = new School;
                        $school_obj->city_id = $request->city_id;
                        $school_obj->state_id = $request->state_id;
                        $school_obj->school_name = $request->school_name;
                        $school_obj->approved = 0;
                        $school_obj->save();
                        $data = [
                            ['class_name' => 'Preschool', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Kindergarten', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 1', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 2', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 3', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 4', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 5', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 6', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 7', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 8', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 9', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 10', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 11', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id],
                            ['class_name' => 'Grade 12', 'class_code' => rand(1000, 9999), 'approved' => 1, 'school_id' => $school_obj->id]
                        ];
                        ClassCode::insert($data);
                        $student_obj->school_id = $school_obj->id;

                        if (!empty($request->class_id)) {
                            $class = $request->class_id - 1;
                            $search_class_name = $data[$class]['class_name'];
                            $class_code = ClassCode::where('class_name', $search_class_name)->where('school_id', $school_obj->id)->first();
                            if (!empty($class_code)) {
                                $classobj = ClassUser::create(
                                    ['user_id' => $request->user_id, 'class_id' => $class_code->id, 'active' => 0]
                                );
                            } else {
                                // return response()->json(array('error' => true, 'message' => $search_class_name), 200);
                            }
                        }
                    } else {
                        return response()->json(array('error' => true, 'message' => 'School name already exist'), 200);
                    }
                }


                $student_obj->save();

                if (!empty($request->class_code)) {
                    $class_code = ClassCode::where('id', $request->class_code)->where('school_id', $request->school_id)->first();
                    if (!empty($class_code)) {
                        $classobj = ClassUser::create(
                            ['user_id' => $request->user_id, 'class_id' => $class_code->id, 'active' => 0]
                        );
                    } else {
                        return response()->json(array('error' => true, 'message' => 'Class code does not belong to this school'), 200);
                    }
                }

                if ($student_obj->role_id == '2') {
                    if (!empty($request->family_code)) {
                        $parents = User::where('family_code', $request->family_code)->where('role_id', 3)->get();
                        foreach ($parents as $singl) {
                            $count = ParentChildrens::where('parent_id', $singl->id)->where('children_id', $request->user_id)->count();
                            if ($count == 0) {
                                DB::table('parent_childrens')->insert(
                                    [
                                        'parent_id' => $singl->id,
                                        'children_id' => $request->user_id,
                                        'relationship' => $singl->relationship
                                    ]
                                );
                            }
                        }
                    }
                }

                if ($student_obj->role_id == '3') {
                    if (!empty($request->family_code)) {
                        $students = User::where('family_code', $request->family_code)->where('role_id', 2)->get();
                        foreach ($students as $singl) {
                            $count = ParentChildrens::where('parent_id', $student_obj->id)->where('children_id', $singl->id)->count();
                            if ($count == 0) {

                                DB::table('parent_childrens')->insert(
                                    [
                                        'parent_id' => $student_obj->id,
                                        'children_id' => $singl->id
                                        //'relationship' => $request->relationship
                                    ]
                                );
                            }
                        }
                    }

                    $parents = ParentChildrens::where('parent_id', $request->user_id)->get();
                    foreach ($parents as $singl) {
                        ParentChildrens::where('id', $singl->id)->update(['relationship' => $request->relationship]);
                    }
                }
                if (!empty($request->family_code)) {
                    User::where('id', $request->user_id)->update(['family_code' => $request->family_code]);
                }

                // }

                $addUser = User::with('StateDetail')->with('CityDetail')->with('SchoolDetail')->where('id', $request->user_id)->first();
                if (!empty($addUser->SchoolDetail)) {

                    if ($addUser->SchoolDetail->approved == '1') {
                        $addUser->school_live = '1';
                    } else {
                        $addUser->school_live = '0';
                    }
                } else {
                    $addUser->school_live = '0';
                }
                if (isset($classobj)) {
                    $addUser->class_id = $class_code->id;
                    $addUser->class_name = $class_code->class_name;
                    $addUser->school_status = '0';
                } else {
                    $addUser->class_id = '';
                    $addUser->class_name == '';
                    $addUser->school_status = '0';
                }
                /*****get timezone data*******/
                if ($addUser->type_of_schooling == 'school') {
                    if (empty($addUser->timezone_id) || $addUser->timezone_id == '') {
                        //get school timezone
                        $schooldata = School::where('id', $addUser->school_id)->first();
                        $statedata = State::where('id', $schooldata->state_id)->first();
                        $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                        // $single_notification->timezone = $timezone;
                        $addUser->timezone_offset = $timezone->utc_offset;
                        $addUser->timezone_name = $timezone->timezone_name;
                    } else {
                        //get user timezone
                        $timezone = Timezone::where('id', $addUser->timezone_id)->first();
                        // $single_notification->timezone = $timezone;
                        $addUser->timezone_offset = $timezone->utc_offset;
                        $addUser->timezone_name = $timezone->timezone_name;
                    }
                }
                return response()->json(array('error' => false, 'message' => 'Successfully Updated', 'data' => $addUser), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Register Student
    public function ParentRegister(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                'first_name' => 'required',
                //  'device_token' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'type_of_schooling' => 'required',
                'country' => 'required',
                'state_id' => 'required|exists:states,id',
                'city_id' => 'required|exists:cities,id',
                'school_id' => 'required_if:type_of_schooling, =,school'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $student_obj = new User;
                $addUser = $student_obj->store($request);
                $token = JWTAuth::fromUser($addUser);
                $addUser->jwt_token = $token;
                //clascodes
                if (!empty($addUser)) {
                    //User::where('id',$addUser->id)->update(['device_token' => $request->device_token]);
                    if (!empty($request->class_code)) {
                        $class_code = ClassCode::where('class_code', $request->class_code)->first();
                        if (!empty($class_code)) {
                            $classobj = ClassUser::create(
                                ['user_id' => $addUser->id, 'class_id' => $class_code->id]
                            );
                        } else {
                            return response()->json(array('error' => true, 'message' => 'Class code is not valid.'), 200);
                        }
                    }

                    if (isset($request->student_id)) {
                        $explode = explode(',', $request->student_id);
                        foreach ($explode as $single) {

                            DB::table('parent_childrens')->updateOrInsert(
                                [
                                    'parent_id' => $addUser->id,
                                    'children_id' => $single,
                                    'relationship' => $request->relationship
                                ]
                            );
                        }
                    }
                    // Store unapprove Student
                    if ($request->has('full_name')) {
                        $data = [];
                        if ($request->hasfile('documents')) {
                            foreach ($request->file('documents') as $key => $file) {
                                $name = time() . $key . '.' . $file->getClientOriginalExtension();
                                $file->move(public_path() . '/documents/', $name);
                                $data[$key] = URL::to('/') . '/documents/' . $name;
                            }
                        }



                        $unapprove_student = UnapproveStudent::create(['full_name' => $request->full_name, 'parent_id' => $addUser->id, 'school_id' => $request->school_id, 'documents' => $data, 'status' => '0', 'class_code_id' => $request->class_id]);
                    }
                    $addUser = User::with('StateDetail')->with('CityDetail')->with('SchoolDetail')->where('id', $addUser->id)->first();
                    if (isset($classobj)) {
                        $addUser->class_id = $class_code->id;
                        $addUser->class_name = $class_code->class_name;
                    } else {
                        $addUser->class_id = '';
                        $addUser->class_name == '';
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

    public function GetStudents(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'school_id' => 'required',
            'class_code' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        } else {
            DB::enableQueryLog();
            //  $students=User::with('SchoolDetail')

            $students = User::with('SchoolDetail:id,school_name')
                ->leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')
                ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
                ->select('users.*', 'class_code.class_name')->where('role_id', 2)->where('status', 1)->where('class_code.class_code', $request->class_code)->where('users.school_id', $request->school_id)->get();
            return response()->json(array('error' => false, 'message' => 'Students fetched successfully', 'data' => $students), 200);
        }
    }

    public function GetAssociatedParents(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'class_id' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $parents = User::join('user_class_code', 'users.id', '=', 'user_class_code.user_id')->Join('class_code', 'user_class_code.class_id', '=', 'class_code.id')->select(DB::raw('users.id,users.name,users.role_id, class_code.class_name,(SELECT group_concat( distinct u.name)  from parent_childrens
						INNER join users as u On u.id= parent_childrens.children_id  where  parent_id=users.id)  as childrens'))->where('role_id', 3) /* ->where('status', 1) */->where('class_code.id', $request->class_id)->groupBy('users.id')->get();


                return response()->json(array('error' => false, 'message' => 'Students fetched successfully', 'data' => $parents), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function GetAssociatedClasses(Request $request)
    {

        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'parent_id' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {

                $resultsa = ParentChildrens::select(DB::raw('GROUP_CONCAT(children_id) AS children'))->where('parent_id', $request->parent_id)->first();

                $result = DB::select(DB::raw("Select class_code.id,class_code.class_code,class_code.class_name,user_class_code.user_id as student_id from class_code INNER JOIN user_class_code ON class_code .id =user_class_code .class_id WHERE user_id =" . $request->parent_id . " GROUP BY class_code.id "));

                $childrens = $resultsa->children;
                if (!empty($childrens)) {
                    $users = DB::select(DB::raw("Select class_code.id,class_code.class_code,class_code.class_name,user_class_code.user_id as student_id from class_code INNER JOIN user_class_code ON class_code .id =user_class_code .class_id WHERE user_id IN(" . $childrens . ") AND active=1 GROUP BY class_code.id "));
                    if (!empty($result) && !empty($users)) {
                        $collection = collect($result);
                        $mergedCollection = $collection->merge($users);
                        $mergedCollection = $mergedCollection->unique(function ($item) {

                            return $item;
                        });
                        $result = $mergedCollection->all();
                    }
                    if (empty($result) && !empty($users)) {

                        $result = $users;
                    }
                }

                return response()->json(array('error' => false, 'message' => 'Classes fetched successfully', 'data' => $result), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function MyParents(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'student_id' => 'required'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {

                //DB::enableQueryLog(); // Enable query log
                $parents = ParentChildrens::where('children_id', $request->student_id)->with('ParentDetails:id,name,first_name,last_name,role_id')->get();
                foreach ($parents as $single) {
                    $children = User::Join('parent_childrens', 'parent_childrens.children_id', '=', 'users.id')->select('users.id', 'users.name', 'users.role_id')->where('parent_id', $single->parent_id)->distinct()->get();
                    $single->childrens = $children;
                }

                //dd(DB::getQueryLog());
                return response()->json(array('error' => false, 'message' => 'Parents fetched successfully', 'data' => $parents), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function GethomeStudents(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'city_id' => 'required',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        } else {
            DB::enableQueryLog();
            //  $students=User::with('SchoolDetail')

            $students = User::where('role_id', 2)->where('users.type_of_schooling', 'home')->where('users.city', $request->city_id)->get();

            return response()->json(array('error' => false, 'message' => 'Students fetched successfully', 'data' => $students), 200);
        }
    }

    public function AddChildren(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                'type_of_schooling' => 'required',
                'parent_id' => 'required',
                'school_id' => 'required_if:type_of_schooling, =,school',
                //'class_code' => 'required_if:type_of_schooling, =,school',
                // 'student_id' => 'required_if:type_of_schooling, =,school'
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                //clascodes
                if (!empty($request->class_code)) {
                    $class_code = ClassCode::where('class_code', $request->class_code)->first();
                    if (!empty($class_code)) {
                        DB::table('user_class_code')->insert(
                            ['user_id' => $request->parent_id, 'class_id' => $class_code->id]
                        );
                    } else {
                        return response()->json(array('error' => true, 'message' => 'Class code is not valid.'), 200);
                    }
                }

                if (!empty($request->student_id)) {
                    $explode = explode(',', $request->student_id);
                    if (!empty($explode)) {
                        foreach ($explode as $student_id) {
                            DB::table('parent_childrens')->insert(
                                [
                                    'parent_id' => $request->parent_id,
                                    'children_id' => $student_id,
                                    'relationship' => $request->relationship
                                ]
                            );

                            if ($request->has('family_code')) {
                                User::where('id', $student_id)->update(['family_code' => $request->family_code]);
                            }
                        }
                    }

                    $addUser = DB::table('parent_childrens')->where('parent_id', $request->parent_id)->whereIn('children_id', array($request->student_id))->get();

                    User::where('id', $request->parent_id)->update(['type_of_schooling' => $request->type_of_schooling]);
                    if (isset($request->school_id)) {
                        User::where('id', $request->parent_id)->whereNull('school_id')->update(['school_id' => $request->school_id]);
                    }
                }

                $data_document = [];
                // Store unapprove Student
                if ($request->has('full_name')) {

                    if ($request->hasfile('documents')) {
                        foreach ($request->file('documents') as $key => $file) {
                            $name = time() . $key . '.' . $file->getClientOriginalExtension();
                            $file->move(public_path() . '/documents/', $name);
                            $data_document[$key] = URL::to('/') . '/documents/' . $name;
                        }
                    }

                    $addUser = UnapproveStudent::create(['full_name' => $request->full_name, 'parent_id' => $request->parent_id, 'school_id' => $request->school_id, 'documents' => $data_document, 'status' => '0', 'class_code_id' => $request->class_id]);
                }

                return response()->json(array('error' => false, 'data' => $addUser, 'message' => 'Child added successfully.', 'documents' => $data_document), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Create task 
    public function AddScheduleTask(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'task_assigned_by' => 'required|exists:users,id',
            //                    'task_assigned_to' => 'required',
            'task_name' => 'required',
            'schedule_id' => 'required|exists:schedules,id',
            //                  'task_time' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $task = new ParentTask; //then create new object
            $task->task_assigned_by = $request->task_assigned_by;
            $task->task_name = $request->task_name;
            $task->schedule_id = $request->schedule_id;
            $task->task_time = $request->task_time;
            $task->task_description = $request->task_description;
            $data = [];
            if ($request->hasfile('image')) {

                foreach ($request->file('image') as $key => $file) {
                    $name = time() . $key . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path() . '/images/', $name);
                    $data[$key] = URL::to('/') . '/images/' . $name;
                }
            }
            $task->image = $data;
            // $task->notes=$request->notes;
            $days_data = [];
            $dates = [];

            if (!empty($request->selected_days)) {
                $selected_days = explode(",", $request->selected_days);
                foreach ($selected_days as $key => $selected_day) {
                    $dates[] = date("d/m/Y", strtotime($selected_day));
                    // $selected_day = date("d/m/Y", strtotime($selected_day));
                    $days_data[$key] = $selected_day;
                }
            }
            $task->selected_days = $days_data;

            $task->save();
            //get task_assigned_to values
            $taskassignedids = Schedule::where('id', $request->schedule_id)->first();
            if ($taskassignedids->accept_reject_schedule != null) {
                $accept_reject_status = '1';
            } else if ($taskassignedids->rejected_user != null) {
                $accept_reject_status = '2';
            } else {
                $accept_reject_status = '0';
            }

            $dates_implode = implode(',', $dates);

            // $users_explode = explode(',', $request->task_assigned_to);
            $user_data_by = User::where('id', $request->task_assigned_by)->first();
            // foreach ($users_explode as $single) {

            $task_assigned = new ParentTaskAssigned; //then create new object
            $task_assigned->task_id = $task->id;
            //            $task_assigned->task_assigned_to = $request->task_assigned_to; 
            $task_assigned->task_assigned_to = $taskassignedids->assigned_to;
            if ($request->task_assigned_by == $taskassignedids->assigned_to) {
                $task_assigned->handover = '1';
            } else {
                $task_assigned->handover = $taskassignedids->handover;
            }

            $task_assigned->save();

            if ($taskassignedids->handover == '1') {

                $addded = ParentTask::select((DB::raw("( CASE WHEN EXISTS (
							SELECT *
							FROM parent_task_assigned
							WHERE parent_task_assigned.task_id = parent_tasks.id  AND parent_task_assigned.accept_reject = 3
							) THEN TRUE
							ELSE FALSE END)
							AS is_complete,parent_tasks.*")))->with('User')->where('id', $task->id)->first();
                //             $addded->task_assigned_to=$request->task_assigned_to;
                $addded->task_assigned_to = $taskassignedids->assigned_to;
 
                $this->pusher->trigger('task-channel', 'task_add', $addded);

                $user_data_to = User::where('id', $task_assigned->task_assigned_to)->first();
                //email notification 



                $message = 'A new task has been assigned to you in this schedule \'' . $taskassignedids->schedule_name . '\'';
                if (!empty($user_data_to->device_token)) {
                    SendAllNotification($user_data_to->device_token, $message, 'social_notification', $task->id, 'add_task', null, null, null, null, null, $accept_reject_status);
                }
                $notificationobj = new Notification;
                $notificationobj->user_id = $user_data_to->id;
                $notificationobj->notification_message = $message;
                $notificationobj->notification_type = 'task_assign';
                $notificationobj->task_id = $task->id;
                $notificationobj->schedule_id = $task->schedule_id;
                $notificationobj->type = 'social_notification';
                $notificationobj->push_type = 'add_task';
                $notificationobj->from_user_id = $user_data_by->id;
                $notificationobj->save();

                $notificationobj->role_type = 'all';
                

                $this->pusher->trigger('notification-channel', 'notification_all_read', $notificationobj);
                
                $data = array(
                    'name' => $user_data_to->name,
                    'email' => $user_data_to->email,
                    'task_creator' => $user_data_by->name,
                    'task_name' => $request->task_name,
                    'task_date' => $days_data,
                    'task_time' => $request->task_time,
                    'task_description' => $request->task_description,
                );
                Mail::send("email.assigned-task", $data, function ($m) use ($user_data_to) {
                    $m->from('involvvely@gmail.com', 'Involvvely');
                    $m->to($user_data_to->email);
                    $m->subject('Assigned Task');
                }); 
            }
            //}
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        }
    }

    public function AddSchedule(Request $request)
    { 
        $input = $request->all();
        $validator = Validator::make($input, [
            'created_by' => 'required|exists:users,id',
            'assigned_to' => 'required',
            'schedule_name' => 'required',
            'from_time' => 'required',
            'to_time' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else { 
            //foreach ($selected_days as $key => $selected_day) {
            // 
            /*  $contain= Schedule::whereRaw('JSON_CONTAINS(selected_days,\'["'.$selected_day.'"]\')')->where('from_time',$request->from_time)->where('to_time',$request->to_time)->where('created_by',$request->created_by)->count();
            if($contain > 0){
            return response()->json(array('error' => true, 'message' =>'You have already selected this date and time'), 200);
            } */
            // }
            // Schedule::whereJsonContains('selected_days', 1)
            $task = new Schedule; //then create new object
            $task->created_by = $request->created_by;
            $task->schedule_name = $request->schedule_name;
            $task->from_time = $request->from_time;
            $task->to_time = $request->to_time;
            $task->assigned_to = $request->assigned_to;
            $task->description = $request->description;
            if ($request->created_by == $request->assigned_to) {
                $task->handover = '1';
            } else {
                $task->handover = '0';
            }
            $days_data = [];

            if (!empty($request->selected_days)) {
                $selected_days = explode(",", $request->selected_days);
                foreach ($selected_days as $key => $selected_day) {
                    //  $selected_day = date("d/m/Y", strtotime($selected_day));
                    $days_data[$key] = $selected_day;
                }
            }
            $task->selected_days = $days_data;
            $task->save();

            //   $user= User::whereIn('id', explode(',',$task->assigned_to))->select('name','id')->get(); 
            $user = [];
            $user = User::where('id', $task->assigned_to)->select('name', 'id', 'timezone_id', 'school_id', 'type_of_schooling')->get();
            $task->assigned_to = $user;
            foreach ($user as $val) {
                if ($val->type_of_schooling == 'school') {
                    if (empty($val->timezone_id) || $val->timezone_id == '') {
                        //get school timezone
                        $schooldata = School::where('id', $val->school_id)->first();
                        $statedata = State::where('id', $schooldata->state_id)->first();
                        $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                        // $single_notification->timezone = $timezone;
                        $val->timezone_offset = $timezone->utc_offset;
                        $val->timezone_name = $timezone->timezone_name;
                    } else {
                        //get user timezone
                        $timezone = Timezone::where('id', $val->timezone_id)->first();
                        // $single_notification->timezone = $timezone;
                        $val->timezone_offset = $timezone->utc_offset;
                        $val->timezone_name = $timezone->timezone_name;
                    }
                }
            }

            $task->User;
            if ($task->handover == '1') {
                $task->is_accept = '0';

                $this->pusher->trigger('schedule-channel', 'schedule_user', $task);
            }

            $user_data_by = User::where('id', $request->created_by)->first();



            //            $users_explode = explode(',', $request->assigned_to);
            //            foreach ($users_explode as $single) {   
            $user_data_to = User::where('id', $request->assigned_to)->first();
            //email notification             
            if ($request->notify_parent == '1') {
                $notify_date = date('d/m/Y', strtotime($request->notify_date));
                //Good News! Your schedule |schedule name here| is set for Saturday , January 4th, 2018 to Sunday, January 5th, 2018.
                $message = 'Good News! Your schedule ' . $task->schedule_name . ' is set for ' . $notify_date . ' at ' . $request->notify_time;
                if (!empty($user_data_to->device_token)) {
                    SendAllNotification($user_data_to->device_token, $message, 'social_notification');
                }
                $notificationobj = new Notification;
                $notificationobj->user_id = $user_data_to->id;
                $notificationobj->notification_message = $message;
                $notificationobj->notification_type = 'schedule_assign';
                $notificationobj->type = 'social_notification';
                $notificationobj->from_user_id = $user_data_by->id;
                $notificationobj->schedule_id = $task->id;
                $notificationobj->save();

                $notificationobj->role_type = 'all';
                $this->pusher->trigger('notification-channel', 'notification_all_read', $notificationobj);
                $data = array(
                    'name' => $user_data_to->name,
                    'email' => $user_data_to->email,
                    'task_creator' => $user_data_by->name,
                    'task_name' => $request->schedule_name,
                    'task_date' => $days_data,
                    'from_time' => $request->from_time,
                    'to_time' => $request->to_time,
                    'task_description' => $request->description,
                );
                Mail::send("email.assigned-schedule", $data, function ($m) use ($user_data_to) {
                    $m->from('involvvely@gmail.com', 'Involvvely');
                    $m->to($user_data_to->email);
                    $m->subject('Assigned Task');
                });
            }
            return response()->json(array('error' => false, 'message' => 'Success', 'data' => $task), 200);
        }
    }


    //Get tasks
    public function Getschedules(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            return $tasks = Schedule::
            select((DB::raw("(SELECT CASE 
                        WHEN FIND_IN_SET(" . $request->user_id . " ,schedules.accept_reject_schedule ) THEN 1
						WHEN FIND_IN_SET(" . $request->user_id . ", schedules.rejected_user) THEN 2
						ELSE 0
						END
						)
						AS is_accept,schedules.*")))->with('User')->whereRaw('( (FIND_IN_SET(' . $request->user_id . ', assigned_to ) AND  handover=1)  OR  created_by=' . $request->user_id . ')  AND  ( FIND_IN_SET(' . $request->user_id . ' ,rejected_user) IS NULL OR  created_by=' . $request->user_id . ')')->orderBy('id', 'DESC')->get();
            // $tasks = Schedule::select((DB::raw("(SELECT CASE
            // 			WHEN  FIND_IN_SET(" . $request->user_id . " ,schedules.accept_reject_schedule ) THEN 1

            // 			WHEN FIND_IN_SET(" . $request->user_id . ", schedules.rejected_user) THEN 2
            // 			ELSE 0
            // 			END
            // 			)
            // 			AS is_accept,schedules.*")))->with('User')->whereRaw('( (FIND_IN_SET(' . $request->user_id . ', assigned_to ) AND  handover=1)  OR  created_by=' . $request->user_id . ')  AND  ( FIND_IN_SET(' . $request->user_id . ' ,rejected_user) IS NULL )')->orderBy('id', 'DESC')->get();
            foreach ($tasks as $singlke_task) {
                $user = User::whereIn('id', explode(',', $singlke_task->assigned_to))->select('name', 'id', 'timezone_id', 'school_id', 'type_of_schooling')->get();
                $singlke_task->assigned_to = $user;
                foreach ($user as $users) {
                    if ($users->type_of_schooling == 'school') {
                        if ($users->timezone_id == null || $users->timezone_id == '') {
                            //get school timezone
                            $schooldata = School::where('id', $users->school_id)->first();
                            $statedata = State::where('id', $schooldata->state_id)->first();
                            $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                            // $users->timezone = $timezone;
                            $users->timezone_offset = $timezone->utc_offset;
                            $users->timezone_name = $timezone->timezone_name;
                        } else {
                            //get user timezone
                            $timezone = Timezone::where('id', $users->timezone_id)->first();
                            // $users->timezone = $timezone;
                            $users->timezone_offset = $timezone->utc_offset;
                            $users->timezone_name = $timezone->timezone_name;
                        }
                    }
                }
            }



            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }



    //Get tasks
    public function GetScheduleTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $tasks = ParentTask::
            select((DB::raw("( CASE WHEN EXISTS ( SELECT * FROM parent_task_assigned
			WHERE parent_task_assigned.task_id = parent_tasks.id  AND parent_task_assigned.accept_reject = 3) THEN TRUE
			ELSE FALSE END) AS is_complete,parent_tasks.*")))->with('User')->whereRaw('( id IN (SELECT task_id
			FROM parent_task_assigned WHERE parent_task_assigned.task_id = parent_tasks.id  AND parent_task_assigned.handover = 1 AND task_assigned_to=' . $request->user_id . ')  OR  task_assigned_by=' . $request->user_id . ')')->where('schedule_id', $request->schedule_id)->orderBy('id', 'DESC')->get();

            foreach ($tasks as $signle_user) {
                //check task schedule is accepted or not 
                $schedule_status = Schedule::where('id', $signle_user->schedule_id)->first();
                if ($schedule_status->rejected_user != null) {
                    $signle_user->is_complete = 2;
                }
            }
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }



    //Get tasks assigned to me 
    public function TaskAssignedToMe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $tasks = ParentTaskAssigned::with('User', 'ParentTask')->where('task_assigned_to', $request->user_id)->orderBy('id', 'DESC')->get();
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }

    //task detail
      public function GetScheduleTaskDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:parent_tasks,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $accept_reject_data = ParentTaskAssigned::with('User', 'ParentTask.User')->where(['task_id' => $request->task_id, 'task_assigned_to' => $request->parent_id])->first();
            //           $accept_reject_data= ParentTaskAssigned::with('User','ParentTask.User')->where(['task_id'=>$request->task_id ,'task_assigned_to'=>$request->parent_id, 'handover'=>'1'])->first();
            if (!empty($accept_reject_data)) {

                if ($request->accept_reject == 1) {
                    $message = $accept_reject_data->User->name . ' has accepted the task ' . $accept_reject_data->ParentTask->task_name;
                } else {
                    $message = $accept_reject_data->User->name . ' has rejected the task ' . $accept_reject_data->ParentTask->task_name;
                }
                // SElect parent_tasks.*, (CASE WHEN (Select Count(id) from parent_task_assigned where task_id=parent_tasks.id AND (parent_task_assigned.accept_reject=1 OR parent_task_assigned.accept_reject=0)) > 0  THEN parent_task_assigned.accept_reject ELSE '2' END )   from parent_tasks 
            } else {
            }
            $tasks = ParentTask::select((DB::raw("( CASE WHEN EXISTS (
							SELECT *
							FROM parent_task_assigned
							WHERE parent_task_assigned.task_id = parent_tasks.id
							 AND parent_task_assigned.accept_reject = 3
							) THEN TRUE
							ELSE FALSE END)
							AS is_complete,parent_tasks.*")))->with('User:id,name')->where('id', $request->task_id)->get();
            foreach ($tasks as $signle_user) {
                $task_user = ParentTaskAssigned::select('image', 'notes', 'task_assigned_to')->with('User:id,name')->where('task_id', $request->task_id)->first();
                $signle_user->assigned_to = $task_user;

                //check task schedule is accepted or not 
                $schedule_status = Schedule::where('id', $signle_user->schedule_id)->first();
                if ($schedule_status->rejected_user != null) {
                    $signle_user->is_complete = 2;
                }
            }






            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);

            return response()->json(array('error' => false, 'message' => 'this task is pending', 'data' => []), 200);
        }
    }

    //Schedule Detail
    public function GetScheduleDetail(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            //GET ASSIGNED USER_ID
            $assigned_id = Schedule::where('id', $request->schedule_id)->first();
            $tasks = Schedule::select((DB::raw("(SELECT CASE
						WHEN  FIND_IN_SET(" . $assigned_id->assigned_to . " ,schedules.accept_reject_schedule ) THEN 1
						WHEN FIND_IN_SET(" . $assigned_id->assigned_to . ", schedules.rejected_user) THEN 2
						ELSE 0
						END
						)
						AS is_accept,schedules.*")))->with('User:id,name')->where('id', $request->schedule_id)->first();

            // $tasks = Schedule::select((DB::raw("(SELECT CASE
            // 			WHEN  FIND_IN_SET(" . $request->user_id . " ,schedules.accept_reject_schedule ) THEN 1
            // 			WHEN FIND_IN_SET(" . $request->user_id . ", schedules.rejected_user) THEN 2
            // 			ELSE 0
            // 			END
            // 			)
            // 			AS is_accept,schedules.*")))->with('User:id,name')->where('id', $request->schedule_id)->first();
            $user = User::whereIn('id', explode(',', $tasks->assigned_to))->select('name')->get();
            $tasks->assigned_to = $user;

            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);

            return response()->json(array('error' => false, 'message' => 'this task is pending', 'data' => []), 200);
        }
    }

    //remove task
    public function RemoveScheduleTask(Request $request) {

        $validator = Validator::make($request->all(), [
                    'task_id' => 'required|exists:parent_tasks,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $task_first = ParentTask::where('id', $request->task_id)->first();

            $delete = ParentTask::where('id', $request->task_id)->delete();
            $delete_assigned = ParentTaskAssigned::where('task_id', $request->task_id)->delete();
            $this->pusher->trigger('remove-channel', 'remove_task', $task_first);
            if ($delete) {
                return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    //remove task
    public function RemoveSchedule(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'user_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $schedule = Schedule::where('id', $request->schedule_id)->first();
            $delete = Schedule::where('id', $request->schedule_id)->where('created_by', $request->user_id)->delete();
            $this->pusher->trigger('delete-schedule', 'delete_schedule', $schedule);
            if ($delete) {
                return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    //remove task
    public function RemoveAssignScheduleTask(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:parent_tasks,id',
            'task_assigned_to' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $delete_assigned = ParentTaskAssigned::where('task_id', $request->task_id)->where('task_assigned_to', $request->task_assigned_to)->delete();
            if ($delete_assigned) {
                return response()->json(array('error' => false, 'message' => 'Removed successfully', 'data' => []), 200);
            } else {
                return response()->json(array('error' => true, 'message' => 'something wrong occured', 'data' => []), 200);
            }
        }
    }

    public function GetListofParents(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                'school_id' => 'required|exists:schools,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {

                $users = DB::select(DB::raw("SELECt id,name,profile_image,(SELECT group_concat(u.name) as childrens from parent_childrens
						INNER join users as u On u.id= parent_childrens.children_id  where  parent_id=users.id)  as childrens from users where role_id=3  and school_id=" . $request->school_id));
                if (!empty($users)) {

                    return response()->json(array('error' => false, 'message' => 'record found', 'data' => $users), 200);
                } else {
                    throw new Exception('No another parents');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function GetListofStudents(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                'school_id' => 'required|exists:schools,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {

                $users = User::where('school_id', $request->school_id)->where('role_id', 2)->where('status', 1)->get();

                if (!empty($users)) {

                    return response()->json(array('error' => false, 'message' => 'record found', 'data' => $users), 200);
                } else {
                    throw new Exception('No Record found');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function GetListofTeachers(Request $request)
    {

        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'school_id' => 'required|exists:schools,id',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                // $teachers = AssignedTeacher::with('User')->with('Subjects')->where('class_id', $request->class_id)->where('school_id', $request->school_id)->get();

                $users = User::where('school_id', $request->school_id)->where('role_id', 4)->where('status', 1)->select(DB::raw('users.id,users.name,user.profile_image,(SELECT group_concat( distinct class_name)  from class_code
						INNER join assigned_teachers as u On u.class_id= class_code.id  where  teacher_id=users.id)  as classes,(SELECT group_concat( distinct subject_name)  from subjects
						INNER join assigned_teachers as u On u.subject_id= subjects.id  where  teacher_id=users.id)  as subjects'))->get();


                if (!empty($users)) {

                    return response()->json(array('error' => false, 'message' => 'record found', 'data' => $users), 200);
                } else {
                    throw new Exception('No Record found');
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Teacher list for Hiring from Parent
    public function GetTeacherList(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                // 'school_id' => 'required',
                'selected_class' => 'exists:class_code,class_name',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $classes = ClassCode::select('id', 'class_name')->groupBy('class_name')->havingRaw('count(*) > 1')->get();

                $location = Cities::select('county', 'id')->groupBy('county')->havingRaw('count(*) > 1')->get();

                $subjects = Subject::select('id', 'subject_name');
                if (strpos($request->selected_class, 'Grade') !== false) {
                    $subjects->where('subject_name', 'not like', 'General' . '%');
                } else {
                    $subjects->where('subject_name', 'like', '%' . $request->selected_class);
                }

                $subjects = $subjects->get();
                $data = array('classes' => $classes, 'subjects' => $subjects, 'location' => $location);
                return response()->json(array('error' => false, 'data' => $data, 'message' => 'Record fetched Success!'), 200);
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }


        try {
            $classes = ClassCode::select('id', 'class_name')->where('school_id', $request->school_id)->get();

            $location = Cities::select('county', 'id')->groupBy('county')->havingRaw('count(*) > 1')->get();
            $subjects = Subject::select('subject_name', 'id')->groupBy('subject_name')->get();
            $data = array(
                [
                    'classes' => $classes,
                    'location' => $location,
                    'subjects' => $subjects,
                ]
            );
            if (!empty($data)) {

                return response()->json(array('error' => false, 'message' => 'record found', 'data' => $data), 200);
            } else {
                throw new Exception('No Record found');
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Hire Teacher Request List
    public function HireTeacherList(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'parent_id' => 'required|exists:users,id',
                'availabilty' => 'nullable|string',
                'location' => 'nullable|string|exists:teaching_program,location',
                'subject' => 'nullable|integer|exists:user_subjects,subject_id',
                'class' => 'nullable|string|exists:class_code,class_name',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $users =
                    User::leftJoin('user_subjects', 'user_subjects.user_id', '=', 'users.id')
                        ->leftJoin('subjects', 'subjects.id', '=', 'user_subjects.subject_id')

                        ->leftJoin('user_class', 'user_class.user_id', '=', 'users.id')
                        ->leftJoin('class_code', 'class_code.id', '=', 'user_class.class_id')

                        ->leftJoin('teaching_program', 'teaching_program.user_id', '=', 'users.id')


                        ->select(DB::raw('(select request_status from teachin_program_requests WHERE teachin_program_requests.from_user = ' . $request->parent_id . ' &&  teachin_program_requests.to_user = teaching_program.user_id) as request_status, users.id, users.name, class_code.class_name, teaching_program.*,users.profile_image'))

                        ->selectRaw('GROUP_CONCAT(DISTINCT subjects.subject_name) as subject_names')
                        ->selectRaw('GROUP_CONCAT(DISTINCT class_code.class_name) as class_names')

                        ->whereNotNull('user_subjects.subject_id');
                if ($request->subject) {
                    $users = $users->where('user_subjects.subject_id', $request->subject);
                }
                if ($request->class) {
                    $users = $users->where('class_code.class_name', $request->class);
                }
                if ($request->location) {
                    $users = $users->where('teaching_program.location', $request->location);
                }
                if ($request->availabilty) {
                    $users = $users->where('teaching_program.availability', $request->availabilty);
                }
                $users = $users->groupBy('users.id')->get();
            }
            if (!empty($users)) {

                return response()->json(array('error' => false, 'message' => 'record found', 'data' => $users), 200);
            } else {
                throw new Exception('No Record found');
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Hire Teacher Request List
    public function HiredTeacherList(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required|integer|exists:teachin_program_requests,from_user',
                // 'request_status' => 'required|in:2'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                if (!empty($input)) {

                    // $data['request_type'] = $request->request_type;

                    $users = TeachingProgramReq::where('from_user', $request->id)
                        ->where('request_status', '2')
                        ->leftJoin('users', 'users.id', '=', 'teachin_program_requests.to_user')
                        ->leftJoin('teaching_program', 'teaching_program.user_id', '=', 'users.id')
                        ->select(DB::raw('(select GROUP_CONCAT(DISTINCT subjects.subject_name) AS subject_pr from user_subjects inner join subjects ON          user_subjects.subject_id=subjects.id WHERE user_subjects.user_id= teaching_program.user_id) as subjects_names,
                         (select GROUP_CONCAT(DISTINCT class_code.class_name) AS class_name from user_class inner join class_code ON user_class.class_id=class_code.id WHERE user_class.user_id= teaching_program.user_id) as class_name,request_status,
                         availability,hourly_rate, location,preferences,users.name,users.profile_image,teaching_program.user_id
                        '))->get()->sortByDesc('teachin_program_requests.id');

                    // $users = TeachingProgramReq::fetchList($data);

                    if (!empty($users)) {
                        return response()->json(['message' => 'data fetched successfully!', 'data' => $users], 200);
                    } else {
                        throw new Exception('List empty!');
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }


    }
    // API- Make a Request for Hire Teacher
    public function PlaceTeacherReq(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required|integer|exists:users,id',
                'from_user' => 'required|integer|exists:users,id',
                'request_status' => 'required|integer|in:0,1,2',
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
                        return response()->json(['error' => false, 'message' => 'success', 'data' => $users], 200);
                    } else {
                        throw new Exception('No Record found');
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    // API- Make a Request for Hire Teacher
    public function TeacherReqList(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required|integer',
                'request_type' => 'required'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                if (!empty($input)) {
                    $data['id'] = $request->id;
                    $data['req_satus'] = '1';
                    $data['request_type'] = $request->request_type;
                    $users = TeachingProgramReq::fetchList($data);

                    if (!empty($users)) {
                        return response()->json(['message' => 'data fetched successfully!', 'data' => $users], 200);
                    } else {
                        throw new Exception('List empty!');
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    // Ongoing  List
    public function ongoingList(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required|integer',
                'request_type' => 'required'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                if (!empty($input)) {
                    $data['id'] = $request->id;
                    $data['req_satus'] = '2';
                    $data['request_type'] = $request->request_type;
                    $users = TeachingProgramReq::fetchList($data);

                    if (!empty($users)) {
                        return response()->json(['message' => 'data fetched successfully!', 'data' => $users], 200);
                    } else {
                        throw new Exception('List empty!');
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }
    // API- Make a Request for Hire Teacher
    public function AcceptRequest(Request $request)
    {
        try {
            // return "Here";
            $input = $request->all();
            $validator = Validator::make($input, [
                'id' => 'required|integer',
                'from_user' => 'required|integer',
                'request_status' => 'required|integer|in:0,1,2',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                if (!empty($input)) {
                    $data['id'] = $request->id;
                    $data['from_user'] = $request->from_user;
                    $data['request_status'] = $request->request_status;
                    $users = TeachingProgramReq::requestStatus($data);
                    if ($request->request_status == 0) {
                        $message = "Request has been removed";
                    }
                    if ($request->request_status == 2) {
                        $message = "Request has been Accepted";
                    }

                    if (!empty($users)) {
                        return response()->json(['message' => $message, 'data' => $users], 200);
                    } else {
                        throw new Exception('List empty!');
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function GetRelatedParents(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                'parent_id' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                $resultsa = ParentChildrens::select(DB::raw('GROUP_CONCAT(children_id) AS children,(SELECT group_concat( DISTINCT  u.name)  from parent_childrens
						INNER join users as u On u.id= parent_childrens.children_id  where  parent_id=' . $request->parent_id . ')  as childrens'))->where('parent_id', $request->parent_id)->first();
                $childrens = $resultsa->childrens;

                // if(!empty($childrens)){
                //$results= ParentChildrens::select(DB::raw('DISTINCT parent_id'))->with('ParentDetails:id,name,first_name,last_name')->whereRaw('children_id IN('.$childrens.')')->get();
                $family_code = User::find($request->parent_id);
                $results = User::whereRaw('family_code =\'' . $family_code->family_code . '\' AND role_id = 3')->select('id', 'name', 'last_name', 'role_id', 'school_id', 'timezone_id', 'type_of_schooling')->get();
                if (!empty($results)) {
                    foreach ($results as $single) {

                        $single->childrens = $childrens;
                        if ($single->type_of_schooling == 'school') {
                            if ($single->timezone_id == null || $single->timezone_id == '') {
                                //get school timezone
                                $schooldata = School::where('id', $single->school_id)->first();
                                $statedata = State::where('id', $schooldata->state_id)->first();
                                $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                                $single->timezone_offset = $timezone->utc_offset;
                                $single->timezone_name = $timezone->timezone_name;
                            } else {
                                //get user timezone
                                $timezone = Timezone::where('id', $single->timezone_id)->first();
                                $single->timezone_offset = $timezone->utc_offset;
                                $single->timezone_name = $timezone->timezone_name;
                            }
                        }
                    }
                    return response()->json(array('error' => false, 'data' => $results, 'message' => 'Parents fetched successfully.'), 200);
                } else {
                    throw new Exception('No another parents');
                }
                // }
                /* else{
                throw new Exception('No children');
                } */
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function GetFamily(Request $request)
    {
        try {

            $input = $request->all();
            $validator = Validator::make($input, [
                'parent_id' => 'required',
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {
                //$resultsa= ParentChildrens::select( DB::raw('GROUP_CONCAT(children_id) AS children'))->where('parent_id',$request->parent_id)->first();
                //$childrens= $resultsa->children;
                //  if(!empty($childrens)){
                //         $results= ParentChildrens::select(DB::raw('DISTINCT parent_id'))->with('ParentDetails')->whereRaw('children_id IN('.$childrens.')')->where('parent_id','!=',$request->parent_id)->get();
                // $results= ParentChildrens::select(DB::raw('DISTINCT parent_id'))->with('ParentDetails:id,name,first_name,last_name,role_id')->whereRaw('children_id IN('.$childrens.')')->where('parent_id','!=',$request->parent_id)->get();
                $id = $request->parent_id;
                $results = DB::select(DB::raw("select id,name,role_id,relationship FROM users where family_code='" . $request->family_code . "'  AND role_id=3  ORDER BY FIELD(id, " . $id . ") DESC "));

                // $results= User::whereRaw('family_code ='.$request->family_code.' AND role_id = 3')->select('id','name','last_name','role_id')->orderBy(DB::raw("FIELD(id, $id)"))->orderBy('id', 'DESC')->get();

                $children = User::with('SchoolDetail:id,school_name')->whereRaw('family_code ="' . $request->family_code . '" AND role_id = 2')->select('id', 'name', 'last_name', 'role_id', 'school_id', 'type_of_schooling', DB::raw('(select active as school_status from user_class_code where user_id=users.id limit 1) as school_status'))->get();


                return response()->json(array('error' => false, 'data' => $results, 'childrens' => $children, 'message' => 'Family listed successfully'), 200);


                // }
                /* else{
                throw new Exception('No children');
                } */
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage()), 200);
        }
    }

    public function AcceptRejectTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_id' => 'required|exists:parent_tasks,id',
            // 'accept_reject' => 'required',
            'parent_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {

            $data = [];
            if ($request->hasfile('image')) {

                foreach ($request->file('image') as $key => $file) {
                    $name = time() . $key . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path() . '/images/', $name);
                    $data[$key] = URL::to('/') . '/images/' . $name;
                }
            }

            $task_schedule = ParentTask::find($request->task_id);


            ParentTaskAssigned::where(['task_id' => $request->task_id, 'task_assigned_to' => $request->parent_id])->update(['accept_reject' => '3', 'image' => $data, 'notes' => $request->notes]);
            $accept_reject_data = ParentTaskAssigned::with('User', 'ParentTask.User')->where(['task_id' => $request->task_id, 'task_assigned_to' => $request->parent_id])->first();
            if (!empty($accept_reject_data)) {

                $obj = new ParentController();
                $obj->task_id = $request->task_id;
                $obj->is_complete = '1';
                $obj->schedule_id = $task_schedule->schedule_id;
                $this->pusher->trigger('task-channel', 'complete_task', $obj);

                $message = $accept_reject_data->User->name . ' has completed the task ' . $accept_reject_data->ParentTask->task_name;
                if (!empty($accept_reject_data->ParentTask->User->device_token)) {
                    SendAllNotification($accept_reject_data->ParentTask->User->device_token, $message, 'social_notification', null, 'complete_task', null, null, null, null, $request->task_id);
                }
                $notificationobj = new Notification;
                $notificationobj->user_id = $accept_reject_data->ParentTask->User->id;
                $notificationobj->notification_message = $message;
                $notificationobj->notification_type = 'complete_task';
                $notificationobj->type = 'social_notification';
                $notificationobj->from_user_id = $accept_reject_data->User->id;
                $notificationobj->task_id = $accept_reject_data->task_id;
                $notificationobj->schedule_id = $task_schedule->schedule_id;
                $notificationobj->save();
                $notificationobj->role_type = 'all';
                $this->pusher->trigger('notification-channel', 'notification_all_read', $notificationobj);
            } else {
                $accept_reject_data = new ParentController();
            }



            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $accept_reject_data), 200);
        }
    }

    public function SendAcceptNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {

            $scheduke = Schedule::find($request->schedule_id);
            $scheduke->handover = '1';
            $scheduke->rejected_user = null;
            $scheduke->save();


            $tasks = ParentTask::with('AssignedUser')->where('schedule_id', $request->schedule_id)->first();
            $task_data = ParentTask::with('AssignedUser')->where('schedule_id', $request->schedule_id)->get();
            foreach ($task_data as $task_datas) {
                ParentTaskAssigned::where('task_id', $task_datas->id)->update(['handover' => '1']);
            }
            // ParentTaskAssigned::where('task_id', $tasks->id)->update(['handover' => '1']);
            $user = User::where('id', $tasks->AssignedUser->task_assigned_to)->select('name', 'id', 'device_token', 'timezone_id', 'school_id', 'type_of_schooling')->get();


            $notificationobj = new Notification;
            $notificationobj->user_id = $tasks->AssignedUser->task_assigned_to;
            $notificationobj->notification_message = 'A Schedule has been handed over to you';
            $notificationobj->notification_type = 'task_assign';
            $notificationobj->type = 'social_notification';
            $notificationobj->from_user_id = $tasks->task_assigned_by;
            $notificationobj->task_id = $tasks->id;
            $notificationobj->schedule_id = $tasks->schedule_id;
            $notificationobj->push_type = 'handover';
            $notificationobj->save();

            $scheduke->User;
            $scheduke->assigned_to = $user;
            foreach ($user as $users) {
                if ($users->type_of_schooling == 'school') {
                    if ($users->timezone_id == null || $users->timezone_id == '') {
                        //get school timezone
                        $schooldata = School::where('id', $users->school_id)->first();
                        $statedata = State::where('id', $schooldata->state_id)->first();
                        $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                        $users->timezone_offset = $timezone->utc_offset;
                        $users->timezone_name = $timezone->timezone_name;
                        $tasks->timezone_offset = $timezone->utc_offset;
                        $tasks->timezone_name = $timezone->timezone_name;
                    } else {
                        //get user timezone
                        $timezone = Timezone::where('id', $users->timezone_id)->first();
                        $users->timezone_offset = $timezone->utc_offset;
                        $users->timezone_name = $timezone->timezone_name;
                        $tasks->timezone_offset = $timezone->utc_offset;
                        $tasks->timezone_name = $timezone->timezone_name;
                    }
                }
            }
            $scheduke->is_accept = '0';
            if (!empty($user[0]->device_token)) {

                SendAllNotification($user[0]->device_token, 'A Schedule has been handed over to you.', 'social_notification', $scheduke, 'handover');
            }
            $this->pusher->trigger('schedule-channel', 'schedule_user', $scheduke);
            /* foreach($tasks  as $single_task){
            ParentTaskAssigned::where('task_id',$single_task->id)->update(['handover' => '1']);
            $task_assigned= ParentTaskAssigned::with('AssignedTo')->where('task_id',$single_task->id)->get();
            if(!empty($task_assigned)){
            foreach($task_assigned as $single_task_to){
            if (!empty($single_task_to->AssignedTo->device_token)) {
            SendAllNotification($single_task_to->AssignedTo->device_token, 'A task has been assigned to you.', 'school_notification');
            }
            $notificationobj=new Notification;
            $notificationobj->user_id=$single_task_to->task_assigned_to;
            $notificationobj->notification_message='A task has been assigned to you.';
            $notificationobj->notification_type='task_assign';
            $notificationobj->type='school_notification';
            $notificationobj->from_user_id=$single_task->task_assigned_by;
            $notificationobj->task_id=$single_task->id;
            $notificationobj->schedule_id=$single_task->schedule_id;
            $notificationobj->save();
            }
            }
            } */
            return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $tasks), 200);
        }
    }

    public function AcceptRejectSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:schedules,id',
            'accept_reject' => 'required',
            'parent_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(array('error' => true, 'message' => $validator->errors()->first()), 200);
        } else {
            $schedule = Schedule::with('User')->find($request->schedule_id);
            $user = User::find($request->parent_id);
            $users = User::where('id', $request->parent_id)->get();
            if (!empty($schedule)) {
                if ($request->accept_reject == 1) {
                    $type = 'accepted';
                    if (!empty($schedule->accept_reject_schedule)) {
                        $schedule->accept_reject_schedule = $schedule->accept_reject_schedule . ',' . $request->parent_id;
                    } else {
                        $schedule->accept_reject_schedule = $request->parent_id;
                    }

                    $message = $user->name . ' has accepted the schedule ' . $schedule->schedule_name;
                }
                if ($request->accept_reject == 2) {
                    $type = 'rejected';
                    if (!empty($schedule->rejected_user)) {
                        $schedule->rejected_user = $schedule->rejected_user . ',' . $request->parent_id;
                    } else {
                        $schedule->rejected_user = $request->parent_id;
                    }

                    $schedule->handover = '0';



                    $message = $user->name . ' has rejected the schedule ' . $schedule->schedule_name;
                }

                $schedule->save();
                $schedule->assigned_to = $users;
                foreach ($users as $user) {
                    if ($user->type_of_schooling == 'school') {
                        if ($user->timezone_id == null || $user->timezone_id == '') {
                            //get school timezone
                            $schooldata = School::where('id', $user->school_id)->first();
                            $statedata = State::where('id', $schooldata->state_id)->first();
                            $timezone = Timezone::where('id', $statedata->timezone_id)->first();
                            $user->timezone_offset = $timezone->utc_offset;
                            $user->timezone_name = $timezone->timezone_name;
                        } else {
                            //get user timezone
                            $timezone = Timezone::where('id', $user->timezone_id)->first();
                            $user->timezone_offset = $timezone->utc_offset;
                            $user->timezone_name = $timezone->timezone_name;
                        }
                    }
                }

                if ($request->accept_reject == 2) {
                    $this->pusher->trigger('decline-channel', 'decline_schedule', $schedule);
                }
                if (!empty($schedule->User->device_token)) {
                    SendAllNotification($schedule->User->device_token, $message, 'social_notification', $schedule, $type);
                }


                $notificationobj = new Notification;
                $notificationobj->user_id = $schedule->created_by;
                $notificationobj->notification_message = $message;
                $notificationobj->notification_type = 'accept_reject';
                $notificationobj->type = 'social_notification';
                $notificationobj->from_user_id = $user->id;
                $notificationobj->schedule_id = $schedule->id;
                $notificationobj->push_type = $type;
                $notificationobj->save();

                $notificationobj->role_type = 'all';
                $this->pusher->trigger('notification-channel', 'notification_all_read', $notificationobj);



                return response()->json(array('error' => false, 'message' => 'Record found', 'data' => $schedule), 200);
            }
        }
    }

    public function ConnectWithSchool(Request $request)
    {
        try {
            $input = $request->all();
            $validator = Validator::make($input, [
                'user_id' => 'required',
                'school_id' => 'required',
                'class_code' => 'required',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            } else {


                if (!empty($request->class_code)) {
                    $class_code = ClassCode::where('class_code', $request->class_code)->where('school_id', $request->school_id)->first();
                    if (!empty($class_code)) {
                        $count = ClassUser::where('user_id', $request->user_id)->where('class_id', $class_code->id)->count();
                        if ($count >= 1) {
                            DB::table('user_class_code')->where('user_id', $request->user_id)->where('class_id', $class_code->id)->update(['active' => '1']);


                            User::where('id', $request->user_id)->update(['school_id' => $request->school_id]);
                            if ($request->role_id == '2') {
                                $parents = ParentChildrens::where('children_id', $request->user_id)->get();
                                foreach ($parents as $singl) {
                                    DB::table('user_class_code')->where('user_id', $singl->parent_id)->where('class_id', $class_code->id)->update(['class_id' => $class_code->id, 'active' => '1']);
                                }
                            }
                        } else {
                            return response()->json(array('error' => true, 'message' => 'This class code is not according to your selected class.'), 200);
                        }
                    } else {
                        return response()->json(array('error' => true, 'message' => 'Class code is not valid.'), 200);
                    }

                    return response()->json(array('error' => false, 'data' => $class_code, 'message' => 'Updated Successfully'), 200);
                }
            }
        } catch (\Exception $e) {
            return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data' => []), 200);
        }
    }

    function random_strings($length_of_string)
    {

        // String of all alphanumeric character 
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

        // Shufle the $str_result and returns substring 
        // of specified length 
        return substr(
            str_shuffle($str_result),
            0,
            $length_of_string
        );
    }
}