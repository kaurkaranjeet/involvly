<?php

namespace App\Models;

use App\Jobs\ProcessRequest;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class TeachingProgramReq extends Model
{
    protected $table = 'teachin_program_requests';
    protected $fillable = [
        'from_user',
        'to_user',
        'request_status',
    ];
    public static function requestStatus($data)
    {
        //    echo  $data['request_status'];exit;
        if ($data) {
            self::updateOrCreate(
                [
                    'to_user' => $data['id'],
                    'from_user' => $data['from_user']
                ],
                [
                    'to_user' => $data['id'],
                    'from_user' => $data['from_user'],
                    'request_status' => strval($data['request_status']),
                ]
            );
            // Need Assistance
            if ($data['request_status'] == 0) {
                self::where(['to_user' => $data['id'], 'from_user' => $data['from_user']])->delete();
            }


            $usersData = User::where('id', $data['id'])
                ->select('users.*', 'teaching_program.*')
                ->leftJoin('teaching_program', 'users.id', '=', 'teaching_program.user_id')
              
                ->first();
              $from_user_id = intval($data['from_user']);
          
            $process = ProcessRequest::dispatch($usersData, $from_user_id);

            $users = self::where('to_user','=', $data['id'])
            ->where('from_user','=', $data['from_user'])->first();
            if ($users->request_status == 0) {
                $message = "Request has been cancelled";
            } else if ($users->request_status == 1) {
                $message = "Request has been Sent";
            } else if ($users->request_status == 2) {
                $message = "Request has been Accepted";
            } else {
                $message = "Request not Placed!";
            }
            return response()->json(['message' =>$message, 'data' => $users], 200);
 
        }
    }
}
