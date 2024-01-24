<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
}
