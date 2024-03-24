<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'postal_code',
        'address',
        'phone_number',
        'fax_number',
        'email',
        'order_url',
        'how_to_order',
        'order_condition',
        'staff_name',
        'staff_phone',
    ];
    
    public function supplyItems()
    {
        return $this->hasMany(SupplyItem::class, 'company_id', 'id');
    }
}
