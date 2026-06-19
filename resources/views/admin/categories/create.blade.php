@extends('admin.layouts.admin')
@section('title', 'Create Category')
@section('header', 'Create Category')
@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Parent Category</label>
            <select name="parent_id" class="w-full border rounded px-3 py-2">
                <option value="">None (Top Level)</option>
                @foreach($parentCategories as $pcat)
                    <option value="{{ $pcat->id }}">{{ $pcat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>
        <div class="mb-4 flex items-center space-x-6">
            <label><input type="checkbox" name="is_active" value="1" checked class="rounded"> <span class="ml-1 text-sm">Active</span></label>
            <div>
                <label class="text-sm font-medium text-gray-700">Sort Order</label>
                <input type="number" name="sort_order" value="0" class="w-20 border rounded px-2 py-1 ml-2">
            </div>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Create</button>
    </form>
</div>
@endsection
