<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaigouordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daigouorders', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->string('ooid');//訂單Id
            $table->string('dgid');//代購Id
            $table->integer('status');//是否完成交易
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
        Schema::dropIfExists('daigouorders');
    }
}
