<?php

namespace App\Services;

use App\Models\Wishlist;

class WishlistService
{
    public function toggle(int $userId, int $productId): array
    {
        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return ['wishlisted' => false];
        }

        Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
        return ['wishlisted' => true];
    }

    public function getUserWishlist(int $userId)
    {
        return Wishlist::where('user_id', $userId)
            ->with('product')
            ->latest()
            ->get();
    }
}
