<nav class="navbar navbar-expand-lg" style="background:#075E54;">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="/">RideGo</a>

        <div class="d-flex align-items-center gap-3 ms-auto">
            @auth
                @if(Auth::user()->role === 'customer')
                    <a class="nav-link text-white" href="{{ route('customer.dashboard') }}">Dashboard</a>
                    <a class="nav-link text-white" href="{{ route('orders.index') }}">Order Saya</a>
                    <a class="nav-link text-white" href="{{ route('chat.index') }}">Chat</a>
                @elseif(Auth::user()->role === 'driver')
                    <a class="nav-link text-white" href="{{ route('driver.dashboard') }}">Dashboard</a>
                    <a class="nav-link text-white" href="{{ route('driver.orders') }}">Order</a>
                    <a class="nav-link text-white" href="{{ route('chat.index') }}">Chat</a>
                @elseif(Auth::user()->role === 'admin')
                    <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a class="nav-link text-white" href="{{ route('drivers.index') }}">Drivers</a>
                    <a class="nav-link text-white" href="{{ route('vehicles.index') }}">Vehicles</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>