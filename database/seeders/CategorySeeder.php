<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Men' => [
                ['name' => 'Suits', 'description' => 'Sharp suits and tailored sets for men.'],
                ['name' => 'Shirts', 'description' => 'Casual and formal shirts for every occasion.'],
                ['name' => 'Jeans', 'description' => 'Denim and trousers in multiple fits.'],
                ['name' => 'Shoes', 'description' => 'Dress shoes, sneakers and boots.'],
            ],
            'Women' => [
                ['name' => 'Dresses', 'description' => 'Elegant dresses for day and evening wear.'],
                ['name' => 'Tops', 'description' => 'Blouses, shirts, and stylish tops.'],
                ['name' => 'Skirts', 'description' => 'Mini, midi, and maxi skirts.'],
                ['name' => 'Bags', 'description' => 'Handbags, clutches, and everyday carry.'],
            ],
            'Kids' => [
                ['name' => 'Tops', 'description' => 'Comfortable tops and tees for kids.'],
                ['name' => 'Bottoms', 'description' => 'Shorts, pants, and leggings for active kids.'],
                ['name' => 'Outerwear', 'description' => 'Jackets and hoodies for playtime and school.'],
                ['name' => 'Shoes', 'description' => 'Durable shoes and sandals for children.'],
            ],
        ];

        foreach ($categories as $parentName => $subcategories) {
            $parent = Category::firstOrCreate(
                ['slug' => strtolower($parentName)],
                ['name' => $parentName, 'description' => "$parentName clothing and accessories"]
            );

            foreach ($subcategories as $subcategory) {
                Category::firstOrCreate(
                    ['slug' => strtolower($parentName) . '-' . str_replace(' ', '-', strtolower($subcategory['name'])), 'parent_id' => $parent->id],
                    ['name' => $subcategory['name'], 'description' => $subcategory['description'], 'parent_id' => $parent->id]
                );
            }
        }
    }
}
