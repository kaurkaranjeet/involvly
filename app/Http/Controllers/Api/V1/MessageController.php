<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailable;
use App\User;
use App\Models\Message;
use Pusher\Pusher;
use App\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use DB;
use URL;

class MessageController extends Controller {

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

 /**
     * sendUserMessage
     *
     * @param Request $request
     */
      public function sendUserMessage(Request $request)
    {
        $input = $request->all();
        $response =[];
        try{
            $validator = Validator::make($input, [
             'from_user_id' => 'required|exists:users,id',
             'to_user_id' => 'required|exists:users,id',
         
            ]);
            if($validator->fails()){
             throw new Exception( $validator->errors());
         }  else{

         $data = new Message();
         $data->from_user_id =  $request->from_user_id;
         $data->to_user_id =  $request->to_user_id;
         $data->message = $request->message; 
         if(empty($data->message)){
         	$data->message='';
         } 
            $data->is_read = 0; // message will be unread when sending message
            $data->save();
            $data->message_date = $data->created_at->diffForHumans();
            $data->User;      
            $this->pusher->trigger('chat-channel', 'chat_event', $data);  
         // prepare some data to send with the response
        $response = [
          'error' =>  true,
          'message'  =>'Message send successfully',
          'data' =>  $data

        ]; 
    }
}
      //catch exception
catch(Exception $e) {
    $response = [
        'success' => false,
        'message' =>  $e->getMessage(),
    ]; 

}
return response()->json($response);
}

}
