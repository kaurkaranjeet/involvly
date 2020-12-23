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

   public function likes(){
   return $this->hasMany('App\Models\DiscussionsLike','discussion_id')->where('like','=', 1);
}
public function comments(){
   return $this->hasMany('App\Models\DiscussionComment','discussion_id');
}

}
