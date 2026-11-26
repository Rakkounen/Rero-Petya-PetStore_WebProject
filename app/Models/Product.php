<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'product_category_id',
    'sku',
    'name',
    'slug',
    'brand',
    'pet_species',
    'description',
    'weight',
    'cost_price',
    'price',
    'stock',
    'min_stock',
    'expired_date',
    'image',
    'is_active',
])]
class Product extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'price' => 'decimal:2',
            'expired_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
