<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ClassCode extends Model
{ 
       
    protected $table= 'class_code';
    protected $fillable = ['class_name','class_code','approved', 'school_id'];

	public function users()
    {
        return $this->hasMany('App\User');
    }
}
