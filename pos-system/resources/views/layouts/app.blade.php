<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen font-sans antialiased">

    {{-- Header with single nav panel --}}
    <header class="bg-white shadow">
        <nav class="container mx-auto flex justify-between items-center py-4 px-4">
            <div class="flex space-x-6">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium">Dashboard</a>
                <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">Orders</a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">Products</a>
                <!-- Inventory link removed -->
                <a href="{{ route('reports.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">Reports</a>
            </div>

            <div>
                @guest
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium mr-4">Login</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600 font-medium">Register</a>
                    @endif
                @else
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600 font-medium">
                            Logout ({{ Auth::user()->name }})
                        </button>
                    </form>
                @endguest
            </div>
        </nav>
    </header>

    {{-- Main content --}}
    <main class="container mx-auto mt-6 px-4">
        @yield('content')
    </main>

</body>
</html>
