<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Comment extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'post_id','comment'
    ];

      public function User(){
   return $this->belongsTo('App\User','user_id','id');
   }

 public function replycomments(){
    return $this->hasMany('App\Models\CommentReply','comment_id');
 }
    
}
