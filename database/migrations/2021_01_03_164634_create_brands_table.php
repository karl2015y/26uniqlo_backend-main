<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->char('name', 100);	// 名稱
            $table->string('pic')->nullable();// 圖片
            $table->text('description');// 簡介
            $table->integer('status')->default(0);// 是否上架，預設下架(0)
            $table->string('type')->default("運動品牌");// 類別，預設
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
        Schema::dropIfExists('brands');
    }
}
