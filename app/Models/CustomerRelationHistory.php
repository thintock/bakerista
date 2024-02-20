<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class CustomerRelationHistory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'customer_relation_id',
        'respondent_user_id',
        'response_category',
        'response_content'
    ];
    
    public function User()
    {
        return $this->belongsTo(User::class, 'respondent_user_id');
    }
    
    public static function updateHistories($updateHistories,)
    {
        // 既存履歴の更新処理
        if (!empty($updateHistories)) {
            foreach ($updateHistories as $id => $data) {
                $history = self::find($id);
                if ($history) {
                    $history->fill($data);
                    if ($history->isDirty()) { // 変更がある場合のみ更新
                        $history->save();
                    }
                }
            }
        }
    }
    
    public static function deleteHistories($deleteHistories,)
    {
        // 履歴の削除処理
        if (!empty($deleteHistories)) {
            self::destroy($deleteHistories);
        }
    }
    
    public static function newHistories($customerRelation, $newHistories)
    {
        // 新規履歴の追加処理
        if (!empty($newHistories)) {
            foreach ($newHistories as $historyData) {
                // response_contentがnullまたは空文字の場合はスキップ
                if (empty($historyData['response_content'])) {
                    continue;
                }
    
                $customerRelation->customerRelationHistories()->create([
                    'customer_relation_id' => $customerRelation->id,
                    'respondent_user_id' => Auth::id(),
                    'response_category' => $historyData['response_category'],
                    'response_content' => $historyData['response_content']
                ]);
            }
        }
    }

}
