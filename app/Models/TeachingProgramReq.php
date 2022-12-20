<?php

namespace App\Models;

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
            $users = self::updateOrCreate(
                ['to_user' => $data['id'],'from_user' => $data['from_user']],
                [
                  'to_user' => $data['id'],
                  'from_user' => $data['from_user'],
                  'request_status' => strval($data['request_status']),
                ]   
            );
            // Need Assistance
            if($data['request_status'] == 0)
            {
                self::where(['to_user'=>$data['id'],'from_user'=>$data['from_user']])->delete();
            }
            return  $users;
        }
    }
}
