<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableTentsites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tent_sites', function (Blueprint $table) {
            $table->integer('reported_by')->nullable()->change();
            $table->string('location_name')->nullable()->change();
            $table->integer('created_time')->nullable()->change();
            $table->integer('likes')->nullable()->change();
            $table->string('img_location')->nullable()->change();
            $table->string('external_id')->nullable()->change();
            $table->string('thumbnail_location')->nullable()->change();
            $table->string('caption')->nullable()->change();
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
