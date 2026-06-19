@extends('layouts.app')

@section('title', 'Wishlist - ShopHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">My Wishlist</h1>
    @if($wishlists->isEmpty())
        <div class="text-center py-20">
            <i class="far fa-heart text-6xl text-gray-300 mb-4"></i>
            <p class="text-xl text-gray-500">Your wishlist is empty</p>
            <a href="{{ route('products.index') }}" class="mt-4 inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Browse Products</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($wishlists as $wishlist)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <a href="{{ route('products.show', $wishlist->product->slug) }}">
                        <div class="h-40 bg-gray-200 flex items-center justify-center">
                            @if($wishlist->product->getFirstMediaUrl('products'))
                                <img src="{{ $wishlist->product->getFirstMediaUrl('products') }}" alt="" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-box text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold">{{ $wishlist->product->name }}</h3>
                            <span class="text-lg font-bold text-indigo-600">${{ number_format($wishlist->product->price, 2) }}</span>
                        </div>
                    </a>
                    <div class="px-3 pb-3 flex space-x-2">
                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $wishlist->product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-indigo-600 text-white text-sm px-3 py-2 rounded hover:bg-indigo-700"><i class="fas fa-cart-plus mr-1"></i>Add to Cart</button>
                        </form>
                        <form action="{{ route('wishlist.toggle') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $wishlist->product->id }}">
                            <button type="submit" class="text-red-500 px-3 py-2 border border-red-300 rounded hover:bg-red-50"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
