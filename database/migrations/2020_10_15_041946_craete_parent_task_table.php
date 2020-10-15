<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CraeteParentTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('parent_tasks', function (Blueprint $table) {

      $table->id();
      $table->unsignedBigInteger('task_assigned_by');
      $table->foreign('task_assigned_by')->references('id')->on('users')->onDelete('cascade');         
      $table->index('task_assigned_to');
      $table->unsignedBigInteger('task_assigned_to')->references('id')->on('users')->onDelete('cascade');
      $table->string('task_name')->nullable();           
      $table->string('task_date')->nullable();
      $table->string('task_time')->nullable();
      $table->text('task_description')->nullable();
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
        //
    }
}
