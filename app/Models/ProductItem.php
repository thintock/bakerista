<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductItem extends Model
{
    use HasFactory;

    protected $table = 'product_items';

    protected $fillable = [
        'item_code',
        'jan_code',
        'name',
        'brand_name',
        'description',
        'item_status',
        'label_name',
        'label_kana',
        'label_sub_name',
        'label_standard',
        'label_description',
        'food_content_name',
        'food_content_ingredients',
        'food_content_volume',
        'shelf_life',
        'storage_method',
        'manufacturer_id',
        'allergen_display',
        'nutritional_energy',
        'nutritional_protein',
        'nutritional_fat',
        'nutritional_carbohydrate',
        'nutritional_salt_equivalent',
        'nutritional_ash',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'manufacturer_id');
    }
    
    public function printHistories()
    {
        return $this->hasMany(PrintHistory::class, 'product_id');
    }
}