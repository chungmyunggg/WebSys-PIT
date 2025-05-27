@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Orders</h1>
        <a href="{{ route('orders.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 transition">
            + Create New Order
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Orders Table --}}
    <div class="bg-white shadow rounded-lg overflow-auto p-4 border border-gray-200">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border-b font-semibold">Order ID</th>
                    <th class="px-4 py-2 border-b font-semibold">Customer</th>
                    <th class="px-4 py-2 border-b font-semibold">Total Price</th>
                    <th class="px-4 py-2 border-b font-semibold">Created At</th>
                    <th class="px-4 py-2 border-b font-semibold">Status</th>
                    <th class="px-4 py-2 border-b font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-b">#{{ $order->id }}</td>
                        <td class="px-4 py-2 border-b">{{ $order->customer_name }}</td>
                        <td class="px-4 py-2 border-b">₱{{ number_format($order->total_price, 2) }}</td>
                        <td class="px-4 py-2 border-b">{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 border-b">
                            @php
                                $badgeClasses = match($order->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded {{ $badgeClasses }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 border-b text-center space-x-2">
                            <a href="{{ route('orders.edit', $order) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
