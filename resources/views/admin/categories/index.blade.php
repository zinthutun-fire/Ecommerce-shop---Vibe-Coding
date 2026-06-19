@extends('admin.layouts.admin')

@section('title', 'Categories')
@section('header', 'Categories')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">All Categories</h3>
        <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"><i class="fas fa-plus mr-1"></i> Add Category</a>
    </div>
    <table class="w-full" id="categories-table">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left py-3 px-4">Name</th>
                <th class="text-left py-3 px-4">Slug</th>
                <th class="text-left py-3 px-4">Parent</th>
                <th class="text-left py-3 px-4">Products</th>
                <th class="text-left py-3 px-4">Status</th>
                <th class="text-left py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $cat->name }}</td>
                    <td class="py-3 px-4">{{ $cat->slug }}</td>
                    <td class="py-3 px-4">{{ $cat->parent->name ?? '—' }}</td>
                    <td class="py-3 px-4">{{ $cat->products_count }}</td>
                    <td class="py-3 px-4">{!! $cat->is_active ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>' : '<span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Inactive</span>' !!}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="text-indigo-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $categories->links() }}</div>
</div>
@endsection
@push('scripts-extra')
<script>$(document).ready(function(){$('#categories-table').DataTable({paging:false,info:false,order:[]})});</script>
@endpush
