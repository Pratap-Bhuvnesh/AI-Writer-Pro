<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    protected $fillable = [
    'product_variant_id',
    'stock_quantity',
    'reserved_quantity',
    'low_stock_threshold'
];  
public function variant()
{
    return $this->belongsTo(ProductVariant::class, 'product_variant_id');
}
}
