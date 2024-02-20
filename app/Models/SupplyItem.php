<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'item_code',
        'item_name',
        'standard',
        'brand_name',
        'category',
        'price',
        'description',
        'thumbnail',
        'files',
        'print_images',
        'item_status',
        'order_url',
        'order_schedule',
        'delivery_period',
        'order_point',
        'order_lot',
        'constant_stock',
        'actual_stock',
        'location_code',
        'company_id',
        'user_id',
    ];
    
    // 日付として扱う属性を追加
    protected $dates = ['order_schedule'];
    
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_code', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
