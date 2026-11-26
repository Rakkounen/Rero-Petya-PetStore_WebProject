@extends('layouts.app')

@section('title', 'Daftar - Rero Petya')

@section('content')
    <section class="form-card">
        <h1 class="page-title">Daftar akun</h1>
        <p class="page-copy mb-6">Buat akun untuk menjadi bagian dari Rero Petya.</p>

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form class="grid gap-5" method="POST" action="{{ route('register.store') }}">
            @csrf
            <div class="field">
                <label for="name">Nama lengkap</label>
                <input id="name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" required autofocus>
            </div>
            <div class="field">
                <label for="username">Username</label>
                <input id="username" name="username" value="{{ old('username') }}" autocomplete="username" minlength="3" maxlength="50" pattern="[a-z0-9_-]+" required>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required>
            </div>
            <div class="field">
                <label for="password">Kata sandi (minimal 8 karakter)</label>
                <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
            </div>
            <div class="field">
                <label for="password_confirmation">Ulangi kata sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
            </div>
            <button class="button w-full sm:w-fit" type="submit">Daftar</button>
        </form>
        <p class="mt-5 text-sm text-muted">Sudah punya akun? <a class="font-semibold text-emerald-700" href="{{ route('login') }}">Login</a></p>
    </section>
@endsection
