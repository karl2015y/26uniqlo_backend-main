<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daigouorder extends Model
{
    use HasFactory;
    protected $table = 'daigouorders';
    protected $fillable = [
        'uuid', // 用戶Id
        'ooid', // 訂單Id
        'dgid', // 代購Id
        'status', // 是否完成交易
    ];

}
