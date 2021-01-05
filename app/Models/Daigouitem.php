<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daigouitem extends Model
{
    use HasFactory;
    protected $table = 'daigouitems';
    protected $fillable = [
        'dgid', //代購Id
        'dgurl', //代購連結
        'dgimgurl', //代購圖片連結
        'dgtype', //代購類別
        'count', //代購數量
        'price', //代購商品價格(韓幣)
        'note', //代購規格或備註
        'total', // 總價(台幣)
    ];
}
