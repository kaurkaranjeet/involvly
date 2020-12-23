<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class DiscussionsLike extends Model {

   protected $table='discussions_like';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'discussions_id','like'
    ];
    
    public function User(){
     return $this->belongsTo('App\User','user_id','id');
   }


}
