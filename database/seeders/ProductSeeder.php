<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Luxe Satin Slip Dress',
                'slug' => 'luxe-satin-slip-dress',
                'description' => 'A luxe satin dress with a flattering bias cut and delicate straps.',
                'price' => 129.00,
                'category_id' => 1, // Women
                'sizes' => ['XS', 'S', 'M', 'L'],
                'colors' => ['Blush', 'Black', 'Ivory'],
                'styles' => ['Party', 'Evening'],
                'images' => [
                    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80',
                    'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80',
                    'https://images.unsplash.com/photo-1520975261704-7e23e5f74302?auto=format&fit=crop&w=900&q=80',
                ],
                'stock' => 24,
                'is_featured' => true,
                'is_new' => true,
                'is_best_seller' => true,
            ],
            [
                'name' => 'Tailored Ribbed Blazer',
                'slug' => 'tailored-ribbed-blazer',
                'description' => 'A structured ribbed blazer for polished day-to-night styling.',
                'price' => 149.00,
                'category_id' => 1, // Women
                'sizes' => ['XS', 'S', 'M', 'L'],
                'colors' => ['Cream', 'Black'],
                'styles' => ['Corporate', 'Casual'],
                'images' => [
                    'https://images.unsplash.com/photo-1521334884684-d80222895322?auto=format&fit=crop&w=900&q=80',
                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=900&q=80',
                ],
                'stock' => 18,
                'is_featured' => true,
                'is_new' => true,
            ],
            [
                'name' => 'Quilted Chain Shoulder Bag',
                'slug' => 'quilted-chain-shoulder-bag',
                'description' => 'A luxe quilted bag with a polished chain shoulder strap.',
                'price' => 195.00,
                'category_id' => 1, // Women
                'sizes' => [],
                'colors' => ['Blush', 'Ivory', 'Black'],
                'styles' => ['Streetwear', 'Everyday'],
                'images' => [
                    'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=80',
                    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80',
                ],
                'stock' => 32,
                'is_featured' => true,
                'is_new' => false,
                'is_best_seller' => true,
            ],
            [
                'name' => 'Sculpted Block Heel Sandals',
                'slug' => 'sculpted-block-heel-sandals',
                'description' => 'The perfect sandal with sculptural block heel and strappy detail.',
                'price' => 110.00,
                'category_id' => 1, // Women
                'sizes' => ['36', '37', '38', '39', '40'],
                'colors' => ['Nude', 'Black'],
                'styles' => ['Party', 'Streetwear'],
                'images' => [
                    'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=900&q=80',
                    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80',
                ],
                'stock' => 20,
                'is_featured' => false,
                'is_new' => true,
            ],
            [
                'name' => 'Pearl Embellished Hoops',
                'slug' => 'pearl-embellished-hoops',
                'description' => 'Elevated hoop earrings with pearl detail for a luxury finish.',
                'price' => 45.00,
                'category_id' => 1, // Women
                'sizes' => [],
                'colors' => ['Gold'],
                'styles' => ['Party', 'Everyday'],
                'images' => [
                    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=900&q=80',
                ],
                'stock' => 80,
                'is_featured' => true,
                'is_new' => false,
            ],
            [
                'name' => 'Classic Oxford Shirt',
                'slug' => 'classic-oxford-shirt',
                'description' => 'A timeless oxford shirt perfect for any occasion.',
                'price' => 89.00,
                'category_id' => 2, // Men
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['White', 'Blue', 'Gray'],
                'styles' => ['Casual', 'Business'],
                'images' => [
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80',
                ],
                'stock' => 50,
                'is_featured' => true,
                'is_new' => true,
                'is_best_seller' => false,
            ],
            [
                'name' => 'Kids Play Dress',
                'slug' => 'kids-play-dress',
                'description' => 'Comfortable and fun dress for active children.',
                'price' => 39.00,
                'category_id' => 3, // Children
                'sizes' => ['2-3Y', '4-5Y', '6-7Y'],
                'colors' => ['Pink', 'Blue', 'Yellow'],
                'styles' => ['Play', 'Casual'],
                'images' => [
                    'https://images.unsplash.com/photo-1503944168849-c1246463e59b?auto=format&fit=crop&w=900&q=80',
                ],
                'stock' => 30,
                'is_featured' => false,
                'is_new' => true,
                'is_best_seller' => true,
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}
