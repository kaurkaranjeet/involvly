<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTeachingProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teaching_program', function (Blueprint $table) {
            $table->enum('request_status',[0,1,2])->comment('0:pending,1:accepted,2:Rejected')->nullable();
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teaching_program', function (Blueprint $table) {
            $table->dropColumn('request_status');
        });
    }
}
