<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'compare_price',
        'sku',
        'stock_quantity',
        'is_active',
        'featured',
        'attributes',
        'meta_title',
        'meta_description',
        'category_id',
    ];

    protected $casts = [
        'attributes' => 'array',
        'featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getPriceFormattedAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock_quantity === 0) {
            return 'Out of Stock';
        }

        if ($this->stock_quantity <= 5) {
            return 'Low Stock';
        }

        return 'In Stock';
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (!$this->compare_price) {
            return null;
        }

        return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }
}
