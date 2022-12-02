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
    ];
    protected $casts = [
        'location' => 'array',
        'subject_id' =>'array',
        'class_id' =>'array'

    ];

    public function users()
    {
        return $this->hasMany('App\User');  
    }
    public static function add($data)
    {
        return self::insert([
            'class_id' =>  implode(',',$data['class_id']),
            'subject_id' => implode(',',$data['subject_id']),
            'hourly_rate' => $data['hourly_rate'],
            'availability' => $data['availability'],
            'location' => implode(',',$data['location']),
            'preferences' => $data['preferences'],
            'user_id' =>  $data['id'],
        ]);
    }


    
}
