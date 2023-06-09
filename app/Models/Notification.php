<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Video;

class Notification extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'user_id', 'notification_message','type','notification_type','from_user_id','push_type','post_id'
    ];
    
   
  protected $table='notification';

      public function User(){
   return $this->belongsTo('App\User','from_user_id','id');
   }
/*
     public function User(){
   return $this->belongsTo('App\User','user_id','id');
   }*/

   

}
