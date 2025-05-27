@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Create New Order</h1>

@if ($errors->any())
<div class="mb-4 text-red-600">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('orders.store') }}" method="POST" class="max-w-lg">
    @csrf

    <label class="block mb-2">Customer Name</label>
    <input
        type="text"
        name="customer_name"
        class="border rounded p-2 w-full mb-4"
        value="{{ old('customer_name') }}"
        required
    >

    <label class="block mb-2">Order Status</label>
    <select name="status" class="border rounded p-2 w-full mb-4" required>
        @php
            $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        @endphp
        @foreach ($statuses as $status)
            <option value="{{ $status }}" {{ old('status', 'pending') === $status ? 'selected' : '' }}>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>

    <label class="block mb-2">Select Products</label>

    <div id="order-items-container" class="mb-4 space-y-4">
        @php
            $oldProducts = old('products', ['']);
            $oldQuantities = old('quantities', ['']);
        @endphp

        @foreach ($oldProducts as $index => $oldProduct)
        <div class="order-item flex space-x-2 items-center">
            <select name="products[]" class="border rounded p-2 flex-grow" required>
                <option value="">-- Select Product --</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ $product->id == $oldProduct ? 'selected' : '' }}>
                    {{ $product->name }} (Stock: {{ $product->quantity }}) - ${{ number_format($product->price, 2) }}
                </option>
                @endforeach
            </select>

            <input
                type="number"
                name="quantities[]"
                min="1"
                class="border rounded p-2 w-20"
                placeholder="Qty"
                value="{{ $oldQuantities[$index] ?? '' }}"
                required
            >

            <button type="button" onclick="removeOrderItem(this)" class="bg-red-500 text-white px-3 py-1 rounded">Remove</button>
        </div>
        @endforeach
    </div>

    <button type="button" onclick="addOrderItem()" class="bg-blue-600 text-white px-4 py-2 rounded mb-6">Add Another Product</button>

    <br>

    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Create Order</button>
    <a href="{{ route('orders.index') }}" class="ml-4 text-gray-600">Cancel</a>
</form>

<script>
function addOrderItem() {
    const container = document.getElementById('order-items-container');

    const newItem = document.createElement('div');
    newItem.classList.add('order-item', 'flex', 'space-x-2', 'items-center');

    newItem.innerHTML = `
        <select name="products[]" class="border rounded p-2 flex-grow" required>
            <option value="">-- Select Product --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->quantity }}) - ${{ number_format($product->price, 2) }}</option>
            @endforeach
        </select>

        <input type="number" name="quantities[]" min="1" class="border rounded p-2 w-20" placeholder="Qty" required>

        <button type="button" onclick="removeOrderItem(this)" class="bg-red-500 text-white px-3 py-1 rounded">Remove</button>
    `;

    container.appendChild(newItem);
}

function removeOrderItem(button) {
    button.closest('.order-item').remove();
}
</script>
@endsection
