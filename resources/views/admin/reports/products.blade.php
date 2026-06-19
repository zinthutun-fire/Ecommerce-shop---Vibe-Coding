@extends('admin.layouts.admin')
@section('title', 'Top Products')
@section('header', 'Top Products')
@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('admin.reports.products') }}" method="GET" class="flex items-end gap-4">
        <div>
            <label class="block text-sm text-gray-600">From</label>
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600">To</label>
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Apply</button>
        <a href="{{ route('admin.reports.export.pdf', ['type' => 'products', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
        <a href="{{ route('admin.reports.export.csv', ['type' => 'products', 'from' => $from->format('Y-m-d'), 'to' => $to->format('Y-m-d')]) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"><i class="fas fa-file-csv mr-1"></i> CSV</a>
    </form>
</div>
<div class="bg-white rounded-lg shadow p-6">
    <div class="h-80 mb-6">
        <canvas id="productsChart"></canvas>
    </div>
    <table class="w-full">
        <thead><tr class="border-b bg-gray-50"><th class="text-left py-3 px-4">Product</th><th class="text-left py-3 px-4">Qty Sold</th><th class="text-left py-3 px-4">Revenue</th></tr></thead>
        <tbody>
            @forelse($products as $p)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium">{{ $p->product_name }}</td>
                    <td class="py-3 px-4">{{ $p->qty_sold }}</td>
                    <td class="py-3 px-4">${{ number_format($p->revenue, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="py-8 text-center text-gray-500">No data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
@push('scripts-extra')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('productsChart');
    if (!el) return;
    new Chart(el, {
        type: 'bar',
        data: {
            labels: {!! json_encode($products->pluck('product_name')) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($products->pluck('revenue')) !!},
                backgroundColor: '#4F46E5',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
});
</script>
@endpush
