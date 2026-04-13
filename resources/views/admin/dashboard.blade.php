{{-- ============================================
    DASHBOARD ADMIN
    Menampilkan statistik dan daftar rating & ulasan
    dari film TMDB
    ============================================ --}}

@extends('layouts.app')

@section('title', 'Dashboard Admin - CineRate')

@section('content')

{{-- Judul --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="section-heading">
        <i class="bi bi-speedometer2"></i> Dashboard Admin
    </h2>
</div>

{{-- Pesan Selamat Datang --}}
<p class="mb-4" style="color: var(--imdb-text-muted);">
    Selamat datang, <strong style="color: var(--imdb-yellow);">{{ Auth::user()->username }}</strong>!
    Lihat data rating & ulasan pengguna dari halaman ini.
</p>

{{-- Pesan Sukses --}}
@if(session('sukses'))
    <div class="alert alert-success alert-auto-hide">
        <i class="bi bi-check-circle"></i> {{ session('sukses') }}
    </div>
@endif

{{-- ============================================ --}}
{{-- KARTU STATISTIK                              --}}
{{-- ============================================ --}}
<div class="row mb-4">
    <div class="col-md-6 col-6 mb-3">
        <div class="stat-card bg-rating">
            <div class="stat-number">{{ $totalRating }}</div>
            <div class="stat-label"><i class="bi bi-star"></i> Total Rating</div>
        </div>
    </div>
    <div class="col-md-6 col-6 mb-3">
        <div class="stat-card bg-ulasan">
            <div class="stat-number">{{ $totalUlasan }}</div>
            <div class="stat-label"><i class="bi bi-chat-dots"></i> Total Ulasan</div>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- TABEL DAFTAR RATING                          --}}
{{-- ============================================ --}}
<div class="card table-admin mb-4">
    <div class="card-header">
        <h5 class="mb-0" style="color: var(--imdb-yellow);"><i class="bi bi-star-fill"></i> Daftar Rating</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Film</th>
                        <th>Rating</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ratings as $index => $rating)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $rating->nama ?? 'Anonim' }}</td>
                        <td>
                            <a href="{{ route('tmdb.detail', $rating->tmdb_id) }}" class="text-decoration-none">
                                {{ $filmNames[$rating->tmdb_id] ?? 'Unknown' }}
                            </a>
                        </td>
                        <td>
                            <span class="star-rating"><i class="bi bi-star-fill"></i></span>
                            {{ $rating->rating }}/10
                        </td>
                        <td style="white-space: nowrap;">{{ $rating->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.rating.delete', $rating->id) }}" method="POST" onsubmit="return confirm('Hapus rating ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: var(--imdb-text-muted);">
                            <i class="bi bi-info-circle"></i> Belum ada rating.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ============================================ --}}
{{-- TABEL DAFTAR ULASAN                          --}}
{{-- ============================================ --}}
<div class="card table-admin">
    <div class="card-header">
        <h5 class="mb-0" style="color: var(--imdb-yellow);"><i class="bi bi-chat-square-text"></i> Daftar Ulasan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Film</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ulasans as $index => $ulasan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $ulasan->nama ?? 'Anonim' }}</td>
                        <td>
                            <a href="{{ route('tmdb.detail', $ulasan->tmdb_id) }}" class="text-decoration-none">
                                {{ $filmNames[$ulasan->tmdb_id] ?? 'Unknown' }}
                            </a>
                        </td>
                        <td>{{ Str::limit($ulasan->komentar, 80) }}</td>
                        <td style="white-space: nowrap;">{{ $ulasan->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            <form action="{{ route('admin.ulasan.delete', $ulasan->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4" style="color: var(--imdb-text-muted);">
                            <i class="bi bi-info-circle"></i> Belum ada ulasan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
