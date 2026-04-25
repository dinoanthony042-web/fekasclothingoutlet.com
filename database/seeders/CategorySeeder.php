<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Women', 'slug' => 'women', 'description' => 'Elegant clothing and accessories for women'],
            ['name' => 'Men', 'slug' => 'men', 'description' => 'Stylish clothing and accessories for men'],
            ['name' => 'Children', 'slug' => 'children', 'description' => 'Comfortable and fun clothing for children'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
