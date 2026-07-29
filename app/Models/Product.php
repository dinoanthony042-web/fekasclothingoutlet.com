<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'category_id', 'sizes', 'size_stock', 'colors', 'color_stock', 'styles', 'images',
        'stock', 'is_featured', 'is_new', 'is_best_seller', 'age_range'
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'styles' => 'array',
        'images' => 'array',
        'size_stock' => 'array',
        'color_stock' => 'array',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
    ];

    public function getAvailableStockAttribute()
    {
        $sizeStock = is_array($this->size_stock) ? array_sum($this->size_stock) : 0;
        $colorStock = is_array($this->color_stock) ? array_sum($this->color_stock) : 0;

        if ($sizeStock > 0 || $colorStock > 0) {
            return $sizeStock + $colorStock;
        }

        return $this->stock;
    }

    public function stockForSize(?string $size): int
    {
        if (!is_array($this->size_stock) || $size === null) {
            return $this->stock;
        }

        return (int) ($this->size_stock[$size] ?? 0);
    }

    public function decrementStock(int $quantity, ?string $size = null): void
    {
        if ($size && is_array($this->size_stock) && array_key_exists($size, $this->size_stock)) {
            $this->size_stock[$size] = max(0, $this->size_stock[$size] - $quantity);
            $this->stock = max(0, array_sum($this->size_stock));
            $this->saveQuietly();
            return;
        }

        $this->decrement('stock', $quantity);
    }

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
        $discount = $this->discounts()->active()->first();

        if ($discount) {
            return $discount;
        }

        return $this->category?->discounts()->active()->first();
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

    public function isBagProduct(): bool
    {
        $texts = collect([
            $this->name,
            $this->slug,
            $this->description,
        ]);

        $category = $this->relationLoaded('category') ? $this->category : null;

        if ($category) {
            $current = $category;
            while ($current) {
                $texts->push($current->name);
                $texts->push($current->slug);
                $current = $this->relationLoaded('category') ? null : null;
            }
        }

        foreach ($texts as $text) {
            $value = Str::lower((string) $text);
            if (Str::contains($value, 'bag') || Str::contains($value, 'bags')) {
                return true;
            }
        }

        return false;
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
