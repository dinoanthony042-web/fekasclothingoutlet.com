<?php

uses(Tests\TestCase::class);

use App\Models\Category;
use App\Models\Product;

it('sums size and color stock for available inventory', function () {
    $product = new Product();
    $product->stock = 0;
    $product->size_stock = ['S' => 3, 'M' => 5];
    $product->color_stock = ['Red' => 2, 'Blue' => 4];

    expect($product->available_stock)->toBe(14);
});

it('detects bag products from the category name and slug', function () {
    $category = new Category(['name' => 'Bags', 'slug' => 'women-bags']);
    $product = new Product(['name' => 'Women Handbag']);
    $product->setRelation('category', $category);

    expect($product->isBagProduct())->toBeTrue();
});
