<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'parent_id'];

    public const ROOT_CATEGORY_NAMES = ['Men', 'Women', 'Kids'];

    public static function rootCategoryNames(): array
    {
        return self::ROOT_CATEGORY_NAMES;
    }

    public static function rootCategories()
    {
        return self::whereNull('parent_id')
            ->whereIn('name', self::ROOT_CATEGORY_NAMES)
            ->orderBy('name');
    }

    public static function ensureRootCategoriesExist()
    {
        foreach (self::ROOT_CATEGORY_NAMES as $name) {
            $slug = Str::slug($name);
            self::firstOrCreate(
                ['slug' => $slug, 'parent_id' => null],
                ['name' => $name, 'description' => null]
            );
        }

        return self::rootCategories()->get();
    }

    public function isRootCategory(): bool
    {
        return $this->parent_id === null && in_array(Str::title($this->name), self::ROOT_CATEGORY_NAMES, true);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function allProducts()
    {
        return $this->hasManyThrough(Product::class, Category::class, 'parent_id', 'category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
