<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SubmittedAssignments extends Model {

    protected $table = 'submitted_assignments';
    protected $fillable = ['assignment_id', 'student_id', 'submitted_attachement', 'class_id','submit_status'];
    protected $casts = [
        'submitted_attachement' => 'array'
    ];
    

    public function User() {
        return $this->belongsTo('App\User', 'student_id', 'id');
    }
    
    public function Assignments() {
        return $this->belongsTo('App\Models\Assignment', 'assignment_id', 'id');
    }
    
    public function AssignedClass() {
        return $this->belongsTo('App\Models\ClassCode', 'class_id', 'id');
    }
    
    public function subjects() {
        return $this->belongsTo('App\Models\Subject', 'subject_id', 'id');
    }

}
