<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupDiscussionComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_discussion_comments', function (Blueprint $table) {
             $table->id();
            $table->index('user_id');
            $table->unsignedBigInteger('user_id')->references('id')->on('users')->onDelete('cascade');          
            $table->index('discussion_id');
            $table->unsignedBigInteger('discussion_id')->references('id')->on('group_discussions')->onDelete('cascade');
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('group_discussion_comments');
    }
}
