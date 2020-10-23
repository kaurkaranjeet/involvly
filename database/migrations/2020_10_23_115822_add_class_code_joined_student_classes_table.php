<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClassCodeJoinedStudentClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('joined_student_classes', function (Blueprint $table) {
            $table->string('class_code_name')->after('student_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('joined_student_classes', function (Blueprint $table) {
            //
        });
    }
}
