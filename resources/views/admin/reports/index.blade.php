@extends('admin.layouts.admin')
@section('title', 'Reports')
@section('header', 'Reports Overview')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Total Revenue</p>
        <p class="text-2xl font-bold">${{ number_format($report['total_revenue'], 2) }}</p>
        <p class="text-sm mt-2 {{ $report['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            <i class="fas fa-{{ $report['revenue_growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> {{ $report['revenue_growth'] }}%
        </p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Total Orders</p>
        <p class="text-2xl font-bold">{{ $report['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Avg Order Value</p>
        <p class="text-2xl font-bold">${{ number_format($report['avg_order_value'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Top Product</p>
        <p class="text-lg font-bold truncate">{{ $report['top_product'] ?? 'N/A' }}</p>
    </div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('admin.reports.sales') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div><h3 class="font-bold text-lg">Sales Report</h3><p class="text-gray-500 text-sm">Daily, monthly, yearly breakdown</p></div>
            <i class="fas fa-chart-line text-3xl text-indigo-600"></i>
        </div>
    </a>
    <a href="{{ route('admin.reports.products') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div><h3 class="font-bold text-lg">Top Products</h3><p class="text-gray-500 text-sm">Best selling products by revenue</p></div>
            <i class="fas fa-crown text-3xl text-yellow-500"></i>
        </div>
    </a>
    <a href="{{ route('admin.reports.categories') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div><h3 class="font-bold text-lg">Category Report</h3><p class="text-gray-500 text-sm">Sales breakdown by category</p></div>
            <i class="fas fa-pie-chart text-3xl text-purple-600"></i>
        </div>
    </a>
    <a href="{{ route('admin.reports.customers') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div><h3 class="font-bold text-lg">Customer Report</h3><p class="text-gray-500 text-sm">New vs returning customers</p></div>
            <i class="fas fa-users text-3xl text-green-600"></i>
        </div>
    </a>
    <a href="{{ route('admin.reports.inventory') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div><h3 class="font-bold text-lg">Inventory Report</h3><p class="text-gray-500 text-sm">Low stock and out of stock items</p></div>
            <i class="fas fa-warehouse text-3xl text-orange-600"></i>
        </div>
    </a>
</div>
@endsection
