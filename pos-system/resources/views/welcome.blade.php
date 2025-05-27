<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Laravel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-100 min-h-screen flex flex-col justify-center items-center">

    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-lg text-center">

        <h1 class="text-5xl font-bold mb-4 text-gray-900">Welcome to Laravel</h1>

        <p class="text-lg mb-6 text-gray-700">Your Laravel application is ready.</p>

        <div class="space-x-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-white bg-blue-600 px-5 py-3 rounded-md hover:bg-blue-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-white bg-blue-600 px-5 py-3 rounded-md hover:bg-blue-700 transition">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-blue-600 border border-blue-600 px-5 py-3 rounded-md hover:bg-blue-600 hover:text-white transition">Register</a>
                    @endif
                @endauth
            @endif
        </div>

    </div>

</body>
</html>
