@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container my-5">
    <div class="card shadow rounded-4 overflow-hidden">
        {{-- Header --}}
        <div class="card-header bg-primary text-white py-4 px-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h2 class="fw-bold mb-2 mb-md-0">
                Order <span class="text-warning">#{{ $order->id }}</span>
            </h2>
            <small class="fst-italic opacity-75 mt-1 mt-md-0">Placed on {{ $order->created_at->format('F j, Y \a\t h:i A') }}</small>
        </div>

        {{-- Body --}}
        <div class="card-body px-4 py-5">
            <div class="row g-4 mb-5">
                {{-- Customer Details --}}
                <div class="col-md-6">
                    <div class="p-4 bg-light border-start border-4 border-primary rounded-3 h-100">
                        <h5 class="text-uppercase text-secondary fw-bold mb-3">
                            Customer Details
                        </h5>
                        <p class="mb-2">
                            <strong>Name:</strong> {{ $order->customer_name ?? 'N/A' }}
                        </p>
                        <p class="mb-0">
                            <strong>Status:</strong>
                            @php
                                $statusClasses = [
                                    'pending' => 'badge bg-warning text-dark',
                                    'completed' => 'badge bg-success',
                                    'cancelled' => 'badge bg-danger',
                                ];
                                $statusClass = $statusClasses[$order->status] ?? 'badge bg-secondary';
                            @endphp
                            <span class="{{ $statusClass }} fs-6 px-3 py-2 rounded-pill">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="col-md-6">
                    <div class="p-4 bg-light border-start border-4 border-success rounded-3 h-100">
                        <h5 class="text-uppercase text-secondary fw-bold mb-3">
                            Order Summary
                        </h5>
                        <p class="mb-2">
                            <strong>Total Items:</strong> {{ $order->orderItems->sum('quantity') }}
                        </p>
                        <p class="mb-0 fs-4 fw-bold text-success">
                            Total Price: ₱{{ number_format($order->total_price, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Product Details --}}
            <div class="mb-4">
                <h5 class="text-uppercase text-secondary fw-bold mb-3 border-start border-4 border-primary ps-3">
                    Product Details
                </h5>

                @if ($order->orderItems->count())
                    <div class="table-responsive shadow-sm rounded-3">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light text-muted text-uppercase small">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $orderItem)
                                    <tr>
                                        <td class="fw-bold text-black">{{ $orderItem->product->name ?? 'N/A' }}</td>
                                        <td class="text-center fw-bold text-black">{{ $orderItem->quantity }}</td>
                                        <td class="text-end fw-bold text-black">₱{{ number_format($orderItem->price, 2) }}</td>
                                        <td class="text-end fw-bold text-black">₱{{ number_format($orderItem->price * $orderItem->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted fst-italic">No products found in this order.</p>
                @endif
            </div>

            {{-- Total and Back Button --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-5 pt-4 border-top">
                <h3 class="fw-bold text-success mb-3 mb-md-0">
                    Grand Total: ₱{{ number_format($order->total_price, 2) }}
                </h3>

                <a href="{{ route('orders.index') }}" 
                   class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold shadow-sm">
                    &laquo; Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
