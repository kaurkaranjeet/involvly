<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GroupMember extends Model
{ 

	
	public function User(){
     return $this->belongsTo('App\User','member_id','id');
   }
}
