@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="p-4 bg-blue-100 rounded shadow">
        <h2 class="font-semibold text-lg text-blue-800">Total Products</h2>
        <p class="text-2xl text-blue-900">{{ $totalProducts ?? 0 }}</p>
    </div>
    <div class="p-4 bg-green-100 rounded shadow">
        <h2 class="font-semibold text-lg text-green-800">Total Orders</h2>
        <p class="text-2xl text-green-900">{{ $totalOrders ?? 0 }}</p>
    </div>
    <div class="p-4 bg-blue-100 rounded shadow">
        <h2 class="font-semibold text-lg text-blue-800">Revenue</h2>
        <p class="text-2xl text-blue-900">${{ number_format($revenue ?? 0, 2) }}</p>
    </div>
    <div class="p-4 bg-green-100 rounded shadow">
        <h2 class="font-semibold text-lg text-green-800">Low Stock</h2>
        <ul class="list-disc list-inside text-green-900">
            @if(!empty($lowStockProducts) && $lowStockProducts->count())
                @foreach ($lowStockProducts as $product)
                    <li>{{ $product->name }} ({{ $product->quantity }})</li>
                @endforeach
            @else
                <li>No low stock products.</li>
            @endif
        </ul>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="p-4 bg-white border rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
        <table class="w-full border border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-2 py-1">Customer</th>
                    <th class="border px-2 py-1">Total</th>
                    <th class="border px-2 py-1">Date</th>
                    <th class="border px-2 py-1">Items</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($recentOrders) && $recentOrders->count())
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td class="border px-2 py-1">{{ $order->customer_name }}</td>
                            <td class="border px-2 py-1">${{ number_format($order->total_price, 2) }}</td>
                            <td class="border px-2 py-1">{{ $order->created_at->format('Y-m-d') }}</td>
                            <td class="border px-2 py-1">
                                <ul class="list-disc list-inside">
                                    @foreach ($order->orderItems as $item)
                                        <li>{{ $item->product->name ?? 'Unknown Product' }} x {{ $item->quantity }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="4" class="text-center p-3">No recent orders.</td></tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="p-4 bg-white border rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Available Products (Quick Order)</h2>
        <ul>
            @if (!empty($availableProducts) && $availableProducts->count())
                @foreach ($availableProducts as $product)
                    <li class="mb-2 flex justify-between items-center border-b pb-1">
                        <span>{{ $product->name }} ({{ $product->quantity }} pcs - ${{ number_format($product->price, 2) }})</span>
                        <a href="{{ route('orders.create') }}" 
                           class="text-sm bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                            Order Now
                        </a>
                    </li>
                @endforeach
            @else
                <li>No available products.</li>
            @endif
        </ul>
    </div>
</div>
@endsection
