<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ParentTaskAssigned extends Model {

    protected $table = 'parent_task_assigned';
    protected $fillable = ['task_id', 'task_assigned_to'];
     protected $casts = [
        'image' => 'array'
    ];

    public function User() {
        return $this->belongsTo('App\User', 'task_assigned_to', 'id');
    }

    public function AssignedTo() {
        return $this->belongsTo('App\User', 'task_assigned_to', 'id');
    }
    
    
    public function ParentTask() {
        return $this->belongsTo('App\Models\ParentTask', 'task_id', 'id');
    }

}
