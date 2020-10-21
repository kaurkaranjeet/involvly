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

class TeacherController extends Controller {

    public function Approveteacher($id) {
        $data = User::where('id', $id)->update(['status' => '1']);
        return response()->json(compact('data'), 200);
    }

    public function DisApproveteacher($id) {
        $data = User::where('id', $id)->update(['status' => '0']);
        return response()->json(compact('data'), 200);
    }

    //Assigned teachers
    public function fetchAssignedTeachersToClasses(Request $request, $id) {
        DB::enableQueryLog();
        $teachers = User::with('SchoolDetail:id,school_name')
                            ->leftJoin('user_class_code', 'users.id', '=', 'user_class_code.user_id')
                            ->leftJoin('class_code', 'user_class_code.class_id', '=', 'class_code.id')
                            ->select('users.*','class_code.class_name')->where('role_id', 4)->where('user_class_code.class_id', $id)->get();
        if (!empty($teachers)) {
            return response()->json(compact('teachers'), 200);
        } else {
            return response()->json(['error' => 'true', 'teachers' => [], 'message' => 'No record found'], 200);
        }
    }

}

?>