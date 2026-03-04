<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class LoopyMenuSeeder extends Seeder
{
    public function run(): void
    {
        MenuItem::query()->delete();
        Category::query()->delete();

        $categories = [
            ['name' => 'Donuts', 'sort_order' => 1],
            ['name' => 'Cinnabon Ball', 'sort_order' => 2],
            ['name' => 'Frappe', 'sort_order' => 3],
            ['name' => 'Ice Cream', 'sort_order' => 4],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($cat['name'])],
                $cat
            );
        }

        $items = [
            // Donuts
            ['category' => 'Donuts', 'name' => '4 pc Mix', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Nutella', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Lotus', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Kinder', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Pistachio', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Sugar', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Cheese & Chips', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Cinnamon Sugar', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '4 pc Cheese & Zaatr', 'price' => '1.200'],
            ['category' => 'Donuts', 'name' => '8 pc Mix', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Nutella', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Lotus', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Kinder', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Pistachio', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Sugar', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Cheese & Chips', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Cinnamon Sugar', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => '8 pc Cheese & Zaatr', 'price' => '1.900'],
            ['category' => 'Donuts', 'name' => 'Family Box', 'price' => '7.900'],
            ['category' => 'Donuts', 'name' => 'Large Box', 'price' => '14.900'],
            // Cinnabon Ball
            ['category' => 'Cinnabon Ball', 'name' => 'Cinnabon Cheese', 'price' => '1.900'],
            ['category' => 'Cinnabon Ball', 'name' => 'Cinnabon Cheese & Chips', 'price' => '1.900'],
            ['category' => 'Cinnabon Ball', 'name' => 'Cinnabon Cheese & Zaatr', 'price' => '1.900'],
            ['category' => 'Cinnabon Ball', 'name' => 'Cinnabon Kinder Nutella', 'price' => '1.900'],
            ['category' => 'Cinnabon Ball', 'name' => 'Cinnabon Pistachio', 'price' => '1.900'],
            // Frappe
            ['category' => 'Frappe', 'name' => 'Loopy Frappe', 'price' => '1.900'],
            ['category' => 'Frappe', 'name' => 'Mango Frappe', 'price' => '1.900'],
            ['category' => 'Frappe', 'name' => 'Pistachio Frappe', 'price' => '1.900'],
            // Ice Cream
            ['category' => 'Ice Cream', 'name' => 'Cheese Cake', 'price' => '1.400'],
            ['category' => 'Ice Cream', 'name' => 'Chocolate', 'price' => '1.400'],
        ];

        $sortOrder = 0;
        foreach ($items as $row) {
            $cat = Category::where('name', $row['category'])->first();
            if (!$cat) {
                continue;
            }
            $sortOrder++;
            MenuItem::updateOrCreate(
                [
                    'category_id' => $cat->id,
                    'slug' => \Illuminate\Support\Str::slug($row['name']),
                ],
                [
                    'name' => $row['name'],
                    'description' => null,
                    'image' => null,
                    'prices' => [['value' => $row['price']]],
                    'sort_order' => $sortOrder,
                ]
            );
        }
    }
}
