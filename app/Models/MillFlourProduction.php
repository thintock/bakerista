<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillFlourProduction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'production_lot_number',
        'production_date',
        'start_time',
        'end_time',
        'total_input_weight',
        'total_input_cost',
        'flour_weight',
        'bran_weight',
        'milling_retention',
        'is_finished',
        'remarks',
        'mill_machine_id',
        'user_id'
    ];
    
    // 日付として扱う属性を追加
    protected $dates = ['production_date'];
    
    // 原料投入量を計算
    public static function calculateTotals(array $inputWeights, array $millPolishedMaterialIds, $flourWeight, $branWeight)
    {
        $totalInputWeight = array_sum($inputWeights);
        $calculatedInputCosts = 0;
        $thisInputCost = 0;
        
        foreach ($millPolishedMaterialIds as $index => $id) {
        $polishedMaterial = MillPolishedMaterial::find($id);
        if ($polishedMaterial !== null && $polishedMaterial->total_output_weight > 0) {
            // 対応する input weight を取得
            $inputWeight = $inputWeights[$index] ?? 0;
            // total_input_costを計算
            $thisInputCost = ($polishedMaterial->total_input_cost / $polishedMaterial->total_output_weight) * $inputWeight;
            $calculatedInputCosts += $thisInputCost;
            $inputCosts[] = $thisInputCost;
        }
    }
        
        // 製粉歩留率を計算
        $millingRetention = ($totalInputWeight > 0) 
            ? (($flourWeight + $branWeight) / $totalInputWeight) * 100
            : 0;
        
        return [
            'total_input_weight' => $totalInputWeight,
            'total_input_cost' => $calculatedInputCosts,
            'input_costs' => $inputCosts,
            'milling_retention' => $millingRetention
        ];
    }
    
    // 以下関連テーブル定義
    // 関連するMillPolishedMaterialsモデルを取得
    public function millPolishedMaterials()
    {
        return $this->belongsToMany(
            MillPolishedMaterial::class, 
            'mill_flour_production_details', // 中間テーブル名を正確に指定
            'mill_flour_production_id',     // このモデルに関連する外部キー
            'mill_polished_material_id'     // 関連するモデルに関連する外部キー
        )
        ->withPivot('input_weight', 'input_cost') // 中間テーブルの追加カラム
        ->withTimestamps(); // created_at と updated_at も扱う
    }

    // 関連するMillMachineモデルを取得
    public function millMachine()
    {
        return $this->belongsTo(MillMachine::class);
    }

    // 関連するUserモデルを取得
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
