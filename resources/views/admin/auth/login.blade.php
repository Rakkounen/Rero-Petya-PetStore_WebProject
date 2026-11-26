@extends('layouts.app')

@section('title', 'Login - Rero Petya')

@section('content')
    <section class="form-card">
        <h1 class="page-title">Login</h1>
        <p class="page-copy mb-6">Masuk ke akun Rero Petya Anda.</p>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="grid gap-5" method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field">
                <label for="username">Username</label>
                <input id="username" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
            </div>
            <label class="flex items-center gap-2 text-sm text-muted">
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>
            <button class="button w-full sm:w-fit" type="submit">Masuk</button>
        </form>
        <p class="mt-5 text-sm text-muted">Belum punya akun? <a class="font-semibold text-emerald-700" href="{{ route('register') }}">Daftar</a></p>
    </section>
@endsection
