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
use App\Models\Group;
use Tymon\JWTAuth\Exceptions\JWTException;

class ClassController extends Controller {

    //class code listing
    public function manageClasses(Request $request,$id) {
        
   $school= User::find($id);
        $classes = ClassCode::where('school_id',$school->school_id)->get();
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
        'approved' => '1',
        'school_id' => $request->get('school_id'),
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
        'class_code' => 'required',
        // 'class_code' => 'required|unique:class_code,class_code,'.$request->get('class_id'),
    ]);
    if($validator->fails()){
      return response()->json([ 'error' =>true, 'message'=>$validator->errors()->first()], 200);
    }
    if(!empty($request->get('class_id'))){
      //get school id from class_id
      $school_id = ClassCode::where('id',$request->get('class_id'))->first();
      //check unique class code validation
      $unique_class_code = ClassCode::where('school_id',$school_id->school_id)->where('class_code',$request->get('class_code'))->first();
      if(!empty($unique_class_code)){
        return response()->json(array('error' => true, 'message' => 'Class Code already exist', 'data' => []), 200);  
    }
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