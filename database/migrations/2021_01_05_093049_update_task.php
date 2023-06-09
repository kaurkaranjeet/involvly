<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
          Schema::table('parent_tasks', function (Blueprint $table) {

            $table->index('schedule_id');
            $table->unsignedBigInteger('schedule_id')->references('id')->on('schedules')->onDelete('cascade'); 

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
