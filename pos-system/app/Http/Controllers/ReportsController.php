<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class ReportsController extends Controller
{
    // Show reports dashboard with optional date filtering
    public function index(Request $request)
    {
        // Optional date filter
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $salesQuery = Order::query();

        if ($from && $to) {
            $salesQuery->whereBetween('created_at', [$from, $to]);
        }

        // Group by day with total orders and revenue
        $salesSummary = $salesQuery
            ->selectRaw('DATE(created_at) as order_date, COUNT(*) as orders, SUM(total_price) as revenue')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) DESC')
            ->limit(30)
            ->get();

        // For totals, use fresh queries
        $totalSalesQuery = Order::query();
        $totalOrdersQuery = Order::query();

        if ($from && $to) {
            $totalSalesQuery->whereBetween('created_at', [$from, $to]);
            $totalOrdersQuery->whereBetween('created_at', [$from, $to]);
        }

        $totalSales = $totalSalesQuery->sum('total_price');
        $totalOrders = $totalOrdersQuery->count();
        $totalProducts = Product::count();

        // Fetch completed orders for receipts section
        $completedOrders = Order::with('orderItems.product')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.index', compact(
            'salesSummary',
            'totalSales',
            'totalOrders',
            'totalProducts',
            'completedOrders',
            'from',
            'to'
        ));
    }

    // Generate sales report for a given period
    public function salesReport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $sales = Order::whereBetween('created_at', [$from, $to])
                      ->orderBy('created_at', 'desc')
                      ->get();

        $totalSales = $sales->sum('total_price');

        return view('reports.sales', compact('sales', 'totalSales', 'from', 'to'));
    }

    // Export sales report as CSV
    public function exportSalesReport(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $from = $request->input('from_date');
        $to = $request->input('to_date');

        $sales = Order::whereBetween('created_at', [$from, $to])
                      ->orderBy('created_at', 'desc')
                      ->get();

        $filename = "sales_report_{$from}_to_{$to}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order ID', 'Customer Name', 'Total Price', 'Date']);

            foreach ($sales as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->customer_name,
                    number_format($order->total_price, 2),
                    $order->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
