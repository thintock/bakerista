<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintHistory extends Model
{
    use HasFactory;

    protected $table = 'print_histories'; // モデルが使用するテーブルを指定

    protected $fillable = [
        'user_id',
        'product_id',
        'count',
    ];

    public function productItem()
    {
        return $this->belongsTo(ProductItem::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}