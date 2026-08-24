<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Basq NBA')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        .navbar-brand img {
            transition: transform 0.3s ease;
        }
        .navbar-brand:hover img {
            transform: rotate(15deg) scale(1.1);
        }
        .bi-heart-fill {
            color: #dc3545;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .bi-heart-fill:hover {
            transform: scale(1.2);
        }
        .hero-section {
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <span class="fw-bold" style="font-size: 1.5rem;">🏀 Basq NBA</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('noticias.index') }}"><i class="bi bi-newspaper"></i> Notícias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('jogos.index') }}"><i class="bi bi-calendar3"></i> Jogos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estatisticas.index') }}"><i class="bi bi-bar-chart"></i> Estatísticas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('donations.index') }}"><span style="font-size:1.1em;">&#x1F3C0;</span> Apoiador</a>
                    </li>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i> Admin</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm mt-1">Sair</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Registrar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="bg-dark text-white text-center py-4">
        <p style="font-size: 1.5rem;">🏀</p>
        <p class="mb-0" style="font-size: 1rem;">© {{ date('Y') }} Basq NBA</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(id, btn) {
            var input = document.getElementById(id);
            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>