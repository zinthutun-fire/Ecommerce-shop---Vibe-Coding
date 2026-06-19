<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\ReportService;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $report = $this->reportService->overview(now()->startOfMonth(), now());
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $totalUsers = User::count();
        $recentOrders = Order::latest()->with('user')->take(5)->get();
        $monthlySales = $this->reportService->salesByMonth(now()->subYear(), now());

        return view('admin.dashboard', compact(
            'report', 'totalProducts', 'activeProducts', 'totalUsers', 'recentOrders', 'monthlySales'
        ));
    }
}
