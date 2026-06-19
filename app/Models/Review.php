<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'body',
        'images',
        'is_approved',
        'verified_purchase',
    ];

    protected $casts = [
        'images' => 'array',
        'is_approved' => 'boolean',
        'verified_purchase' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
