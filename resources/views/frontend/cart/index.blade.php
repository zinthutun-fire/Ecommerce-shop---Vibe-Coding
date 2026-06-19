@extends('layouts.app')

@section('title', 'Cart - ShopHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
    @if($cart->items->isEmpty())
        <div class="text-center py-20">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl text-gray-500">Your cart is empty</h3>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Continue Shopping</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                @foreach($cart->items as $item)
                    <div class="bg-white rounded-lg shadow p-4 mb-4 flex items-center">
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center flex-shrink-0">
                            @if($item->product && $item->product->getFirstMediaUrl('products'))
                                <img src="{{ $item->product->getFirstMediaUrl('products') }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover rounded">
                            @else
                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="font-semibold">{{ $item->product->name ?? 'Product Unavailable' }}</h3>
                            <p class="text-indigo-600 font-bold">${{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <button type="button" onclick="this.parentElement.querySelector('input[type=number]').stepDown(); this.parentElement.submit()" class="px-2 py-1 border rounded-l hover:bg-gray-100">-</button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="100" class="w-12 text-center border-t border-b py-1">
                                <button type="button" onclick="this.parentElement.querySelector('input[type=number]').stepUp(); this.parentElement.submit()" class="px-2 py-1 border rounded-r hover:bg-gray-100">+</button>
                            </form>
                        </div>
                        <div class="ml-4 text-right">
                            <p class="font-bold">${{ number_format($item->subtotal, 2) }}</p>
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-sm hover:underline">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bg-white rounded-lg shadow p-6 h-fit">
                <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                @if($cart->coupon_code)
                    <div class="flex justify-between mb-2 text-green-600">
                        <span>Coupon: {{ $cart->coupon_code }}</span>
                        <span>-${{ number_format($cart->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between mb-2">
                    <span>Subtotal</span>
                    <span>${{ number_format($cart->items->sum(fn($i) => $i->subtotal), 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Shipping</span>
                    <span>Calculated at checkout</span>
                </div>
                <hr class="my-2">
                <div class="flex justify-between text-xl font-bold">
                    <span>Total</span>
                    <span>${{ number_format($cart->total, 2) }}</span>
                </div>
                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="flex">
                        <input type="text" name="code" placeholder="Coupon code" class="flex-1 border rounded-l px-3 py-2">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-r hover:bg-gray-700">Apply</button>
                    </div>
                    @error('coupon')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </form>
                <a href="{{ route('checkout.index') }}" class="block mt-4 bg-indigo-600 text-white text-center px-6 py-3 rounded-lg hover:bg-indigo-700 font-semibold">Proceed to Checkout</a>
                <a href="{{ route('products.index') }}" class="block mt-2 text-center text-gray-600 hover:text-indigo-600">Continue Shopping</a>
            </div>
        </div>
    @endif
</div>
@endsection
