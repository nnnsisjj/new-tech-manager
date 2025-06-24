<nav class="navbar navbar-expand navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <button class="btn btn-primary" type="button" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        @auth
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
    <i class="fas fa-user-circle me-1"></i>
    {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
</a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-1"></i> Выйти
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
        @endauth
    </div>
</nav>