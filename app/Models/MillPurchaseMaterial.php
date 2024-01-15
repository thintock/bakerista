<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MillPurchaseMaterialモデル（原材料仕入れ）
 * 
 * 購入された材料の在庫と使用状況を管理します。各属性は購入材料テーブルのカラムに対応しており、
 * 材料の追跡、在庫管理、コスト計算などのビジネスロジックに基づいています。
 */
 
class MillPurchaseMaterial extends Model
{
    use HasFactory;
    
    /**
     * 一括割当て可能な属性
     * 
     * @var array
     * 
     * - materials_id: 材料のID (bigint, unsigned)
     * - arrival_date: 材料の到着日 (date)
     * - year_of_production: 生産年 (varchar, 2文字)
     * - flecon_number: フレコン番号 (varchar, 3文字)
     * - total_amount: 総量 (int)
     * - remaining_amount: 残量 (int)
     * - is_finished: 使用完了フラグ (tinyint, 1)
     * - lot_number: ロット番号 (varchar, 11文字)
     * - cost: 費用 (decimal, 8桁2小数点)
     * - user_id: ユーザーID (bigint, unsigned)
     */
     
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
    
    // 日付として扱う属性を追加
    protected $dates = ['arrival_date'];
    
    /**
     * Userモデルとの関連付け
     * 
     * この購入材料を管理するユーザーを定義します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Materialモデルとの関連付け
     * 
     * この購入材料の元となる材料を定義します。
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     
    public function material()
    {
        return $this->belongsTo(Material::class, 'materials_id');
    }
    
    /**
     * MillPolishedMaterialsとの多対多リレーション
     * 
     * この購入材料がどの研磨材料に使われたかを管理します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
     
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
    
    /**
     * 在庫更新メソッド
     * 
     * 研磨材料の使用に応じて残量を更新し、必要に応じて使用完了フラグを更新します。
     */
    public function updateRemainingAmount() {
        $usedAmount = $this->millPolishedMaterials()->sum('pivot.input_weight');
        $this->remaining_amount = $this->total_amount - $usedAmount;
    
        if ($this->remaining_amount <= 0) {
            $this->remaining_amount = 0; // 負の数にならないように
            $this->is_finished = true; // 完了フラグ更新
        } else {
            $this->is_finished = false; // 完了フラグ更新
        }
        $this->save();
    }
    
}