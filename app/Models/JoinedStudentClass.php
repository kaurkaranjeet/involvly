<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class JoinedStudentClass extends Model
{ 
       
    protected $table= 'joined_student_classes';
    protected $fillable = ['student_id','subject_id','class_id', 'school_id','join_date','leave_date','status'];

	public function users()
    {
        return $this->hasMany('App\User');
    }
}
