<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ParentTask extends Model {

    protected $table = 'parent_tasks';
    protected $fillable = ['task_assigned_by', 'task_assigned_to', 'task_name', 'task_time', 'task_description'];
     protected $casts = [
        'image' => 'array',
         'selected_days' => 'array'
    ];

    public function User() {
        return $this->belongsTo('App\User', 'task_assigned_by', 'id');
    }
    
    public function AssignedUser(){
        return $this->hasMany('App\Models\ParentTaskAssigned','task_id');
    }

}
