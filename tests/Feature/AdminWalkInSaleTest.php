<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('records a completed walk-in sale and reduces product stock', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
        'price' => 2500,
        'stock' => 4,
        'sizes' => ['M'],
        'size_stock' => ['M' => 4],
    ]);

    $this->actingAs($admin)
        ->post(route('admin.sales.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
            'size' => 'M',
        ])
        ->assertRedirect(route('admin.sales.create'))
        ->assertSessionHas('success');

    $product->refresh();
    $order = Order::latest()->first();

    expect($product->stock)->toBe(2);
    expect($product->size_stock['M'])->toBe(2);
    expect($order->user_id)->toBe($admin->id);
    expect($order->status)->toBe('delivered');
    expect($order->payment_status)->toBe('completed');
    expect($order->payment_method)->toBe('cash');
    expect($order->shipping_address['delivery_method'])->toBe('walk_in');
    expect($order->items()->first()->quantity)->toBe(2);
});

it('filters orders by online or walk-in sale type', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $onlineOrder = $admin->orders()->create([
        'total' => 1000,
        'status' => 'confirmed',
        'payment_status' => 'completed',
        'payment_method' => 'paystack',
        'shipping_address' => ['delivery_method' => 'delivery'],
    ]);
    $walkInOrder = $admin->orders()->create([
        'total' => 2000,
        'status' => 'delivered',
        'payment_status' => 'completed',
        'payment_method' => 'cash',
        'shipping_address' => ['delivery_method' => 'walk_in'],
    ]);

    $this->actingAs($admin)
        ->get(route('admin.orders.index', ['sale_type' => 'walk_in']))
        ->assertSee('#' . $walkInOrder->id)
        ->assertDontSee('#' . $onlineOrder->id);

    $this->actingAs($admin)
        ->get(route('admin.orders.index', ['sale_type' => 'online']))
        ->assertSee('#' . $onlineOrder->id)
        ->assertDontSee('#' . $walkInOrder->id);
});