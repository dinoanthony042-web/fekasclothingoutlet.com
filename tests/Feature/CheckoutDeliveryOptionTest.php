<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('checkout delivery options', function () {
    it('stores the selected delivery method on checkout', function () {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 100,
            'stock' => 10,
            'sizes' => [],
            'colors' => ['Black'],
            'color_stock' => ['Black' => 10],
        ]);

        $user->carts()->create([
            'product_id' => $product->id,
            'quantity' => 1,
            'color' => 'Black',
        ]);

        $this->actingAs($user)
            ->post(route('checkout.store'), [
                'shipping_name' => 'Jane Doe',
                'shipping_phone' => '09000000000',
                'shipping_street' => '1 Main Street',
                'shipping_city' => 'Lagos',
                'shipping_state' => 'Lagos',
                'shipping_postcode' => '100001',
                'shipping_country' => 'Nigeria',
                'payment_method' => 'korapay',
                'delivery_method' => 'pickup',
            ])
            ->assertRedirect();

        $order = $user->orders()->latest()->first();

        expect($order)->not->toBeNull();
        expect($order->shipping_address['delivery_method'] ?? null)->toBe('pickup');
    });
});
