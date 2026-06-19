@extends('layouts.app')

@section('title', 'Checkout - ShopHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Shipping Address</h2>
                @if($addresses->isEmpty())
                    <p class="text-gray-500 mb-4">No addresses found. Please add one from your profile.</p>
                    <a href="{{ route('profile.index') }}" class="text-indigo-600 hover:underline">Add Address</a>
                @else
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        @foreach($addresses as $address)
                            <label class="block border rounded-lg p-4 mb-3 cursor-pointer hover:border-indigo-500 {{ old('shipping_address_id') == $address->id ? 'border-indigo-500 bg-indigo-50' : '' }}">
                                <input type="radio" name="shipping_address_id" value="{{ $address->id }}" {{ $loop->first ? 'checked' : '' }} class="mr-2">
                                <span class="font-medium">{{ $address->name }}</span>
                                <p class="text-sm text-gray-500 mt-1">{{ $address->full_address }}</p>
                                <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                            </label>
                        @endforeach
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Order Notes (Optional)</label>
                            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2" placeholder="Any special instructions?"></textarea>
                        </div>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold">Place Order</button>
                    </form>
                @endif
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6 h-fit">
            <h3 class="text-xl font-bold mb-4">Order Summary</h3>
            @foreach($cart->items as $item)
                <div class="flex items-center mb-2 text-sm">
                    @if($item->product && $item->product->getFirstMediaUrl('products'))
                        <img src="{{ $item->product->getFirstMediaUrl('products') }}" alt="" class="w-10 h-10 object-cover rounded mr-2">
                    @endif
                    <span class="flex-1">{{ $item->product->name ?? 'N/A' }} x{{ $item->quantity }}</span>
                    <span>${{ number_format($item->subtotal, 2) }}</span>
                </div>
            @endforeach
            <hr class="my-2">
            <div class="flex justify-between mb-2">
                <span>Subtotal</span>
                <span>${{ number_format($cart->items->sum(fn($i) => $i->subtotal), 2) }}</span>
            </div>
            @if($cart->discount > 0)
                <div class="flex justify-between mb-2 text-green-600">
                    <span>Discount</span>
                    <span>-${{ number_format($cart->discount, 2) }}</span>
                </div>
            @endif
            <div class="flex justify-between mb-2">
                <span>Estimated Tax (8%)</span>
                <span>${{ number_format($cart->items->sum(fn($i) => $i->subtotal) * 0.08, 2) }}</span>
            </div>
            <hr class="my-2">
            <div class="flex justify-between text-xl font-bold">
                <span>Total</span>
                <span>${{ number_format($cart->total, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
