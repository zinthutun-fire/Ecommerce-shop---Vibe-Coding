@extends('admin.layouts.admin')
@section('title', 'Sales Report')
@section('header', 'Sales Report')
@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="font-bold text-lg mb-4">Filter</h3>
    <form action="{{ route('admin.reports.sales') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm text-gray-600">From</label>
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600">To</label>
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600">Period</label>
            <select name="period" class="border rounded px-3 py-2">
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Daily</option>
                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Apply</button>
        <a href="{{ route('admin.reports.export.pdf', ['type' => 'sales', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
        <a href="{{ route('admin.reports.export.csv', ['type' => 'sales', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"><i class="fas fa-file-csv mr-1"></i> CSV</a>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Total Revenue</p>
        <p class="text-2xl font-bold">${{ number_format($totals['total_revenue'], 2) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Orders</p>
        <p class="text-2xl font-bold">{{ $totals['total_orders'] }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-gray-500 text-sm">Avg Order Value</p>
        <p class="text-2xl font-bold">${{ number_format($totals['avg_order_value'], 2) }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <canvas id="salesChart" height="100"></canvas>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <table class="w-full">
        <thead><tr class="border-b bg-gray-50"><th class="text-left py-3 px-4">{{ $period === 'daily' ? 'Date' : ($period === 'yearly' ? 'Year' : 'Month') }}</th><th class="text-left py-3 px-4">Orders</th><th class="text-left py-3 px-4">Revenue</th></tr></thead>
        <tbody>
            @forelse($data as $row)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $period === 'daily' ? $row->date : ($period === 'yearly' ? $row->year : $row->month) }}</td>
                    <td class="py-3 px-4">{{ $row->orders }}</td>
                    <td class="py-3 px-4">${{ number_format($row->revenue, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-8 text-center text-gray-500">No data available</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts-extra')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('salesChart');
    if (!el) return;
    new Chart(el, {
        type: 'bar',
        data: {
            labels: {!! json_encode($data->pluck($period === 'daily' ? 'date' : ($period === 'yearly' ? 'year' : 'month'))) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($data->pluck('revenue')) !!},
                backgroundColor: '#4F46E5',
                borderRadius: 4
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
