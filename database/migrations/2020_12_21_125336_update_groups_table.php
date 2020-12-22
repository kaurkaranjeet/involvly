<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

         Schema::table('groups', function (Blueprint $table) {
           $table->longText('group_description')->nullable();
           $table->enum('group_category', array('community_group','digital_learning'))->nullable();
           $table->string('view_status')->default('public');
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
