@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <nav class="text-sm mb-6 text-gray-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a> /
        @if($product->category)
            <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-indigo-600">{{ $product->category->name }}</a> /
        @endif
        <span class="text-gray-800">{{ $product->name }}</span>
    </nav>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="h-96 bg-gray-200 flex items-center justify-center">
                @if($product->getFirstMediaUrl('products'))
                    <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-box text-gray-400 text-8xl"></i>
                @endif
            </div>
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-2">{{ $product->name }}</h1>
            @if($product->category)
                <span class="text-sm text-indigo-600 bg-indigo-50 px-3 py-1 rounded">{{ $product->category->name }}</span>
            @endif
            <div class="mt-4">
                <span class="text-4xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                @if($product->compare_price)
                    <span class="text-xl text-gray-400 line-through ml-2">${{ number_format($product->compare_price, 2) }}</span>
                    <span class="ml-2 bg-red-100 text-red-600 text-sm px-2 py-1 rounded">Save ${{ number_format($product->compare_price - $product->price, 2) }}</span>
                @endif
            </div>
            <div class="mt-2">
                @if($product->stock_quantity > 0)
                    <span class="text-green-600"><i class="fas fa-check-circle"></i> In Stock ({{ $product->stock_quantity }} available)</span>
                @else
                    <span class="text-red-600"><i class="fas fa-times-circle"></i> Out of Stock</span>
                @endif
            </div>
            <p class="mt-4 text-gray-600">{{ $product->description }}</p>
            @if($product->sku)
                <p class="mt-2 text-sm text-gray-400">SKU: {{ $product->sku }}</p>
            @endif
            <form action="{{ route('cart.add') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center border rounded">
                        <button type="button" onclick="this.nextElementSibling.stepDown()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">-</button>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-16 text-center border-0 focus:ring-0">
                        <button type="button" onclick="this.previousElementSibling.stepUp()" class="px-3 py-2 text-gray-600 hover:bg-gray-100">+</button>
                    </div>
                    @if($product->stock_quantity > 0)
                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 font-semibold transition"><i class="fas fa-cart-plus mr-2"></i>Add to Cart</button>
                    @else
                        <button disabled class="flex-1 bg-gray-300 text-gray-500 px-8 py-3 rounded-lg cursor-not-allowed">Out of Stock</button>
                    @endif
                </div>
            </form>
            @auth
                <form action="{{ route('wishlist.toggle') }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="text-gray-600 hover:text-red-500 transition">
                        <i class="far fa-heart mr-1"></i> Add to Wishlist
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Related Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($relatedProducts as $rp)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <a href="{{ route('products.show', $rp->slug) }}">
                        <div class="h-40 bg-gray-200 flex items-center justify-center">
                            @if($rp->getFirstMediaUrl('products'))
                                <img src="{{ $rp->getFirstMediaUrl('products') }}" alt="{{ $rp->name }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-box text-gray-400 text-3xl"></i>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="font-semibold">{{ $rp->name }}</h3>
                            <span class="text-lg font-bold text-indigo-600">${{ number_format($rp->price, 2) }}</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-12 bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-6">Reviews</h2>
        @if($product->reviews->where('is_approved', true)->count() > 0)
            @foreach($product->reviews()->where('is_approved', true)->latest()->get() as $review)
                <div class="border-b pb-4 mb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold">{{ $review->user->name }}</span>
                            <span class="text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </span>
                        </div>
                        <span class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    @if($review->title)<h4 class="font-medium mt-2">{{ $review->title }}</h4>@endif
                    @if($review->body)<p class="text-gray-600 mt-1">{{ $review->body }}</p>@endif
                    @if($review->verified_purchase)
                        <span class="text-xs text-green-600"><i class="fas fa-check-circle"></i> Verified Purchase</span>
                    @endif
                </div>
            @endforeach
        @else
            <p class="text-gray-500">No reviews yet.</p>
        @endif

        @auth
            <h3 class="text-xl font-semibold mt-8 mb-4">Write a Review</h3>
            <form action="{{ route('reviews.store', $product) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select name="rating" required class="w-full border rounded px-3 py-2">
                        <option value="">Select rating</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Review</label>
                    <textarea name="body" rows="4" class="w-full border rounded px-3 py-2"></textarea>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Submit Review</button>
            </form>
        @endauth
    </div>
</div>
@endsection
