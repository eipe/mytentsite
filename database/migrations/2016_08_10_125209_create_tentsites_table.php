<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTentsitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tent_sites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reported_by', false, true);
            $table->float('latitude',10,6);
            $table->float('longitude',10,6);
            $table->string('location_name');
            $table->integer('created_time', false, true);
            $table->integer('likes');
            $table->string('img_location');
            $table->string('external_id');
            $table->string('thumbnail_location');
            $table->string('caption');
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
        Schema::drop('tent_sites');
    }
}
