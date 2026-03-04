<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    protected $fillable = ['category_id', 'name', 'slug', 'description', 'image', 'prices', 'sort_order'];

    protected $casts = [
        'prices' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (MenuItem $item): void {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name);
            }
            if ($item->sort_order === 0 && !$item->exists) {
                $max = static::max('sort_order');
                $item->sort_order = ($max ?? 0) + 1;
            }
        });
        static::saved(fn () => Cache::forget('menu.categories_with_items'));
        static::deleted(fn () => Cache::forget('menu.categories_with_items'));
        static::saving(function (MenuItem $item): void {
            if (is_array($item->prices)) {
                $item->prices = collect($item->prices)
                    ->filter(fn ($p) => !empty($p['value'] ?? null))
                    ->map(fn ($p) => array_filter([
                        'label' => trim($p['label'] ?? '') ?: null,
                        'value' => (string) ($p['value'] ?? ''),
                    ], fn ($v) => $v !== null))
                    ->values()
                    ->all();
            }
        });
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }
        return Storage::disk('public')->url($this->image);
    }
}
