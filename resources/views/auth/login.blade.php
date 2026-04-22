{{-- ============================================
    HALAMAN LOGIN USER
    Form login untuk pengguna biasa
    Design: IMDb Dark Style
    ============================================ --}}

@extends('layouts.app')

@section('title', 'Login - CineRate')

@section('content')

<div class="login-box">
    <div class="text-center mb-4">
        <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--imdb-yellow);"></i>
        <h2 class="mt-2">Login</h2>
        <p style="color: var(--imdb-text-muted); font-size: 0.9rem;">
            Masuk ke akun CineRate kamu
        </p>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
        <div class="alert alert-danger alert-auto-hide">
            <i class="bi bi-exclamation-triangle"></i>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    {{-- Success Message --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-auto-hide">
            <i class="bi bi-check-circle"></i> {{ session('sukses') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">
                <i class="bi bi-person"></i> Username
            </label>
            <input type="text" class="form-control @error('username') is-invalid @enderror"
                   name="username" id="username"
                   placeholder="Masukkan username"
                   value="{{ old('username') }}" required autofocus>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">
                <i class="bi bi-lock"></i> Password
            </label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" id="password"
                   placeholder="Masukkan password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-imdb w-100 mb-3">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>
    </form>

    <div class="text-center mt-3">
        <p style="color: var(--imdb-text-muted); font-size: 0.9rem;">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color: var(--imdb-yellow); font-weight: 600;">
                Daftar Sekarang
            </a>
        </p>
    </div>
</div>

@endsection
