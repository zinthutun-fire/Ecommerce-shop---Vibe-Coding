@extends('admin.layouts.admin')
@section('title', 'Coupons')
@section('header', 'Coupons')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold">All Coupons</h3>
        <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"><i class="fas fa-plus mr-1"></i> Add Coupon</a>
    </div>
    <table class="w-full" id="coupons-table">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left py-3 px-4">Code</th>
                <th class="text-left py-3 px-4">Value</th>
                <th class="text-left py-3 px-4">Type</th>
                <th class="text-left py-3 px-4">Used/Max</th>
                <th class="text-left py-3 px-4">Expires</th>
                <th class="text-left py-3 px-4">Status</th>
                <th class="text-left py-3 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coupons as $coupon)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-mono font-bold">{{ $coupon->code }}</td>
                    <td class="py-3 px-4">{{ $coupon->type === 'percentage' ? $coupon->value . '%' : '$' . number_format($coupon->value, 2) }}</td>
                    <td class="py-3 px-4">{{ ucfirst($coupon->type) }}</td>
                    <td class="py-3 px-4">{{ $coupon->used_count }}/{{ $coupon->max_uses ?? '∞' }}</td>
                    <td class="py-3 px-4 text-sm">{{ $coupon->expires_at ? $coupon->expires_at->format('M d, Y') : 'Never' }}</td>
                    <td class="py-3 px-4">{!! $coupon->is_active ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Active</span>' : '<span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Inactive</span>' !!}</td>
                    <td class="py-3 px-4">
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-indigo-600 hover:underline mr-2">Edit</a>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:underline">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $coupons->links() }}</div>
</div>
@endsection
@push('scripts-extra')
<script>$(document).ready(function(){$('#coupons-table').DataTable({paging:false,info:false,order:[]})});</script>
@endpush
