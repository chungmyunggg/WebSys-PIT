@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6">Reports</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-lg font-semibold text-gray-700">Total Sales</h2>
            <p class="text-2xl font-bold text-green-600 mt-2">₱{{ number_format($totalSales, 2) }}</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-lg font-semibold text-gray-700">Total Orders</h2>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white shadow p-4 rounded">
            <h2 class="text-lg font-semibold text-gray-700">Total Products</h2>
            <p class="text-2xl font-bold text-purple-600 mt-2">{{ $totalProducts }}</p>
        </div>
    </div>

    <!-- Sales Summary Table -->
    <div class="bg-white shadow p-4 rounded mb-10">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Sales Summary (Last 30 Days)</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Date</th>
                        <th class="border px-4 py-2 text-left">Total Orders</th>
                        <th class="border px-4 py-2 text-left">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesSummary as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($item->date)->format('M d, Y') }}</td>
                            <td class="border px-4 py-2">{{ $item->orders }}</td>
                            <td class="border px-4 py-2">₱{{ number_format($item->revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-4 py-2 text-center text-gray-500">No sales data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Completed Orders (Receipts) Table -->
    <div class="bg-white shadow p-4 rounded">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Receipts - Completed Orders</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2 text-left">Order ID</th>
                        <th class="border px-4 py-2 text-left">Customer</th>
                        <th class="border px-4 py-2 text-left">Date</th>
                        <th class="border px-4 py-2 text-left">Total Items</th>
                        <th class="border px-4 py-2 text-left">Total Price</th>
                        <th class="border px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($completedOrders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2 font-bold text-black">#{{ $order->id }}</td>
                            <td class="border px-4 py-2">{{ $order->customer_name }}</td>
                            <td class="border px-4 py-2 font-bold text-black">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                            <td class="border px-4 py-2">{{ $order->orderItems->sum('quantity') }}</td>
                            <td class="border px-4 py-2">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('orders.show', $order->id) }}"
                                   class="inline-block bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded hover:bg-blue-600">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="border px-4 py-2 text-center text-gray-500">No completed orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
