<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'status',
        'request_date',
        'order_date',
        'request_user',
        'delivery_date',
        'arrival_date',
        'arrival_user',
        'order_quantity',
        'arrival_quantity',
        'description',
        'item_id',
        'company_id',
        'user_id',
        'location_id',
    ];
    
    // 発注済みと入荷待ちの差　入荷まち数
    public static function calculatePendingArrivals($itemId)
    {
        return static::where('item_id', $itemId)
                     ->whereIn('status', ['入荷待ち','発注待ち'])
                     ->get()
                     ->sum(function ($order) {
                         return $order->order_quantity - $order->arrival_quantity;
                     });
    }
    
    // 発注数の提案を計算する。
    public static function calculateOrderQuantity($itemId)
    {
        $item = SupplyItem::find($itemId);
        
        if (!$item) {
            return ['orderQuantity' => 0, 'pendingArrivals' => 0];
        }

        $pendingArrivals = static::calculatePendingArrivals($itemId);
        $requiredQuantity = $item->constant_stock - ($item->actual_stock + $pendingArrivals);
        $orderQuantity = 0;

        if ($requiredQuantity > 0) {
            $orderQuantity = ceil($requiredQuantity / $item->order_lot) * $item->order_lot;
        }
        // orderQuantity = 発注提案数　pendingArrivals = 入荷待ち数
        return ['orderQuantity' => $orderQuantity,'pendingArrivals' => $pendingArrivals];
    }

    
    public function supplyItem()
    {
        return $this->belongsTo(SupplyItem::class, 'item_id');
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function requestUser()
    {
        return $this->belongsTo(User::class, 'request_user');
    }
    
    public function arrivalUser()
    {
        return $this->belongsTo(User::class, 'arrival_user');
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
