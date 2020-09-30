<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTableStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::table('users', function (Blueprint $table) {
         
          $table->integer('status');
        
          $table->unsignedBigInteger('role_id');

          $table->foreign('role_id')->references('id')->on('roles');

          $table->unsignedBigInteger('school_id');

          $table->foreign('school_id')->references('id')->on('schools');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
