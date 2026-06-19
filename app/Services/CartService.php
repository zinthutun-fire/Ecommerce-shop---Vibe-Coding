<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getOrCreateCart($user = null)
    {
        if ($user) {
            $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        } else {
            $sessionId = Session::getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }
        return $cart->load('items.product');
    }

    public function addItem($cart, Product $product, int $quantity = 1)
    {
        $existing = $cart->items()->where('product_id', $product->id)->first();
        if ($existing) {
            $existing->update(['quantity' => $existing->quantity + $quantity, 'price' => $product->price]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }
        return $cart->fresh()->load('items.product');
    }

    public function updateItem(CartItem $item, int $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeItem($item);
        }
        $item->update(['quantity' => $quantity]);
        return $item->cart->fresh()->load('items.product');
    }

    public function removeItem(CartItem $item)
    {
        $cart = $item->cart;
        $item->delete();
        return $cart->fresh()->load('items.product');
    }

    public function clearCart($cart)
    {
        $cart->items()->delete();
        $cart->update(['coupon_code' => null, 'discount' => 0]);
        return $cart->fresh();
    }

    public function mergeGuestCart($user)
    {
        $sessionId = Session::getId();
        $guestCart = Cart::where('session_id', $sessionId)->whereNull('user_id')->first();
        if (!$guestCart || $guestCart->items->isEmpty()) {
            if ($guestCart) $guestCart->delete();
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $user->id]);
        foreach ($guestCart->items as $item) {
            $existing = $userCart->items()->where('product_id', $item->product_id)->first();
            if ($existing) {
                $existing->update(['quantity' => $existing->quantity + $item->quantity]);
            } else {
                $userCart->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }
        }
        $guestCart->delete();
    }

    public function applyCoupon($cart, string $code)
    {
        $coupon = Coupon::where('code', $code)->where('is_active', true)->first();
        if (!$coupon || !$coupon->isValid()) {
            throw new \Exception('Invalid or expired coupon.');
        }

        $subtotal = $cart->items->sum(fn($i) => $i->quantity * $i->price);
        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            throw new \Exception('Minimum order amount not met.');
        }

        $discount = $coupon->type === 'percentage'
            ? $subtotal * ($coupon->value / 100)
            : $coupon->value;

        $discount = min($discount, $subtotal);

        $cart->update([
            'coupon_code' => $code,
            'discount' => $discount,
        ]);

        $coupon->increment('used_count');

        return $cart->fresh()->load('items.product');
    }

    public function removeCoupon($cart)
    {
        $cart->update(['coupon_code' => null, 'discount' => 0]);
        return $cart->fresh()->load('items.product');
    }
}
