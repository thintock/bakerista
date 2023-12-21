<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'materials_code',
        'materials_name',
        'materials_purchaser',
        'materials_producer_name',
        'user_id'
        ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}