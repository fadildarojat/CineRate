{{-- ============================================
    HALAMAN LOGIN ADMIN (IMDb Style)
    Admin harus login untuk mengakses dashboard
    ============================================ --}}

@extends('layouts.app')

@section('title', 'Login Admin - CineRate')

@section('content')

<div class="login-box">
    <div class="text-center mb-4">
        <i class="bi bi-shield-lock" style="font-size: 3rem; color: var(--imdb-yellow);"></i>
    </div>
    <h2>Admin Login</h2>

    {{-- Pesan Error --}}
    @if($errors->any())
        <div class="alert alert-danger alert-auto-hide">
            <i class="bi bi-exclamation-triangle"></i>
            {{ $errors->first() }}
        </div>
    @endif

    {{-- Form Login --}}
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="mb-3">
            <label for="username" class="form-label">
                <i class="bi bi-person"></i> Username
            </label>
            <input type="text" class="form-control" name="username" id="username"
                   placeholder="Masukkan username" required
                   value="{{ old('username') }}">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="bi bi-key"></i> Password
            </label>
            <input type="password" class="form-control" name="password" id="password"
                   placeholder="Masukkan password" required>
        </div>

        <button type="submit" class="btn btn-imdb w-100 py-2">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </button>
    </form>

    <div class="mt-4 text-center">
        <small style="color: var(--imdb-text-muted);">
            <i class="bi bi-info-circle"></i> Default: admin / admin123
        </small>
    </div>
</div>

@endsection
