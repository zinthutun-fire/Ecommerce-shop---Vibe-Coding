@extends('layouts.app')

@section('title', 'Home - ShopHub')

@section('content')
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="text-center">
            <h1 class="text-5xl font-bold mb-4">Welcome to ShopHub</h1>
            <p class="text-xl mb-8">Discover amazing products at unbeatable prices</p>
            <a href="{{ route('products.index') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">Shop Now</a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->getFirstMediaUrl('products'))
                            <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-box text-gray-400 text-5xl"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                            @if($product->compare_price)
                                <span class="text-sm text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                            @endif
                        </div>
                        @if($product->stock_quantity > 0)
                            <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition"><i class="fas fa-cart-plus mr-2"></i>Add to Cart</button>
                            </form>
                        @else
                            <button disabled class="w-full mt-3 bg-gray-300 text-gray-500 px-4 py-2 rounded-lg cursor-not-allowed">Out of Stock</button>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<div class="bg-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8">Shop by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="bg-white p-6 rounded-lg shadow text-center hover:shadow-lg transition">
                    <div class="text-4xl mb-2 text-indigo-600"><i class="fas fa-folder"></i></div>
                    <h3 class="font-semibold">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->products_count }} products</p>
                </a>
            @endforeach
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold mb-8">New Arrivals</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($newProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->getFirstMediaUrl('products'))
                            <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-box text-gray-400 text-5xl"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold">{{ $product->name }}</h3>
                        <span class="text-xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
