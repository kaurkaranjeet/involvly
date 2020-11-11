<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UnapproveStudent extends Model {

    //protected $table = 'joined_student_classes';
    protected $fillable = ['parent_id', 'full_name', 'class_code_id', 'school_id','status'];
 protected $casts = [
        'documents' => 'array'
    ];
    public function users() {
        return $this->hasMany('App\User');
    }

}
