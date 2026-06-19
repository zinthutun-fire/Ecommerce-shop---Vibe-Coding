<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class OrderService
{
    public function createFromCart(Cart $cart, array $data): Order
    {
        $items = $cart->items()->with('product')->get();
        $subtotal = $items->sum(fn($i) => $i->quantity * $i->price);
        $discount = $cart->discount ?? 0;
        $tax = $subtotal * 0.08;
        $shipping = $subtotal >= 100 ? 0 : 9.99;
        $total = $subtotal + $tax + $shipping - $discount;

        $order = Order::create([
            'user_id' => $cart->user_id,
            'order_number' => $this->generateOrderNumber(),
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => max($total, 0),
            'shipping_address_id' => $data['shipping_address_id'] ?? null,
            'billing_address_id' => $data['billing_address_id'] ?? $data['shipping_address_id'] ?? null,
            'coupon_code' => $cart->coupon_code,
            'notes' => $data['notes'] ?? null,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->quantity * $item->price,
            ]);
        }

        $cart->items()->delete();
        $cart->update(['coupon_code' => null, 'discount' => 0]);

        return $order->load('items');
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->update(['status' => $status]);
        if ($status === 'delivered' && !$order->paid_at) {
            $order->update(['paid_at' => now()]);
        }
        return $order;
    }

    public function cancelOrder(Order $order): Order
    {
        if (!in_array($order->status, ['pending', 'processing'])) {
            throw new \Exception('Order cannot be cancelled.');
        }
        return $this->updateStatus($order, 'cancelled');
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . strtoupper(Str::random(8)) . '-' . now()->format('Ymd');
    }
}
