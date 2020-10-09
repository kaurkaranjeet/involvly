<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{ 
	  public function userDetail()
{
    return $this->belongsTo('App\User');
}
}
