<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cart = $this->cartService->getOrCreateCart(auth()->user());
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $addresses = auth()->user()->addresses;
        return view('frontend.checkout.index', compact('cart', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
        ]);

        $cart = $this->cartService->getOrCreateCart(auth()->user());
        if ($cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $order = $this->orderService->createFromCart($cart, $request->only(['shipping_address_id', 'notes']));

        return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');
    }
}
