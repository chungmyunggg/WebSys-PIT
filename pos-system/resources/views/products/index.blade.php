@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- Page Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Products</h1>
        <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-md shadow hover:bg-blue-700 transition">
            + Add New Product
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Grid Layout --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        {{-- Sidebar --}}
        <aside class="md:col-span-1 space-y-6">

            {{-- Low Stock --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm">
                <h2 class="font-semibold text-yellow-800 mb-2">Low Stock (≤ 5)</h2>
                @forelse ($lowStockProducts as $product)
                    <p class="text-sm text-yellow-900">• {{ $product->name }} ({{ $product->quantity }})</p>
                @empty
                    <p class="text-sm text-yellow-900">No low stock items.</p>
                @endforelse
            </div>

            {{-- Top Selling --}}
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded shadow-sm">
                <h2 class="font-semibold text-green-800 mb-2">Top Selling</h2>
                @forelse ($topSellingProducts as $product)
                    <p class="text-sm text-green-900">• {{ $product->name }} (Sold: {{ $product->total_sold }})</p>
                @empty
                    <p class="text-sm text-green-900">No top sellers yet.</p>
                @endforelse
            </div>

        </aside>

        {{-- Main Content --}}
        <main class="md:col-span-3">

            {{-- Total Count --}}
            <h2 class="text-xl font-semibold text-gray-700 mb-3">Total Products: {{ $totalProducts }}</h2>

            {{-- Product Table --}}
            <div class="overflow-auto bg-white rounded shadow p-4 border border-gray-200">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border-b font-semibold">
                                <a href="{{ route('products.index', ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                    Name
                                </a>
                            </th>
                            <th class="px-4 py-2 border-b font-semibold">
                                <a href="{{ route('products.index', ['sort' => 'quantity', 'direction' => request('sort') === 'quantity' && request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                    Quantity
                                </a>
                            </th>
                            <th class="px-4 py-2 border-b font-semibold">
                                <a href="{{ route('products.index', ['sort' => 'price', 'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                    Price
                                </a>
                            </th>
                            <th class="px-4 py-2 border-b font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border-b">{{ $product->name }}</td>
                                <td class="px-4 py-2 border-b">{{ $product->quantity }}</td>
                                <td class="px-4 py-2 border-b">₱{{ number_format($product->price, 2) }}</td>
                                <td class="px-4 py-2 border-b text-center">
                                    <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:underline mr-2">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-center text-gray-500">No products available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
