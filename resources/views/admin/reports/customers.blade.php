@extends('admin.layouts.admin')
@section('title', 'Customer Report')
@section('header', 'Customer Report')
@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('admin.reports.customers') }}" method="GET" class="flex items-end gap-4">
        <div><label class="block text-sm text-gray-600">From</label><input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="border rounded px-3 py-2"></div>
        <div><label class="block text-sm text-gray-600">To</label><input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="border rounded px-3 py-2"></div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Apply</button>
    </form>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">New Customers</p>
        <p class="text-2xl font-bold">{{ $report['newCustomers'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Total Customers</p>
        <p class="text-2xl font-bold">{{ $report['totalCustomers'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Repeat Buyers</p>
        <p class="text-2xl font-bold">{{ $report['repeatRate'] }}</p>
    </div>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="font-bold text-lg mb-4">Top Customers</h3>
    <table class="w-full">
        <thead><tr class="border-b bg-gray-50"><th class="text-left py-3 px-4">Customer</th><th class="text-left py-3 px-4">Email</th><th class="text-left py-3 px-4">Orders</th><th class="text-left py-3 px-4">Total Spent</th></tr></thead>
        <tbody>
            @forelse($report['topCustomers'] as $c)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $c->user->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4">{{ $c->user->email ?? 'N/A' }}</td>
                    <td class="py-3 px-4">{{ $c->orders }}</td>
                    <td class="py-3 px-4">${{ number_format($c->spent, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-8 text-center text-gray-500">No data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
