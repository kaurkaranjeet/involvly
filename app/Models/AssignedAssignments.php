<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssignedAssignments extends Model {

    protected $table = 'assigned_assignments';
    protected $fillable = ['assignment_id', 'assignment_type', 'assignment_assign_to', 'class_id','school_id'];
     protected $casts = [
        'assignment_assign_to' => 'array'
    ];

    public function User() {
        return $this->belongsTo('App\User', 'assignment_assign_to', 'id');
    }

}
