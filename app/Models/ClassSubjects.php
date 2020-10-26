<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClassSubjects extends Model
{ 
 protected $table ='class_code_subject';
 public function subjects()
    {
        return $this->belongsTo('App\Models\Subject', 'subject_id', 'id');
    }
  
}
