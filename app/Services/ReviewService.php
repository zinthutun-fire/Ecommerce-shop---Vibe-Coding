<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Review;

class ReviewService
{
    public function create(array $data): Review
    {
        $data['verified_purchase'] = $this->isVerifiedPurchase($data['user_id'], $data['product_id']);
        return Review::create($data);
    }

    public function approve(Review $review): Review
    {
        $review->update(['is_approved' => true]);
        return $review;
    }

    public function reject(Review $review): Review
    {
        $review->delete();
        return $review;
    }

    private function isVerifiedPurchase($userId, $productId): bool
    {
        return Order::where('user_id', $userId)
            ->where('status', 'delivered')
            ->whereHas('items', fn($q) => $q->where('product_id', $productId))
            ->exists();
    }
}
