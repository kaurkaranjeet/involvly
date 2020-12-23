<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class DiscussionComment extends Model {



   protected $table='discussions_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'discussion_id','comment'
    ];

      public function User(){
   return $this->belongsTo('App\User','user_id','id');
   }

 /*public function replycomments(){
    return $this->hasMany('App\Models\CommentReply','comment_id');
 }*/
    
}
