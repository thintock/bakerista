<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillPolishedMaterial extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'polished_date',
        'polished_lot_number',
        'total_input_weight',
        'total_output_weight',
        'total_input_cost',
        'user_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // MillPublicMaterialsとの多対多リレーション
    public function millPurchaseMaterials ()
    {
      return $this->belongsToMany(
          MillPurchaseMaterial::class, 
        'mill_purchase_material_polished', // 中間テーブル
        'mill_polished_material_id',   // このモデルに関連する外部キー
        'mill_purchase_material_id' // 関連するモデルに関連する外部キー
        )
        ->withPivot('input_weight', 'input_cost')
        ->withTimestamps();
    }
}