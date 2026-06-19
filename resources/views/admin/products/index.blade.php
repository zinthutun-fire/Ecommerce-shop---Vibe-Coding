@extends('admin.layouts.admin')

@section('title', 'Products')
@section('header', 'Products')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">All Products</h3>
        <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"><i class="fas fa-plus mr-1"></i> Add Product</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full" id="products-table">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="text-left py-3 px-4">Image</th>
                    <th class="text-left py-3 px-4">Name</th>
                    <th class="text-left py-3 px-4">SKU</th>
                    <th class="text-left py-3 px-4">Price</th>
                    <th class="text-left py-3 px-4">Stock</th>
                    <th class="text-left py-3 px-4">Category</th>
                    <th class="text-left py-3 px-4">Status</th>
                    <th class="text-left py-3 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">
                            @if($product->getFirstMediaUrl('products'))
                                <img src="{{ $product->getFirstMediaUrl('products') }}" alt="" class="w-12 h-12 object-cover rounded">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-medium">{{ $product->name }}</td>
                        <td class="py-3 px-4">{{ $product->sku }}</td>
                        <td class="py-3 px-4">${{ number_format($product->price, 2) }}</td>
                        <td class="py-3 px-4">{{ $product->stock_quantity }}</td>
                        <td class="py-3 px-4">{{ $product->category->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            @if($product->is_active)
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Inactive</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:underline mr-2">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection

@push('scripts-extra')
<script>
$(document).ready(function() {
    $('#products-table').DataTable({
        paging: false,
        info: false,
        order: [],
        language: { search: "Search products:" }
    });
});
</script>
@endpush
