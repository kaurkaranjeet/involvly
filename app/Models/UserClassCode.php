<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserClassCode extends Model
{ 
       
    protected $table= 'user_class_code';
    protected $fillable = ['user_id','class_id'];

    public function User() 
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function Class()
    {
        return $this->belongsTo('App\Models\ClassCode', 'class_id', 'id');
    }
}
