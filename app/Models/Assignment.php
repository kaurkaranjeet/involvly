<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Assignment extends Model {

    protected $table = 'assignments';
    protected $fillable = ['teacher_id', 'assignments_name', 'assignments_description', 'assignments_special_instruction', 'assignments_date', 'assignments_attachement'];
    protected $casts = [
        'assignments_attachement' => 'array'
    ];


    public function User() {
        return $this->belongsTo('App\User', 'teacher_id', 'id');
    }

    public function AssignedClass() {
        return $this->belongsTo('App\Models\ClassCode', 'class_id', 'id');
    }

}
