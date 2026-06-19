@extends('admin.layouts.admin')
@section('title', 'Edit Coupon')
@section('header', 'Edit Coupon')
@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Code</label>
                <input type="text" name="code" value="{{ old('code', $coupon->code) }}" class="w-full border rounded px-3 py-2 font-mono uppercase" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2">
                    <option value="fixed" {{ $coupon->type === 'fixed' ? 'selected' : '' }}>Fixed ($)</option>
                    <option value="percentage" {{ $coupon->type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Value</label>
                <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Min Order</label>
                <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Max Uses</label>
                <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Expires</label>
                <input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>
        <div class="mt-4">
            <label><input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} class="rounded"> <span class="ml-1 text-sm">Active</span></label>
        </div>
        <button type="submit" class="mt-4 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Update</button>
    </form>
</div>
@endsection
