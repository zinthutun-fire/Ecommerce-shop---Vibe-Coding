@extends('layouts.app')

@section('title', 'Order - ShopHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <a href="{{ route('orders.index') }}" class="text-indigo-600 hover:underline mb-4 inline-block">&larr; Back to Orders</a>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Order #{{ $order->order_number }}</h1>
            @php $colors = ['pending' => 'bg-yellow-100 text-yellow-800', 'processing' => 'bg-blue-100 text-blue-800', 'shipped' => 'bg-purple-100 text-purple-800', 'delivered' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-red-100 text-red-800']; @endphp
            <span class="px-3 py-1 rounded font-semibold text-sm {{ $colors[$order->status] ?? 'bg-gray-100' }}">{{ ucfirst($order->status) }}</span>
        </div>
        <p class="text-gray-500 mb-6">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Shipping Address</h3>
                @if($order->shippingAddress)
                    <p class="text-gray-600">{{ $order->shippingAddress->name }}</p>
                    <p class="text-gray-600">{{ $order->shippingAddress->full_address }}</p>
                    <p class="text-gray-600">{{ $order->shippingAddress->phone }}</p>
                @endif
            </div>
            <div>
                <h3 class="font-semibold mb-2">Billing Address</h3>
                @if($order->billingAddress)
                    <p class="text-gray-600">{{ $order->billingAddress->name }}</p>
                    <p class="text-gray-600">{{ $order->billingAddress->full_address }}</p>
                    <p class="text-gray-600">{{ $order->billingAddress->phone }}</p>
                @endif
            </div>
        </div>
        <table class="w-full mb-6">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Product</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Price</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Qty</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-gray-500">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($order->items as $item)
                    <tr>
                        <td class="px-4 py-3">{{ $item->product_name }}</td>
                        <td class="px-4 py-3">${{ number_format($item->price, 2) }}</td>
                        <td class="px-4 py-3">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 font-medium">${{ number_format($item->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="border-t pt-4 text-right space-y-1">
            <div class="flex justify-end"><span class="w-32 text-left text-gray-500">Subtotal:</span><span class="w-24 text-right">${{ number_format($order->subtotal, 2) }}</span></div>
            <div class="flex justify-end"><span class="w-32 text-left text-gray-500">Shipping:</span><span class="w-24 text-right">${{ number_format($order->shipping, 2) }}</span></div>
            <div class="flex justify-end"><span class="w-32 text-left text-gray-500">Tax:</span><span class="w-24 text-right">${{ number_format($order->tax, 2) }}</span></div>
            @if($order->discount > 0)<div class="flex justify-end text-green-600"><span class="w-32 text-left">Discount:</span><span class="w-24 text-right">-${{ number_format($order->discount, 2) }}</span></div>@endif
            <div class="flex justify-end text-xl font-bold"><span class="w-32 text-left">Total:</span><span class="w-24 text-right">${{ number_format($order->total, 2) }}</span></div>
        </div>
    </div>
</div>
@endsection
