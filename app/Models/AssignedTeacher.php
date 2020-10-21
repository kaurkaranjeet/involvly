<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssignedTeacher extends Model
{ 
       
    protected $table= 'assigned_teachers';
    protected $fillable = ['class_id','subject_id','teacher_id', 'school_id'];

	public function User(){
        return $this->belongsTo('App\User','teacher_id','id');
        }
        public function Class(){
            return $this->belongsTo('App\Model\ClassCode','class_id','id');
            }
            public function subjects(){
                return $this->belongsTo('App\Model\ClassSubjects','subject_id','id');
                }
}
