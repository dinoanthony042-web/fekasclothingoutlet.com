<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type', // 'percentage' or 'fixed'
        'value',
        'starts_at',
        'ends_at',
        'is_active',
        'product_id',
        'category_id',
        'category_ids',
        'apply_all_categories'
    ];

    

    protected $attributes = [
        'apply_all_categories' => false,
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'category_ids' => 'array',
        'apply_all_categories' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where('ends_at', '>=', now());
    }

    public function calculateDiscount($price)
    {
        if ($this->type === 'percentage') {
            return $price * ($this->value / 100);
        }

        return min($this->value, $price);
    }
}
