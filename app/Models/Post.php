<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{ 

	 protected $casts = [
        'image' => 'array'
    ];
	  public function user()
{
    return $this->belongsTo('App\User');
}

public function likes(){
   return $this->hasMany('App\Models\LikeUnlike','post_id')->where('like','=', 1);
}
public function comments(){
   return $this->hasMany('App\Models\Comment','post_id');
}


public function isUserLikedPost($user_id){
      $like = $this->likes()->where('user_id',  $user_id)->get();
      if ($like->isEmpty()){
          return 0;
      }
      return 1;
   }
}
