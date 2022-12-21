<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachinProgramRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachin_program_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_user')->nullable();
            $table->foreign('from_user')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('to_user')->nullable();
            $table->foreign('to_user')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('teaching_req_id')->nullable();
            // $table->foreign('teaching_req_id')->references('teaching_id')->on('teaching_program')->onDelete('cascade');
            $table->enum('request_status',['0','1','2'])->comment('0:default,1:pending,2:Accept')->nullable();
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
        Schema::dropIfExists('teachin_program_requests');        
    }
}
