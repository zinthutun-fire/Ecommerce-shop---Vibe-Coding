@extends('admin.layouts.admin')

@section('title', 'Create Product')
@section('header', 'Create Product')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Short Description</label>
                <input type="text" name="short_description" value="{{ old('short_description') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="4" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Compare Price</label>
                <input type="number" step="0.01" name="compare_price" value="{{ old('compare_price') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">SKU</label>
                <input type="text" name="sku" value="{{ old('sku') }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" class="w-full border rounded px-3 py-2">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300">
                    <span class="ml-2 text-sm">Active</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="featured" value="1" class="rounded border-gray-300">
                    <span class="ml-2 text-sm">Featured</span>
                </label>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="file" name="image" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Create Product</button>
    </form>
</div>
@endsection
