<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Materialモデル（原材料）
 * 
 * データベースのmaterialsテーブルに対応する属性を持つmaterialエンティティを表します。
 * 各属性はテーブルのカラムに対応しており、特定のビジネスロジックや機能要件に基づいています。
 */
 
class Material extends Model
{
    use HasFactory;
    /**
     * 一括割当て可能な属性
     * 
     * @var array
     * 
     * - materials_code: マテリアルのユニークコード (varchar, 6文字)
     * - materials_name: マテリアルの名前 (varchar, 255文字)
     * - materials_purchaser: マテリアルの購入者名 (varchar, 255文字)
     * - materials_producer_name: マテリアルの生産者名 (varchar, 255文字)
     * - user_id: このマテリアルに関連付けられているユーザーのID (bigint, unsigned)
     */
    protected $fillable = [
        'materials_code',
        'materials_name',
        'materials_purchaser',
        'materials_producer_name',
        'user_id'
        ];
        
    /**
     * ユーザー関連付け
     * 
     * Userモデルとの逆一対一または多対多の関係を定義します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}