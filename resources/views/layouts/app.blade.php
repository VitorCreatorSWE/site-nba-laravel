<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="{{ session('theme', auth()->user()?->theme_preference ?? 'light') }}">
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
        body {
            transition: background-color 0.2s ease, color 0.2s ease;
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
                        <a class="nav-link" href="{{ route('home') }}"><i class="bi bi-house"></i> {{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('noticias.index') }}"><i class="bi bi-newspaper"></i> {{ __('messages.news') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('jogos.index') }}"><i class="bi bi-calendar3"></i> {{ __('messages.games') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('estatisticas.index') }}"><i class="bi bi-bar-chart"></i> {{ __('messages.stats') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('donations.index') }}"><span style="font-size:1.1em;">&#x1F3C0;</span> {{ __('messages.donations') }}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-translate"></i> {{ app()->getLocale() === 'en' ? 'Language' : 'Idioma' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('locale') }}">
                                    @csrf
                                    <input type="hidden" name="locale" value="pt">
                                    <button type="submit" class="dropdown-item">🇧🇷 Português</button>
                                </form>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('locale') }}">
                                    @csrf
                                    <input type="hidden" name="locale" value="en">
                                    <button type="submit" class="dropdown-item">🇺🇸 English</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i> {{ __('messages.admin') }}</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm mt-1">{{ __('messages.logout') }}</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> {{ __('messages.login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> {{ __('messages.register') }}</a>
                        </li>
                    @endauth
                    <li class="nav-item ms-lg-4">
                        <button type="button" id="themeToggle" class="btn btn-link nav-link px-2" aria-label="Alternar modo escuro" title="Alternar modo escuro">
                            <i class="bi bi-moon-stars-fill"></i>
                        </button>
                    </li>
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
        const themeToggle = document.getElementById('themeToggle');
        const themeLabel = document.getElementById('themeLabel');
        const savedTheme = localStorage.getItem('theme') || document.documentElement.dataset.bsTheme || 'light';

        function applyTheme(theme) {
            document.documentElement.dataset.bsTheme = theme;
            localStorage.setItem('theme', theme);
            if (themeLabel) themeLabel.textContent = theme === 'dark' ? 'Modo claro' : 'Modo escuro';
            const icon = themeToggle?.querySelector('i');
            if (icon) icon.className = theme === 'dark' ? 'bi bi-sun-fill' : 'bi bi-moon-stars-fill';
        }

        applyTheme(savedTheme);

        themeToggle?.addEventListener('click', () => {
            const theme = document.documentElement.dataset.bsTheme === 'dark' ? 'light' : 'dark';
            applyTheme(theme);
            fetch('{{ route('theme') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ theme })
            });
        });

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
