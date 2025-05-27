<nav class="bg-blue-600 text-white px-6 py-4 flex justify-between items-center">
    <div class="text-xl font-bold">
        <a href="{{ url('/') }}">MyApp</a>
    </div>

    <div class="space-x-4">
        <a href="{{ url('/') }}" class="hover:underline">Home</a>
        <a href="{{ route('products.index') }}" class="hover:underline">Products</a>
        <a href="{{ route('orders.index') }}" class="hover:underline">Orders</a>

        @guest
            <a href="{{ route('login') }}" class="hover:underline">Login</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="hover:underline">Register</a>
            @endif
        @else
            <span>Welcome, {{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="hover:underline">Logout</button>
            </form>
        @endguest
    </div>
</nav>
