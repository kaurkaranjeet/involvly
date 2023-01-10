<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\User;

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
    // protected $casts = [
    //     'subject_id' => 'array',
    //     'class_id' => 'array'
    // ];

    public function Subject()
    {
        return $this->belongsTo('App\Models\Subject', 'subject_id');
    }

    public function Class()
    {
        return $this->belongsTo('App\Models\ClassCode', 'class_id');
    }

    public function User()
    {
        return $this->belongsTo('App\User','user_id');
    }
   
    public function subjects() {
        return $this->hasMany('App\UserSubject','user_id','user_id'); 
      }
  
    public static function add($data)
    {
        if ($data) {
            return self::updateOrCreate([
                'hourly_rate' => $data['hourly_rate'],
                'availability' => $data['availability'],
                'location' => $data['location'],
                'preferences' => $data['preferences'],
                'user_id' => $data['id'],
            ]);
        }
    }
    // 
}
