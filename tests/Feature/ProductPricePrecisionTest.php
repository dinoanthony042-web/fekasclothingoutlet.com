<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('product price precision', function () {
    it('accepts prices up to 1,000,000 without overflowing the database column', function () {
        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 1000000,
            'stock' => 10,
            'sizes' => [],
            'colors' => ['Black'],
            'color_stock' => ['Black' => 10],
        ]);

        expect($product->price)->toBe(1000000);
    });
});
