<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1 { color: #4F46E5; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #f3f4f6; text-align: left; padding: 8px; border-bottom: 2px solid #ddd; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .summary { margin: 20px 0; }
        .summary span { font-weight: bold; }
        .footer { margin-top: 40px; text-align: center; color: #999; font-size: 10px; }
    </style>
</head>
<body>
    <img src="{{ public_path('images/logo.png') }}" alt="ShopHub" style="height: 30px;">
    <h1>ShopHub - {{ ucfirst($type) }} Report</h1>
    <p>Period: {{ $from->format('M d, Y') }} - {{ $to->format('M d, Y') }}</p>
    <div class="summary">
        <p>Total Revenue: <span>${{ number_format($totals['total_revenue'], 2) }}</span></p>
        <p>Total Orders: <span>{{ $totals['total_orders'] }}</span></p>
        <p>Avg Order Value: <span>${{ number_format($totals['avg_order_value'], 2) }}</span></p>
    </div>
    <table>
        <thead>
            <tr>
                @if($type === 'sales')
                    <th>Month</th><th>Orders</th><th>Revenue</th>
                @elseif($type === 'products')
                    <th>Product</th><th>Qty Sold</th><th>Revenue</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr>
                    @if($type === 'sales')
                        <td>{{ $row->month }}</td><td>{{ $row->orders }}</td><td>${{ number_format($row->revenue, 2) }}</td>
                    @elseif($type === 'products')
                        <td>{{ $row->product_name }}</td><td>{{ $row->qty_sold }}</td><td>${{ number_format($row->revenue, 2) }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</div>
</body>
</html>
