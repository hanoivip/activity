<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivities extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('week')->comment('Định dạng: year@week. Ex: 2018@01');
            $table->integer('user_id');
            $table->string('svname');
            $table->integer('total_recharges');
            $table->string('rewards')->comment('Các mốc đã được thưởng');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
