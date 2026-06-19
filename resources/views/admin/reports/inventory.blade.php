@extends('admin.layouts.admin')
@section('title', 'Inventory Report')
@section('header', 'Inventory Report')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <table class="w-full">
        <thead><tr class="border-b bg-gray-50"><th class="text-left py-3 px-4">Product</th><th class="text-left py-3 px-4">SKU</th><th class="text-left py-3 px-4">Stock</th><th class="text-left py-3 px-4">Status</th></tr></thead>
        <tbody>
            @forelse($products as $p)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $p->name }}</td>
                    <td class="py-3 px-4">{{ $p->sku }}</td>
                    <td class="py-3 px-4">{{ $p->stock_quantity }}</td>
                    <td class="py-3 px-4">
                        @if($p->stock_quantity <= 0)
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Out of Stock</span>
                        @elseif($p->stock_quantity <= 5)
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Low Stock</span>
                        @else
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">In Stock</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-gray-500">All products are well stocked</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@push('scripts-extra')
<script>$(document).ready(function(){$('table').DataTable({paging:false,info:false,order:[]})});</script>
@endpush
