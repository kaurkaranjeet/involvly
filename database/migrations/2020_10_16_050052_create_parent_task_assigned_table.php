<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentTaskAssignedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_task_assigned', function (Blueprint $table) {
            $table->id();
      $table->unsignedBigInteger('task_id');
      $table->foreign('task_id')->references('id')->on('parent_tasks')->onDelete('cascade');         
      $table->index('task_assigned_to');
      $table->unsignedBigInteger('task_assigned_to')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('parent_task_assigned');
    }
}
