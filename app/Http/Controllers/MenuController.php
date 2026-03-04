<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $categories = Cache::remember('menu.categories_with_items', 300, function () {
            return Category::query()
                ->with(['menuItems' => fn ($q) => $q->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get()
                ->filter(fn ($cat) => $cat->menuItems->isNotEmpty());
        });

        return view('index', compact('categories'));
    }
}
