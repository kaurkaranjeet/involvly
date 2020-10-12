<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::table('schools', function (Blueprint $table) {
           $table->index('city_id');
           $table->unsignedBigInteger('city_id')->references('id')->on('cities')->onDelete('cascade'); 
           $table->index('state_id');
           $table->unsignedBigInteger('state_id')->references('id')->on('states')->onDelete('cascade'); 
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
