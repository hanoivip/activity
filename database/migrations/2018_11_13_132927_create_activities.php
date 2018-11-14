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
            $table->integer('activity_id')->comment('Activity config id');
            $table->integer('user_id');
            $table->integer('role_id')->default(0);
            $table->integer('current_recharge');
            $table->string('rewards')->comment('Các mốc đã được thưởng');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
