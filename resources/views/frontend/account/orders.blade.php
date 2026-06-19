@extends('layouts.app')

@section('title', 'My Orders - ShopHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Orders</h1>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($orders->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-box-open text-5xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No orders yet.</p>
                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Start Shopping</a>
            </div>
        @else
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Order #</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Total</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = ['pending' => 'bg-yellow-100 text-yellow-800', 'processing' => 'bg-blue-100 text-blue-800', 'shipped' => 'bg-purple-100 text-purple-800', 'delivered' => 'bg-green-100 text-green-800', 'cancelled' => 'bg-red-100 text-red-800'];
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $colors[$order->status] ?? 'bg-gray-100' }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold">${{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4"><a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:underline">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
