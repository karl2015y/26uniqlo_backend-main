<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blogs';
    protected $fillable = [
        'name', //文章內容
        'description', //部落格文章
        'status', //是否上架
        'bimg', //圖片連結
        'type', //類別; Home/Blog/vip
    ];
}
