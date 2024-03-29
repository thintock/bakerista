<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * MillPolishedMaterialモデル（精麦）
 * 
 * 研磨された材料に関する情報を管理します。各属性は研磨材料テーブルのカラムに対応しており、
 * 材料の追跡、重量管理、コスト計算などのビジネスロジックに基づいています。
 */
 
class MillPolishedMaterial extends Model
{
    use HasFactory;
    
    /**
     * 一括割当て可能な属性
     * 
     * @var array
     * 
     * - polished_date: 研磨日 (date)
     * - polished_lot_number: 研磨ロット番号 (varchar, 15文字)
     * - total_input_weight: 総入力重量 (decimal, 8桁2小数点)
     * - total_output_weight: 総出力重量 (decimal, 8桁2小数点)
     * - total_input_cost: 総入力コスト (decimal, 8桁2小数点)
     * - user_id: ユーザーID (bigint, unsigned)
     */
     
    protected $fillable = [
        'polished_date',
        'polished_lot_number',
        'total_input_weight',
        'total_output_weight',
        'total_input_cost',
        'polished_retention',
        'remaining_polished_amount',
        'is_finished',
        'mill_whiteness_1',
        'mill_whiteness_2',
        'mill_whiteness_3',
        'mill_whiteness_4',
        'mill_whiteness_5',
        'mill_whiteness_6',
        'mill_whiteness_7',
        'mill_whiteness_8',
        'user_id',
    ];

    // 日付として扱う属性を追加
    protected $dates = ['polished_date'];
    
    // 在庫残量 remaining_polished_amountの計算
    // updateの際には、$requestのtotal_output_weightと現在のremaining_polished_amountの
    // 値の差分を計算し、その分だけremaining_polished_amountの数を修正する。
    // また、在庫がゼロ以下になれば、is_finishedをtrueにゼロ以上だったらis_finishedを
    // falseにする処理を入れる。
    public static function calculateRemaining($currentRemaining, $newTotalOutputWeight, $currentTotalOutputWeight) 
    {
        $difference = $newTotalOutputWeight - $currentTotalOutputWeight; //total_output_weightの変更を計算
        $newRemaining = $currentRemaining + $difference; // 新しい在庫量を計算
        $isFinished = $newRemaining <= 0; // 在庫量が0以下ならis_finishedをtrueに、それ以外ならfalseに設定
        return [
            'remaining' => max($newRemaining, 0),
            'is_finished' => $isFinished
            ]; // 在庫がマイナスにならないようにチェック
    }
    
    /**
     * Userモデルとの関連付け
     * 
     * この研磨材料を管理するユーザーを定義します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * MillPurchaseMaterialsとの多対多リレーション
     * 
     * この研磨材料を使用している購入材料との関係を管理します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
     
    public function millPurchaseMaterials ()
    {
      return $this->belongsToMany(
          MillPurchaseMaterial::class, 
        'mill_purchase_material_polished', // 中間テーブル
        'mill_polished_material_id',   // このモデルに関連する外部キー
        'mill_purchase_material_id' // 関連するモデルに関連する外部キー
        )
        ->withPivot('input_weight', 'input_cost') // 中間テーブルの追加カラム
        ->withTimestamps();
    }
}