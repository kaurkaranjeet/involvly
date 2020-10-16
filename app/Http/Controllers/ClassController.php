<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\ClassCode;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ClassController extends Controller {

    //class code listing
    public function manageClasses(Request $request) {
        DB::enableQueryLog();
        // if ($request->type == 'teacher') {
        //     $users = User::where('role_id', 4)->where('status', 1)->with('role')->get();
        // } else if ($request->type == 'students') {
        //     $users = User::where('role_id', 2)->where('status', 1)->get();
        // } else {
        //     $users = User::where('role_id', 3)->where('status', 1)->get();
        // }
        //  print_r(DB::getQueryLog());die;
        $classes = ClassCode::get();
        if (isset($classes) && count($classes) > 0) {
            return response()->json(compact('classes'), 200);
        } else {
            return response()->json(['error' => 'true', 'classes' => [], 'message' => 'No record found'], 200);
        }
    }

    // Add class code
    public function saveClassCode(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'class_name' => 'required',
        'class_code' => 'required|min:3|unique:class_code,class_code',
    ]);

    if($validator->fails()){
            return response()->json([ 'error' =>true, 'message'=>$validator->errors()->first()], 200);
    }

    $class = ClassCode::create([
        'class_name' => $request->get('class_name'),
        'class_code' => $request->get('class_code'),
        'approved' => '0',
        'school_id' => '1',
    ]);

    return response()->json(compact('class'),201);
    }

    //delete class code
  public function deleteClassCode($id)
  {
    if (!empty($id)) {
      $classCode = ClassCode::findOrFail($id);
      $classCode->delete();
      return response()->json(compact('classCode'), 200);
    } else {
      return response()->json(['error' => 'true', 'classes' => [], 'message' => 'No Class Code Found'], 200);
    }
  }

    //fetch class code by class code id
    public function fetchClassCodeDetail($id)
    {
      if (!empty($id)) {
        $class = ClassCode::where('id',$id)->first();
        if (isset($class)) {
            return response()->json(compact('class'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
      } else {
        return response()->json(['message' => 'Something went wrong'], 200);
      }
    }

    // edit class code
    public function editClassCode(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'class_id' => 'required',
        'class_name' => 'required',
        'class_code' => 'required|unique:class_code,class_code,'.$request->get('class_id'),
    ]);
    if($validator->fails()){
      $messages = implode(",", errorMessages($validator->messages()));
      // return json_encode(array("statusCode" => 400, "message" => $messages));
      return response()->json([ 'error' =>true, 'message'=>$messages], 200);
    }
    if(!empty($request->get('class_id'))){
    $data['class_name'] = $request->get('class_name');
    $data['class_code'] = $request->get('class_code');
    $class = ClassCode::where('id', $request->get('class_id'))->update($data);
    return response()->json(compact('class'),201);
    }else{
      return response()->json(['message' => 'id not found'], 200);
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
        User::where('id', $request->id)->update(['name' => $request->name, 'email' => $request->email, 'status' => $request->status]);
        $RoleObj = new Role;
        $role_id = $RoleObj->Role($request->role);
        RoleUser::updateOrCreate(['role_id' => $role_id, 'user_id' => $request->user_id]);
        $data = User::fetchUser($request->id);
        if (isset($data)) {
            return response()->json(compact('data'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
    }

    public function RemoveUser($id) {
        $data = User::where('id', $id)->delete();
        return response()->json(compact('data'), 200);
    }

}

?>