<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscussionCommentsReply extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_comments_reply', function (Blueprint $table) {
            $table->id();
            $table->index('user_id');
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade');          
            $table->index('discussion_id');
            $table->unsignedBigInteger('discussion_id')->references('id')->on('group_discussions')->onDelete('cascade');
            $table->index('comment_id');
            $table->unsignedBigInteger('comment_id')->references('id')->on('group_discussion_comments')->onDelete('cascade');
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
        Schema::dropIfExists('discussion_comments_reply');
    }
}
