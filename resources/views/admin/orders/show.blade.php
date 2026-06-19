@extends('admin.layouts.admin')
@section('title', 'Order ' . $order->order_number)
@section('header', 'Order #' . $order->order_number)
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <p class="text-gray-500">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="px-3 py-1 rounded font-semibold text-sm {{ match($order->status) { 'pending'=>'bg-yellow-100 text-yellow-800','processing'=>'bg-blue-100 text-blue-800','shipped'=>'bg-purple-100 text-purple-800','delivered'=>'bg-green-100 text-green-800','cancelled'=>'bg-red-100 text-red-800', default=>'bg-gray-100' } }}">{{ ucfirst($order->status) }}</span>
            <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex items-center space-x-2">
                @csrf @method('PATCH')
                <select name="status" class="border rounded px-3 py-2 text-sm">
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">Update</button>
            </form>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div>
            <h4 class="font-semibold mb-2">Shipping Address</h4>
            @if($order->shippingAddress)
                <p class="text-gray-600">{{ $order->shippingAddress->name }}</p>
                <p class="text-gray-600">{{ $order->shippingAddress->street }}</p>
                <p class="text-gray-600">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip }}</p>
                <p class="text-gray-600">{{ $order->shippingAddress->phone }}</p>
            @endif
        </div>
        <div>
            <h4 class="font-semibold mb-2">Billing Address</h4>
            @if($order->billingAddress)
                <p class="text-gray-600">{{ $order->billingAddress->name }}</p>
                <p class="text-gray-600">{{ $order->billingAddress->street }}</p>
                <p class="text-gray-600">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zip }}</p>
                <p class="text-gray-600">{{ $order->billingAddress->phone }}</p>
            @endif
        </div>
    </div>
    <table class="w-full mb-6">
        <thead class="bg-gray-50">
            <tr><th class="text-left py-3 px-4">Product</th><th class="text-left py-3 px-4">Price</th><th class="text-left py-3 px-4">Qty</th><th class="text-left py-3 px-4">Total</th></tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $item->product_name }}</td>
                    <td class="py-3 px-4">${{ number_format($item->price, 2) }}</td>
                    <td class="py-3 px-4">{{ $item->quantity }}</td>
                    <td class="py-3 px-4 font-medium">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="border-t pt-4 text-right space-y-1">
        <div class="flex justify-end"><span class="w-32 text-gray-500">Subtotal:</span><span class="w-24 text-right">${{ number_format($order->subtotal, 2) }}</span></div>
        <div class="flex justify-end"><span class="w-32 text-gray-500">Shipping:</span><span class="w-24 text-right">${{ number_format($order->shipping, 2) }}</span></div>
        <div class="flex justify-end"><span class="w-32 text-gray-500">Tax:</span><span class="w-24 text-right">${{ number_format($order->tax, 2) }}</span></div>
        @if($order->discount > 0)<div class="flex justify-end text-green-600"><span class="w-32">Discount:</span><span class="w-24 text-right">-${{ number_format($order->discount, 2) }}</span></div>@endif
        <div class="flex justify-end text-xl font-bold"><span class="w-32">Total:</span><span class="w-24 text-right">${{ number_format($order->total, 2) }}</span></div>
    </div>
</div>
@endsection
