<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Featured', 'sort_order' => 1],
            ['name' => 'Donuts', 'sort_order' => 2],
            ['name' => 'Signature Donuts', 'sort_order' => 3],
            ['name' => 'Hot Coffee', 'sort_order' => 4],
            ['name' => 'Iced Coffee', 'sort_order' => 5],
            ['name' => 'Non-Coffee', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($cat['name'])],
                $cat
            );
        }

        $items = [
            ['category' => 'Featured', 'name' => 'Pistachio Rose', 'desc' => 'Soft glaze, crushed pistachio, a subtle floral finish.', 'prices' => [['label' => '1 pc', 'value' => '1.200']]],
            ['category' => 'Featured', 'name' => 'Iced Spanish Latte', 'desc' => 'Bold espresso, silky milk, gentle sweetness.', 'prices' => [['label' => '12oz', 'value' => '2.100'], ['label' => '16oz', 'value' => '2.400']]],
            ['category' => 'Featured', 'name' => 'Classic Cinnamon', 'desc' => 'Warm cinnamon sugar, light vanilla crumb.', 'prices' => [['label' => '1 pc', 'value' => '0.950']]],
            ['category' => 'Donuts', 'name' => 'Sugar Cloud', 'desc' => 'Powdered sugar finish — airy and light.', 'prices' => [['label' => '1 pc', 'value' => '0.900']]],
            ['category' => 'Donuts', 'name' => 'Chocolate Glaze', 'desc' => 'Deep cocoa glaze with a clean bite.', 'prices' => [['label' => '1 pc', 'value' => '1.000']]],
            ['category' => 'Donuts', 'name' => 'Vanilla Sprinkle', 'desc' => 'Vanilla glaze + pastel sprinkles.', 'prices' => [['label' => '1 pc', 'value' => '1.050']]],
            ['category' => 'Donuts', 'name' => 'Caramel Sea Salt', 'desc' => 'Caramel glaze with a gentle salty pop.', 'prices' => [['label' => '1 pc', 'value' => '1.150']]],
            ['category' => 'Donuts', 'name' => 'Strawberry Milk', 'desc' => 'Creamy strawberry glaze, soft & sweet.', 'prices' => [['label' => '1 pc', 'value' => '1.100']]],
            ['category' => 'Donuts', 'name' => 'Espresso Dust', 'desc' => 'Espresso sugar, cocoa, and calm bitterness.', 'prices' => [['label' => '1 pc', 'value' => '1.150']]],
            ['category' => 'Signature Donuts', 'name' => 'Lotus Crunch', 'desc' => 'Biscoff glaze, cookie crumble, caramel warmth.', 'prices' => [['label' => '1 pc', 'value' => '1.300']]],
            ['category' => 'Signature Donuts', 'name' => 'Matcha White', 'desc' => 'Matcha glaze with white chocolate drizzle.', 'prices' => [['label' => '1 pc', 'value' => '1.350']]],
            ['category' => 'Signature Donuts', 'name' => 'Cookies & Cream', 'desc' => 'Oreo crumble, vanilla glaze, clean finish.', 'prices' => [['label' => '1 pc', 'value' => '1.350']]],
            ['category' => 'Hot Coffee', 'name' => 'Americano', 'desc' => 'Clean espresso with hot water — crisp and simple.', 'prices' => [['label' => '8oz', 'value' => '1.200'], ['label' => '12oz', 'value' => '1.500']]],
            ['category' => 'Hot Coffee', 'name' => 'Latte', 'desc' => 'Silky steamed milk with balanced espresso.', 'prices' => [['label' => '8oz', 'value' => '1.700'], ['label' => '12oz', 'value' => '1.900']]],
            ['category' => 'Hot Coffee', 'name' => 'Cappuccino', 'desc' => 'Foamy, aromatic, and classic.', 'prices' => [['label' => '8oz', 'value' => '1.700']]],
            ['category' => 'Iced Coffee', 'name' => 'Iced Latte', 'desc' => 'Chilled milk + espresso — clean and soft.', 'prices' => [['label' => '12oz', 'value' => '2.000'], ['label' => '16oz', 'value' => '2.300']]],
            ['category' => 'Iced Coffee', 'name' => 'Cold Brew', 'desc' => 'Slow-brewed for low acidity and extra smoothness.', 'prices' => [['label' => '12oz', 'value' => '2.200'], ['label' => '16oz', 'value' => '2.600']]],
            ['category' => 'Non-Coffee', 'name' => 'Strawberry Milk', 'desc' => 'Creamy strawberry — nostalgic and soft.', 'prices' => [['label' => '12oz', 'value' => '1.900'], ['label' => '16oz', 'value' => '2.200']]],
            ['category' => 'Non-Coffee', 'name' => 'Vanilla Shake', 'desc' => 'Thick, smooth, and classic.', 'prices' => [['label' => '16oz', 'value' => '2.700']]],
        ];

        $sortOrder = 0;
        foreach ($items as $row) {
            $cat = Category::where('name', $row['category'])->first();
            if (!$cat) continue;
            $sortOrder++;
            MenuItem::updateOrCreate(
                ['category_id' => $cat->id, 'slug' => \Illuminate\Support\Str::slug($row['name'])],
                [
                    'name' => $row['name'],
                    'description' => $row['desc'],
                    'prices' => $row['prices'],
                    'sort_order' => $sortOrder,
                ]
            );
        }
    }
}
