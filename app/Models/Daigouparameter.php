<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daigouparameter extends Model
{
    use HasFactory;
    protected $table = 'daigouparameters';
    protected $fillable = [
        'name', // 名字
        'price', // 價格
        'unit', // 單位
    ];

}
