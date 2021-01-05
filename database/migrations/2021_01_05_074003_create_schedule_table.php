<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_name')->nullable();
            $table->text('selected_days')->nullable();
            $table->string('from_time')->nullable();
            $table->string('to_time')->nullable();
            $table->text('description')->nullable();
            $table->text('assigned_to')->nullable();
            $table->index('created_by');
            $table->unsignedBigInteger('created_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('schedule');
    }
}
