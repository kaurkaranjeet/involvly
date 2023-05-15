<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teaching_program', function (Blueprint $table) {
            $table->string('experience')->nullable();
            $table->text('description')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ab_routing_number')->nullable();
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
            $table->dropColumn('experience');
            $table->dropColumn('description');
            $table->dropColumn('bank_name');
            $table->dropColumn('account_number');
            $table->dropColumn('ab_routing_number');
        });
    }
}
