<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'category_id', 'sizes', 'colors', 'styles', 'images',
        'stock', 'is_featured', 'is_new', 'is_best_seller', 'age_range'
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'styles' => 'array',
        'images' => 'array',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function activeDiscount()
    {
        return $this->discounts()->active()->first();
    }

    public function getDiscountedPriceAttribute()
    {
        $discount = $this->activeDiscount();
        if ($discount) {
            $discountAmount = $discount->calculateDiscount($this->price);
            return max(0, $this->price - $discountAmount);
        }
        return $this->price;
    }

    public function isOnSale()
    {
        return $this->activeDiscount() !== null;
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
