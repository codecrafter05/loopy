<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'sort_order'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('menu.categories_with_items'));
        static::deleted(fn () => Cache::forget('menu.categories_with_items'));
        static::creating(function (Category $category): void {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            if ($category->sort_order === 0 && !$category->exists) {
                $category->sort_order = static::max('sort_order') + 1;
            }
        });
    }

    public function menuItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }
}
