<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachingProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teaching_program', function (Blueprint $table) {
            $table->id('teaching_id');
 
            $table->string('hourly_rate');
            $table->enum('availability',['Full-Time','Part-Time','Both'])->default('Both');
            $table->string('location');

            $table->enum('preferences',['On-Site','Remote'])->default('On-Site');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('teaching_program');
    }
}
