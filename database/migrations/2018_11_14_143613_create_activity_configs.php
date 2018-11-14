<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityConfigs extends Migration
{
    public function up()
    {
        Schema::create('activity_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('start')->comment('Thời gian bắt đầu');
            $table->integer('end')->comment('Thời gian kết thúc');
            $table->string('title');
            $table->string('description');
            $table->integer('icon')->default(0);
            $table->string('params', 20000);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('activity_configs');
    }
}
