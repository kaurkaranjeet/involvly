<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentReply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_replies', function (Blueprint $table) {
//            $table->id();
//            $table->timestamps();
            $table->id();
            $table->index('user_id');
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade');          
            $table->index('post_id');
            $table->unsignedBigInteger('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->index('comment_id');
            $table->unsignedBigInteger('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->string('reply_comment')->nullable();
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_reply');
    }
}
