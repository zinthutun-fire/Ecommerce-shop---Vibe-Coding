<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $report = $this->reportService->overview(now()->startOfMonth(), now());
        return view('admin.reports.index', compact('report'));
    }

    public function sales(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();
        $period = $request->get('period', 'monthly');

        $data = match ($period) {
            'daily' => $this->reportService->salesByDay($from, $to),
            'yearly' => $this->reportService->salesByYear(),
            default => $this->reportService->salesByMonth($from, $to),
        };

        $totals = $this->reportService->overview($from, $to);

        if ($request->ajax()) {
            return response()->json(compact('data', 'totals'));
        }

        return view('admin.reports.sales', compact('data', 'totals', 'from', 'to', 'period'));
    }

    public function products(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();
        $products = $this->reportService->topProducts($from, $to);

        return view('admin.reports.products', compact('products', 'from', 'to'));
    }

    public function categories(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();
        $categories = $this->reportService->topCategories($from, $to);

        return view('admin.reports.categories', compact('categories', 'from', 'to'));
    }

    public function customers(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();
        $report = $this->reportService->customerReport($from, $to);

        return view('admin.reports.customers', compact('report', 'from', 'to'));
    }

    public function inventory()
    {
        $products = $this->reportService->inventoryReport();
        return view('admin.reports.inventory', compact('products'));
    }

    public function exportPdf(Request $request, $type)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();

        $data = match ($type) {
            'sales' => $this->reportService->salesByMonth($from, $to),
            'products' => $this->reportService->topProducts($from, $to),
            default => [],
        };

        $totals = $this->reportService->overview($from, $to);

        $pdf = Pdf::loadView('admin.reports.pdf', compact('data', 'totals', 'from', 'to', 'type'));
        return $pdf->download("report-{$type}-{$from->format('Y-m-d')}-{$to->format('Y-m-d')}.pdf");
    }

    public function exportCsv(Request $request, $type)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();

        $raw = match ($type) {
            'sales' => $this->reportService->salesByMonth($from, $to),
            'products' => $this->reportService->topProducts($from, $to),
            default => collect(),
        };

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=report-{$type}-{$from->format('Y-m-d')}-{$to->format('Y-m-d')}.csv"];

        $callback = function () use ($raw, $type) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            if ($type === 'sales') {
                fputcsv($file, ['Month', 'Orders', 'Revenue']);
                foreach ($raw as $r) {
                    fputcsv($file, [$r->month, $r->orders, number_format($r->revenue, 2)]);
                }
            } elseif ($type === 'products') {
                fputcsv($file, ['Product', 'Qty Sold', 'Revenue']);
                foreach ($raw as $r) {
                    fputcsv($file, [$r->product_name, $r->qty_sold, number_format($r->revenue, 2)]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
