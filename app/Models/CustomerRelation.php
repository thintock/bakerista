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
