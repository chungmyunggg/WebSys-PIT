<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        // Dashboard summary
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $revenue = Order::sum('total_price');

        // Recently placed orders
        $recentOrders = Order::with('orderItems.product')
                             ->latest()
                             ->take(5)
                             ->get();

        // Products that are low in stock
        $lowStockProducts = Product::where('quantity', '<', 5)->get();

        // Latest 5 available products for quick order
        $availableProducts = Product::orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalOrders',
            'revenue',
            'recentOrders',
            'lowStockProducts',
            'availableProducts'
        ));
    }
}
