<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaigouitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daigouitems', function (Blueprint $table) {
            $table->id();
            $table->string('dgid');//代購Id
            $table->string('dgurl');//代購連結
            $table->string('dgimgurl')->nullable();;//代購圖片連結
            $table->string('dgtype');//代購類別
            $table->integer('count');//代購數量
            $table->integer('price');//代購商品價格(韓幣)
            $table->text('note')->nullable();//代購規格或備註
            $table->integer('total');// 總價(台幣)
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
        Schema::dropIfExists('daigouitems');
    }
}
