<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssignedTeacher extends Model {

    protected $table = 'assigned_teachers';
    protected $fillable = ['class_id', 'subject_id', 'teacher_id', 'school_id'];

    public function User() {
        return $this->belongsTo('App\User', 'teacher_id', 'id');
    }

    public function ClassSubjects() {
        return $this->belongsTo('App\Models\ClassSubjects', 'subject_id', 'id');
    }

    public function AssignedClass() {
        return $this->belongsTo('App\Models\ClassCode', 'class_id', 'id');
    }

}
