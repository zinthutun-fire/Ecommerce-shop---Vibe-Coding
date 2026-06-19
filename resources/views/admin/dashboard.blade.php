@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Revenue</p>
                <p class="text-2xl font-bold">${{ number_format($report['total_revenue'], 2) }}</p>
            </div>
            <div class="bg-indigo-100 p-3 rounded-full">
                <i class="fas fa-dollar-sign text-indigo-600 text-xl"></i>
            </div>
        </div>
        <p class="text-sm mt-2 {{ $report['revenue_growth'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
            <i class="fas fa-{{ $report['revenue_growth'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
            {{ $report['revenue_growth'] }}% vs last period
        </p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Orders</p>
                <p class="text-2xl font-bold">{{ $report['total_orders'] }}</p>
            </div>
            <div class="bg-green-100 p-3 rounded-full"><i class="fas fa-shopping-bag text-green-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Products</p>
                <p class="text-2xl font-bold">{{ $totalProducts }}</p>
                <p class="text-xs text-gray-400">{{ $activeProducts }} active</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-full"><i class="fas fa-box text-purple-600 text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Users</p>
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
            </div>
            <div class="bg-yellow-100 p-3 rounded-full"><i class="fas fa-users text-yellow-600 text-xl"></i></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-lg mb-4">Monthly Sales (Last 12 Months)</h3>
        <canvas id="salesChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-lg mb-4">Recent Orders</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2">Order #</th>
                        <th class="text-left py-2">Customer</th>
                        <th class="text-left py-2">Status</th>
                        <th class="text-left py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-2"><a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:underline">{{ $order->order_number }}</a></td>
                            <td class="py-2">{{ $order->user->name }}</td>
                            <td class="py-2"><span class="px-2 py-1 rounded text-xs {{ match($order->status) { 'pending' => 'bg-yellow-100 text-yellow-800', 'processing' => 'bg-blue-100 text-blue-800', 'shipped' => 'bg-purple-100 text-purple-800', 'delivered' => 'bg-green-100 text-green-800', default => 'bg-gray-100' } }}">{{ ucfirst($order->status) }}</span></td>
                            <td class="py-2 font-medium">${{ number_format($order->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts-extra')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('salesChart');
    if (!el) return;
    new Chart(el.getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($monthlySales->pluck('revenue')) !!},
                backgroundColor: '#4F46E5',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endpush
