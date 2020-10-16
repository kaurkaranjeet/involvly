<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Role;
use App\Models\ClassCode;
use App\Models\Subject;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SubjectController extends Controller {

    //subject listing
    public function manageSubjects(Request $request , $id) {
        DB::enableQueryLog();
        $subjects = Subject::where('class_id', $id)->get();
        if (isset($subjects) && count($subjects) > 0) {
            return response()->json(compact('subjects'), 200);
        } else {
            return response()->json(['error' => 'true', 'subjects' => [], 'message' => 'No record found'], 200);
        }
    }

    // Add subject
    public function saveSubject(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'subject_name' => 'required|min:3',
    ]);

    if($validator->fails()){
            return response()->json([ 'error' =>true, 'message'=>$validator->errors()->first()], 200);
    }
    if(!empty($request->get('class_id'))){
      $subject = Subject::create([
        'subject_name' => $request->get('subject_name'),
        'class_id' => $request->get('class_id'),
    ]);
    return response()->json(compact('subject'),201);
      }else{
        return response()->json(['message' => 'id not found'], 200);
      }
    }

    //delete subject
  public function deleteSubject($id)
  {
    if (!empty($id)) {
      $subject = Subject::findOrFail($id);
      $subject->delete();
      return response()->json(compact('subject'), 200);
    } else {
      return response()->json(['error' => 'true', 'subject' => [], 'message' => 'No subject Found'], 200);
    }
  }

    //fetch subject by subject id
    public function fetchSubjectDetail($id)
    {
      if (!empty($id)) {
        $subject = Subject::where('id',$id)->first();
        if (isset($subject)) {
            return response()->json(compact('subject'), 200);
        } else {
            return response()->json(['message' => 'No record found'], 200);
        }
      } else {
        return response()->json(['message' => 'Something went wrong'], 200);
      }
    }

    // edit subject
    public function editSubject(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'subject_id' => 'required',
        'subject_name' => 'required',
    ]);
    if($validator->fails()){
      $messages = implode(",", errorMessages($validator->messages()));
      return response()->json([ 'error' =>true, 'message'=>$messages], 200);
    }
    if(!empty($request->get('subject_id'))){
    $data['subject_name'] = $request->get('subject_name');
    $subject = Subject::where('id', $request->get('subject_id'))->update($data);
    return response()->json(compact('subject'),201);
    }else{
      return response()->json(['message' => 'id not found'], 200);
    }
    }
}

?>