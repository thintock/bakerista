<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillPurchaseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'materials_id',
        'arrival_date',
        'year_of_production',
        'flecon_number',
        'total_amount',
        'remaining_amount',
        'is_finished',
        'lot_number',
        'cost',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function material()
    {
        return $this->belongsTo(Material::class, 'materials_id');
    }
    
    // MillPolishedMaterialsとの多対多リレーション
    public function millPolishedMaterials()
    {
        return $this->belongsToMany(
            MillPolishedMaterial::class,
            'mill_purchase_material_polished', // 中間テーブル
            'mill_purchase_material_id', // このモデルに関連する外部キー
            'mill_polished_material_id' //関連するモデルに関連する外部キー
            )
            ->withPivot('input_weight', 'input_cost') // 中間テーブルの追加カラムも指定
            ->withTimestamps();
    }
    
    // 在庫更新メソッド
    public function updateRemainingAmount() {
        $usedAmount = $this->millPolishedMaterials()->sum('pivot.input_weight');
        $this->remaining_amount = $this->total_amount - $usedAmount;
    
        if ($this->remaining_amount <= 0) {
            $this->remaining_amount = 0; // 負の数にならないように
            $this->is_finished = true;
        } else {
            $this->is_finished = false;
        }
        $this->save();
    }
    
}