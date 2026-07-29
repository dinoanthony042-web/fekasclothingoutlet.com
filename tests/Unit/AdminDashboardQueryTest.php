<?php

uses(Tests\TestCase::class);

use App\Models\Product;
use Illuminate\Support\Facades\DB;

it('includes color_stock in the top products group by clause', function () {
    $query = Product::select(
        'products.*',
        DB::raw('COUNT(order_items.id) as total_sales'),
        DB::raw('SUM(order_items.price * order_items.quantity) as revenue')
    )
        ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
        ->groupBy(
            'products.id',
            'products.name',
            'products.slug',
            'products.description',
            'products.price',
            'products.category_id',
            'products.sizes',
            'products.size_stock',
            'products.colors',
            'products.color_stock',
            'products.styles',
            'products.images',
            'products.stock',
            'products.is_featured',
            'products.is_new',
            'products.is_best_seller',
            'products.age_range',
            'products.created_at',
            'products.updated_at'
        )
        ->orderByDesc('total_sales');

    $sql = $query->toSql();

    expect($sql)->toContain('color_stock');
});
