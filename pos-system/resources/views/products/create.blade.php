@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-6">Add New Product</h1>

@if ($errors->any())
<div class="mb-4 text-red-600">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('products.store') }}" method="POST" class="max-w-md">
    @csrf
    <label class="block mb-2">Name</label>
    <input type="text" name="name" class="border rounded p-2 w-full mb-4" value="{{ old('name') }}" required>

    <label class="block mb-2">Quantity</label>
    <input type="number" name="quantity" class="border rounded p-2 w-full mb-4" min="0" value="{{ old('quantity') }}" required>

    <label class="block mb-2">Price</label>
    <input type="number" step="0.01" name="price" class="border rounded p-2 w-full mb-4" min="0" value="{{ old('price') }}" required>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
    <a href="{{ route('products.index') }}" class="ml-4 text-gray-600">Cancel</a>
</form>
@endsection
