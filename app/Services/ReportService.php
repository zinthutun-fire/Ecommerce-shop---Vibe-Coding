<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function overview($from = null, $to = null)
    {
        $from = $from ?? now()->startOfMonth();
        $to = $to ?? now();

        $totals = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('COUNT(*) as total_orders')
            ->selectRaw('COALESCE(SUM(total), 0) as total_revenue')
            ->selectRaw('COALESCE(AVG(total), 0) as avg_order_value')
            ->first();

        $prevPeriod = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from->copy()->subDays($from->diffInDays($to)), $from])
            ->selectRaw('COALESCE(SUM(total), 0) as revenue')
            ->first();

        $revenueGrowth = $prevPeriod->revenue > 0
            ? round((($totals->total_revenue - $prevPeriod->revenue) / $prevPeriod->revenue) * 100, 1)
            : 100;

        $newCustomers = User::whereBetween('created_at', [$from, $to])->count();
        $topProduct = OrderItem::select('product_name', DB::raw('SUM(quantity) as qty'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('product_name')
            ->orderByDesc('qty')
            ->first();

        return [
            'total_revenue' => $totals->total_revenue,
            'total_orders' => $totals->total_orders,
            'avg_order_value' => $totals->avg_order_value,
            'revenue_growth' => $revenueGrowth,
            'new_customers' => $newCustomers,
            'top_product' => $topProduct?->product_name,
        ];
    }

    public function salesByDay($from, $to)
    {
        return Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw('COALESCE(SUM(total), 0) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function salesByMonth($from, $to)
    {
        return Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month")
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw('COALESCE(SUM(total), 0) as revenue')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function salesByYear()
    {
        return Order::where('status', '!=', 'cancelled')
            ->selectRaw("DATE_FORMAT(created_at, '%Y') as year")
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw('COALESCE(SUM(total), 0) as revenue')
            ->groupBy('year')
            ->orderBy('year')
            ->get();
    }

    public function topProducts($from, $to, $limit = 10)
    {
        return OrderItem::select(
            'product_id',
            'product_name',
            DB::raw('SUM(quantity) as qty_sold'),
            DB::raw('COALESCE(SUM(total), 0) as revenue')
        )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    public function topCategories($from, $to)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereBetween('order_items.created_at', [$from, $to])
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as qty_sold'),
                DB::raw('COALESCE(SUM(order_items.total), 0) as revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('revenue')
            ->get();
    }

    public function orderStatusBreakdown($from, $to)
    {
        return Order::whereBetween('created_at', [$from, $to])
            ->selectRaw('status')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('COALESCE(SUM(total), 0) as revenue')
            ->groupBy('status')
            ->get();
    }

    public function customerReport($from, $to)
    {
        $newCustomers = User::whereBetween('created_at', [$from, $to])->count();
        $totalCustomers = User::count();

        $topCustomers = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from, $to])
            ->select('user_id', DB::raw('COUNT(*) as orders'), DB::raw('COALESCE(SUM(total), 0) as spent'))
            ->groupBy('user_id')
            ->orderByDesc('spent')
            ->limit(10)
            ->with('user:id,name,email')
            ->get();

        $repeatRate = User::has('orders', '>=', 2)->count();
        $oneTimeRate = User::has('orders', '=', 1)->count();

        return compact('newCustomers', 'totalCustomers', 'topCustomers', 'repeatRate', 'oneTimeRate');
    }

    public function inventoryReport()
    {
        return Product::select('id', 'name', 'sku', 'stock_quantity', 'is_active')
            ->where('stock_quantity', '<=', 5)
            ->orWhere('is_active', false)
            ->orderBy('stock_quantity')
            ->get();
    }
}
