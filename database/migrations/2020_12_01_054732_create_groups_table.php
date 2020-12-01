<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');  
            $table->integer('status');  
            $table->integer('user_id')->dafault('0');  
            $table->string('type');  
            $table->integer('school_id')->dafault('0'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
