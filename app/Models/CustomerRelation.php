<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRelation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'received_at',
        'received_by_user_id',
        'reception_channel',
        'initial_content',
        'product_name',
        'customer_name',
        'contact_number',
        'link',
        'images',
        'needs_health_department_contact',
        'health_department_contact_details',
        'is_finished'
    ];
    
    // 日付として扱う属性を追加
    protected $dates = ['received_at'];
    
    // スコープメソッド: 受付日時での絞り込み
    public function scopeReceivedAtBetween($query, $start, $end)
    {
        // dump($query);
        // dump($start);
        // dd($end);
        if (!empty($start) && !empty($end)) {
            return $query->whereBetween('received_at', [$start, $end]);
        } elseif (!empty($start)) {
            return $query->where('received_at', '>=', $start);
        } elseif (!empty($end)) {
            return $query->where('received_at', '<=', $end);
        }
    }

    // スコープメソッド: 受付担当者での絞り込み
    public function scopeReceivedByUserId($query, $userId)
    {
        if (!empty($userId)) {
            return $query->where('received_by_user_id', $userId);
        }
    }

    // スコープメソッド: お客様名での検索
    public function scopeCustomerName($query, $name)
    {
        if (!empty($name)) {
            return $query->where('customer_name', 'like', '%' . $name . '%');
        }
    }

    // スコープメソッド: 電話番号での検索
    public function scopeContactNumber($query, $number)
    {
        if (!empty($number)) {
            return $query->where('contact_number', 'like', '%' . $number . '%');
        }
    }

    // スコープメソッド: 受付場所での検索
    public function scopeReceptionChannel($query, $channel)
    {
        if (!empty($channel)) {
            return $query->where('reception_channel', 'like', '%' . $channel . '%');
        }
    }

    // スコープメソッド: 初期受付内容での検索
    public function scopeInitialContent($query, $content)
    {
        if (!empty($content)) {
            return $query->where('initial_content', 'like', '%' . $content . '%');
        }
    }

    // スコープメソッド: 完了フラグでの検索
    public function scopeIsFinished($query, $isFinished)
    {
        if (isset($isFinished)) {
            return $query->where('is_finished', $isFinished);
        }
    }

    // スコープメソッド: カテゴリでの絞り込み
    public function scopeCategory($query, $categoryId)
    {
        if (!empty($categoryId)) {
            return $query->whereHas('customerRelationCategories', function ($q) use ($categoryId) {
                $q->where('customer_relation_categories.id', $categoryId);
            });
        }
    }
    
    // スコープメソッド： 担当部署での絞り込み
    public function scopeDepartment($query, $department)
    {
        if (!empty($department)) {
            return $query->whereHas('customerRelationCategories', function ($q) use($department) {
                $q->where('department', 'like', '%' . $department . '%');
            });
        }
    }
    
    // Userモデルとの関連付け
    public function user()
    {
        return $this->belongsTo(User::class, 'received_by_user_id');
    }
    
    // CustomerRelationCategoryとの関連付け
    public function customerRelationCategories()
    {
        return $this->belongsToMany(CustomerRelationCategory::class, 'customer_relation_selections');
    }
    
    // CustomerRelationHistoryとの関連付け
    public function customerRelationHistories()
    {
        return $this->hasMany(CustomerRelationHistory::class);
    }
}
