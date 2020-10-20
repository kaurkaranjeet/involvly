<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Subject extends Model
{ 
    protected $table= 'subjects';
    protected $fillable = ['class_id','subject_name'];

    public function subjects()
    {
        return $this->belongsToMany(ClassCode::class);
    }
}
