<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class CommentReply extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id','comment_id','reply_comment'
    ];

   public function User(){
   return $this->belongsTo('App\User','user_id','id');
   }
    
}
