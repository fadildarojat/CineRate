{{-- ============================================
    HALAMAN REGISTER USER
    Form pendaftaran untuk pengguna baru
    Design: IMDb Dark Style
    ============================================ --}}

@extends('layouts.app')

@section('title', 'Register - CineRate')

@section('content')

<div class="login-box">
    <div class="text-center mb-4">
        <i class="bi bi-person-plus-fill" style="font-size: 3rem; color: var(--imdb-yellow);"></i>
        <h2 class="mt-2">Daftar Akun</h2>
        <p style="color: var(--imdb-text-muted); font-size: 0.9rem;">
            Buat akun untuk menikmati streaming, rating & review film
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

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">
                <i class="bi bi-person"></i> Username
            </label>
            <input type="text" class="form-control @error('username') is-invalid @enderror"
                   name="username" id="username"
                   placeholder="Pilih username unik"
                   value="{{ old('username') }}" required autofocus>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="bi bi-lock"></i> Password
            </label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" id="password"
                   placeholder="Minimal 6 karakter" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label">
                <i class="bi bi-lock-fill"></i> Konfirmasi Password
            </label>
            <input type="password" class="form-control"
                   name="password_confirmation" id="password_confirmation"
                   placeholder="Ulangi password" required>
        </div>

        <button type="submit" class="btn btn-imdb w-100 mb-3">
            <i class="bi bi-person-plus"></i> Daftar
        </button>
    </form>

    <div class="text-center mt-3">
        <p style="color: var(--imdb-text-muted); font-size: 0.9rem;">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color: var(--imdb-yellow); font-weight: 600;">
                Masuk di sini
            </a>
        </p>
    </div>
</div>

@endsection
