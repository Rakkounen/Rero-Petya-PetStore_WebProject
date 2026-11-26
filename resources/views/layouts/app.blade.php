<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-icon.svg') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <meta property="og:image" content="{{ asset('images/og-image.png') }}">
    <title>@yield('title', 'Rero Petya')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="{{ route('services.index') }}" aria-label="Rero Petya - Beranda">
                <img
                    src="{{ asset('images/logo-horizontal-kecil.png') }}"
                    srcset="{{ asset('images/logo-horizontal.png') }} 2x"
                    alt="Rero Petya"
                    width="192"
                    height="48"
                >
            </a>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('services.index') }}">Layanan</a>
                <a href="{{ route('services.index', ['type' => 'penitipan']) }}">Penitipan</a>
                <a href="{{ route('products.index') }}">Produk</a>
                <a href="{{ route('about') }}">Tentang Kami</a>
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="button-secondary" type="submit">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Daftar</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="page-shell">
        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t border-emerald-100 bg-white">
        <div class="mx-auto max-w-7xl px-5 py-6 text-sm text-muted">
            Rero Petya · Sahabat terbaik untuk hewan kesayangan Anda.
        </div>
    </footer>
</body>
</html>
