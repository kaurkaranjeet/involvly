<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GroupDiscussion extends Model
{ 

	 protected $casts = [
        'image' => 'array'
    ];
	public function User(){
     return $this->belongsTo('App\User','user_id','id');
   }
}
