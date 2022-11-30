<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('phone');
            $table->boolean('ptp');
            $table->string('hourly_rate');


            $table->unsignedBigInteger('class_id')->nullable();
            $table->foreign('class_id')->references('id')->on('class_code');

            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('ptp');
            $table->dropColumn('hourly_rate');

            $table->dropForeign('lists_class_id_foreign');
            $table->dropIndex('lists_class_id_index');
            $table->dropColumn('class_id');

            $table->dropForeign('lists_subject_id_foreign');
            $table->dropIndex('lists_subject_id_index');
            $table->dropColumn('subject_id');
        });
    }
}
