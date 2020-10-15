<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ParentTask extends Model
{ 
       
    protected $table= 'parent_tasks';
    protected $fillable = ['task_assigned_by','task_assigned_to','task_name', 'task_date','task_time', 'task_description'];

	public function users()
    {
        return $this->hasMany('App\User');
    }
}
