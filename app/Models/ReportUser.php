<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReportUser extends Model
{ 
	
	public function FromDetail(){
		return $this->belongsTo('App\User','from_user_id','id');
	}
	public function ToDetail(){
		return $this->belongsTo('App\User','to_user_id','id');
	}
}
