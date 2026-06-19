<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        return view('frontend.account.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = auth()->user()->orders()->with('items', 'shippingAddress', 'billingAddress')->findOrFail($id);
        return view('frontend.account.order-detail', compact('order'));
    }
}
