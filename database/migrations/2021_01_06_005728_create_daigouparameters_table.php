<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaigouparametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daigouparameters', function (Blueprint $table) {
            $table->id();
            $table->string('name');// 名字
            $table->integer('price');// 售價
            $table->string('unit');// 單位 ex:個
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
        Schema::dropIfExists('daigouparameters');
    }
}
