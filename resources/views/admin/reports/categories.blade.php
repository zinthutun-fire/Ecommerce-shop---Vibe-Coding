@extends('admin.layouts.admin')
@section('title', 'Category Report')
@section('header', 'Category Report')
@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form action="{{ route('admin.reports.categories') }}" method="GET" class="flex items-end gap-4">
        <div>
            <label class="block text-sm text-gray-600">From</label>
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm text-gray-600">To</label>
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Apply</button>
    </form>
</div>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="font-bold mb-4">Category Distribution</h3>
        <div class="h-80"><canvas id="categoryChart"></canvas></div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <table class="w-full">
            <thead><tr class="border-b bg-gray-50"><th class="text-left py-3 px-4">Category</th><th class="text-left py-3 px-4">Qty Sold</th><th class="text-left py-3 px-4">Revenue</th></tr></thead>
            <tbody>
                @forelse($categories as $c)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium">{{ $c->name }}</td>
                        <td class="py-3 px-4">{{ $c->qty_sold }}</td>
                        <td class="py-3 px-4">${{ number_format($c->revenue, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="py-8 text-center text-gray-500">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@push('scripts-extra')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const el = document.getElementById('categoryChart');
    if (!el) return;
    new Chart(el, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categories->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($categories->pluck('revenue')) !!},
                backgroundColor: ['#4F46E5','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6','#F97316']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
});
</script>
@endpush
