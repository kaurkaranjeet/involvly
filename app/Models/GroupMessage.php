<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GroupMessage extends Model
{ 

	
	public function User(){
     return $this->belongsTo('App\User','from_user_id','id');
   }
}
