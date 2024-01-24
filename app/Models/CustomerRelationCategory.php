<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRelationCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name'
    ];
        
    public function customerRelations()
    {
        return $this->belongsToMany(CustomerRelation::class, 'customer_relation_selections');
    }
}
