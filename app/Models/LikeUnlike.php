<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class LikeUnlike extends Model {

   protected $table='likes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id','like'
    ];
    
    public function User(){
     return $this->belongsTo('App\User','user_id','id');
   }


}
