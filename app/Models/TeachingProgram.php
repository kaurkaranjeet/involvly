<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class TeachingProgram extends Model
{

    protected $table = 'teaching_program';
    protected $fillable = [
        'class_id',
        'subject_id',
        'hourly_rate',
        'availability',
        'location',
        'preferences',
        'user_id',
        'request_status',

    ];
    protected $casts = [
        'subject_id' => 'array',
        'class_id' => 'array'
    ];

    public function Subject()
    {
        return $this->belongsTo('App\Models\Subject', 'subject_id');
    }

    public function Class()
    {
        return $this->belongsTo('App\Models\ClassCode', 'class_id');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }
    public static function add($data)
    {
        if ($data) {
            $TeachingProgram = new TeachingProgram;
            $TeachingProgram->class_id = $data['class_id'];
            $TeachingProgram->subject_id = $data['subject_id'];
            $TeachingProgram->hourly_rate =  $data['hourly_rate'];
            $TeachingProgram->availability = $data['availability'];
            $TeachingProgram->location = $data['location'];
            $TeachingProgram->preferences = $data['preferences'];
            $TeachingProgram->user_id = $data['id'];
            return $TeachingProgram->save();
        }
    }
    public static function requestStatus($data)
    {
         
        if ($data) { 
            $users= self::where('user_id', $data['id'])->update([
                'request_status' => $data['request_status']
            ]);
           
            return  $users;
        }
    }
}
