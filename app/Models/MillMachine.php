<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillMachine extends Model
{
    use HasFactory;
    
    /**
     * 一括割当て可能な属性
     * 
     * @var array
     * 
     * - machine_name: 製粉機名 (varchar, 文字列)
     * - description: 製粉機に関する説明や詳細情報 (text, 文字列)
     */
     
    protected $fillable = [
        'machine_number',
        'machine_name',
        'description',
    ];
    
}
