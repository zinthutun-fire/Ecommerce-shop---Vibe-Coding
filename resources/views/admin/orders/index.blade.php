@extends('admin.layouts.admin')
@section('title', 'Orders')
@section('header', 'Orders')
@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="flex space-x-2 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="px-3 py-1 rounded {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">All</a>
        @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
            <a href="{{ route('admin.orders.index', ['status' => $s]) }}" class="px-3 py-1 rounded {{ request('status') === $s ? 'bg-indigo-600 text-white' : 'bg-gray-200' }}">{{ ucfirst($s) }}</a>
        @endforeach
    </div>
    <table class="w-full" id="orders-table">
        <thead>
            <tr class="border-b bg-gray-50">
                <th class="text-left py-3 px-4">Order #</th>
                <th class="text-left py-3 px-4">Customer</th>
                <th class="text-left py-3 px-4">Date</th>
                <th class="text-left py-3 px-4">Total</th>
                <th class="text-left py-3 px-4">Status</th>
                <th class="text-left py-3 px-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $order->order_number }}</td>
                    <td class="py-3 px-4">{{ $order->user->name }}</td>
                    <td class="py-3 px-4 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="py-3 px-4 font-medium">${{ number_format($order->total, 2) }}</td>
                    <td class="py-3 px-4"><span class="px-2 py-1 rounded text-xs {{ match($order->status) { 'pending'=>'bg-yellow-100 text-yellow-800','processing'=>'bg-blue-100 text-blue-800','shipped'=>'bg-purple-100 text-purple-800','delivered'=>'bg-green-100 text-green-800','cancelled'=>'bg-red-100 text-red-800', default=>'bg-gray-100' } }}">{{ ucfirst($order->status) }}</span></td>
                    <td class="py-3 px-4"><a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
@push('scripts-extra')
<script>$(document).ready(function(){$('#orders-table').DataTable({paging:false,info:false,order:[]})});</script>
@endpush
