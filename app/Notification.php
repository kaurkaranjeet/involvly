<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Video;

class Notification extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'user_id', 'notification_message','type','is_follow','stream_id','notify_id',
    ];
    
   
  protected $table='notification';

      public function User(){
   return $this->belongsTo('App\User','user_id','id');
   }

  public function NotifyUser(){
   return $this->belongsTo('App\User','notify_id','id');
   }
}
