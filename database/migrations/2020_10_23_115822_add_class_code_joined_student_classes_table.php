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
            $table->unsignedBigInteger('class_code_name')->after('student_id');
            $table->foreign('class_code_name')->references('class_code')->on('class_code')->onDelete('cascade');
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
