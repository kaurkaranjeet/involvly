<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\User;
use App\Models\Subject;
use App\Models\State;
use App\Models\Cities;
use App\Models\School;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use DB;
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

        $states=State::all();
        if(!empty( $states )){
           return response()->json(array('error' => false, 'data' =>$states ), 200);
       }
       else{
           throw new Exception('No Record');
       }

   }
   catch (\Exception $e) {
       return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data'=>[]), 200);
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
       } else{
         $cities=Cities::where('state_id',$request->state_id)->get();
        if(!empty( $cities )){
           return response()->json(array('error' => false, 'data' =>$cities ), 200);
       }
       else{
           throw new Exception('No City found');
       }

       }  
 }
   catch (\Exception $e) {
       return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data'=>[]), 200);
   }
}

  public function GetSchools(Request $request) {
      try {

        $states=School::all();
        if(!empty( $states )){
           return response()->json(array('error' => false, 'data' =>$states ), 200);
       }
       else{
           throw new Exception('No Record');
       }

   }
   catch (\Exception $e) {
       return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data'=>[]), 200);
   }
}

 public function GetSubjects(Request $request) {
      try {

        $Subject=Subject::all();
        if(!empty( $Subject )){
           return response()->json(array('error' => false, 'data' =>$Subject ), 200);
       }
       else{
           throw new Exception('No Record');
       }

   }
   catch (\Exception $e) {
       return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data'=>[]), 200);
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
          throw new Exception( $validator->errors()->first());
    } else {
       User::where('id',$request->user_id)->update(['join_community'=>'1']);
          return response()->json(array('error' => false, 'data' =>[],'message'=>'Updated Successfully'), 200);
   }
 }
   catch (\Exception $e) {
       return response()->json(array('error' => true, 'message' => $e->getMessage(), 'data'=>[]), 200);
   }
}

public function  RunMigration(){
  Artisan::call('migrate');
}
}