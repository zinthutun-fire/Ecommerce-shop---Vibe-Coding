@extends('admin.layouts.admin')

@section('title', 'Edit Product')
@section('header', 'Edit Product')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description', $product->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Compare Price</label>
                <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">SKU</label>
                <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" class="w-full border rounded px-3 py-2">
                    <option value="">Select</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-4">
                <label><input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="rounded"> <span class="ml-1 text-sm">Active</span></label>
                <label><input type="checkbox" name="featured" value="1" {{ $product->featured ? 'checked' : '' }} class="rounded"> <span class="ml-1 text-sm">Featured</span></label>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Image</label>
                <input type="file" name="image" class="w-full border rounded px-3 py-2">
                @if($product->getFirstMediaUrl('products'))
                    <img src="{{ $product->getFirstMediaUrl('products') }}" alt="" class="mt-2 h-20 rounded">
                @endif
            </div>
        </div>
        <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Update Product</button>
    </form>
</div>
@endsection
