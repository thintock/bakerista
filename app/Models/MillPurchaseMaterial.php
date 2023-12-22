<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MillPurchaseMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'materials_id',
        'arrival_date',
        'year_of_production',
        'flecon_number',
        'total_amount',
        'lot_number',
        'cost',
        'user_id'
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'materials_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
