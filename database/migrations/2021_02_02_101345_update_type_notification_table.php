<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('notification', function (Blueprint $table) {

            //
          
            $table->string('push_type')->nullable();
             $table->index('assignment_id');
            $table->unsignedBigInteger('assignment_id')->references('id')->on('assignments')->nullable()->onDelete('cascade');
         //   $table->string('assignment_id')->nullable();
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
