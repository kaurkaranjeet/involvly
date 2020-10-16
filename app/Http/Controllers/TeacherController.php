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

    public function Approveteacher($id){
  $data= User::where('id',$id)->update(['status'=>'1']);
  return response()->json(compact('data'), 200);
}

     public function DisApproveteacher($id){
  $data= User::where('id',$id)->update(['status'=>'0']);
  return response()->json(compact('data'), 200);
}
}

?>