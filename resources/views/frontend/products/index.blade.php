@extends('layouts.app')

@section('title', 'Products - ShopHub')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <aside class="w-full md:w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow p-4 sticky top-20">
                <h3 class="font-bold text-lg mb-4">Filters</h3>
                @if(isset($category))
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-600 mb-2">Category</h4>
                        <p class="text-indigo-600 font-medium">{{ $category->name }}</p>
                    </div>
                @else
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-600 mb-2">Categories</h4>
                    <ul class="space-y-1">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('categories.show', $cat->slug) }}" class="text-gray-600 hover:text-indigo-600 text-sm {{ request('category') === $cat->slug ? 'text-indigo-600 font-semibold' : '' }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-600 mb-2">Price</h4>
                    <form action="{{ route('products.index') }}" method="GET">
                        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                        @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                        <div class="flex items-center space-x-2">
                            <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full border rounded px-2 py-1 text-sm">
                            <span>-</span>
                            <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full border rounded px-2 py-1 text-sm">
                        </div>
                        <button type="submit" class="mt-2 w-full bg-indigo-600 text-white text-sm px-3 py-1 rounded hover:bg-indigo-700">Filter</button>
                    </form>
                </div>
                <div class="mb-4">
                    <h4 class="font-semibold text-gray-600 mb-2">Sort By</h4>
                    <select onchange="window.location.href=this.value" class="w-full border rounded px-2 py-1 text-sm">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>Newest</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_asc']) }}" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_desc']) }}" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'name']) }}" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    </select>
                </div>
                @if(request()->anyFilled(['category', 'search', 'min_price', 'max_price', 'sort']))
                    <a href="{{ route('products.index') }}" class="text-sm text-red-600 hover:underline">Clear all filters</a>
                @endif
            </div>
        </aside>
        <div class="flex-1">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">
                    @if(isset($category))
                        {{ $category->name }}
                    @elseif(request('search'))
                        Search: "{{ request('search') }}"
                    @else
                        All Products
                    @endif
                </h1>
                <span class="text-gray-500">{{ $products->total() }} products</span>
            </div>
            @if($products->isEmpty())
                <div class="text-center py-20">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl text-gray-500">No products found</h3>
                </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                @if($product->getFirstMediaUrl('products'))
                                    <img src="{{ $product->getFirstMediaUrl('products') }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-box text-gray-400 text-5xl"></i>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            @if($product->category)
                                <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-1 rounded">{{ $product->category->name }}</span>
                            @endif
                            <a href="{{ route('products.show', $product->slug) }}">
                                <h3 class="font-semibold text-lg mt-1">{{ $product->name }}</h3>
                            </a>
                            <p class="text-gray-500 text-sm mt-1">{{ Str::limit($product->short_description, 60) }}</p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->price, 2) }}</span>
                                @if($product->compare_price)
                                    <span class="text-sm text-gray-400 line-through">${{ number_format($product->compare_price, 2) }}</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    @if($product->stock_quantity > 0)
                                        <button type="submit" class="w-full bg-indigo-600 text-white px-3 py-2 rounded-lg hover:bg-indigo-700 text-sm transition"><i class="fas fa-cart-plus mr-1"></i>Add to Cart</button>
                                    @else
                                        <button disabled class="w-full bg-gray-300 text-gray-500 px-3 py-2 rounded-lg text-sm cursor-not-allowed">Out of Stock</button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
