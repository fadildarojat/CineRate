{{-- ============================================
    LAYOUT UTAMA (app.blade.php)
    Template dasar yang digunakan oleh semua halaman.
    Berisi: head, navbar, konten, dan footer.
    Tema: IMDb Dark Style
    ============================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CineRate - Rating, Review & Streaming Film')</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    {{-- Scoped Responsive Helpers --}}
    <link href="{{ asset('css/responsive.css') }}?v={{ @filemtime(public_path('css/responsive.css')) }}" rel="stylesheet">

    {{-- CSS Custom - IMDb Style (Fully Responsive) --}}
    <style>
        :root {
            --imdb-yellow: #f5c518;
            --imdb-dark: #121212;
            --imdb-dark-2: #1a1a1a;
            --imdb-dark-3: #1f1f1f;
            --imdb-dark-4: #2a2a2a;
            --imdb-dark-5: #333333;
            --imdb-text: #e0e0e0;
            --imdb-text-muted: #999999;
            --imdb-link: #5799ef;
            --transition-speed: 0.3s;
        }

        /* ---- UMUM ---- */
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Roboto', 'Segoe UI', sans-serif;
            background-color: var(--imdb-dark);
            color: var(--imdb-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        main {
            flex: 1;
            padding: 1rem;
        }

        a {
            color: var(--imdb-link);
            text-decoration: none;
            transition: color var(--transition-speed) ease;
        }

        a:hover {
            color: #79b8f3;
        }

        /* ---- NAVBAR IMDb - Responsive ---- */
        .navbar-imdb {
            background-color: var(--imdb-dark-3) !important;
            border-bottom: 1px solid var(--imdb-dark-4);
            padding: 0.75rem 0;
        }

        .navbar-imdb .navbar-brand {
            background-color: var(--imdb-yellow);
            color: #000 !important;
            font-weight: 700;
            padding: 0.35rem 0.75rem;
            border-radius: 4px;
            font-size: clamp(0.9rem, 2vw, 1.2rem);
            letter-spacing: 1px;
            transition: background-color var(--transition-speed) ease;
            min-width: 80px;
            text-align: center;
        }

        .navbar-imdb .navbar-brand:hover {
            background-color: #e0b416;
        }

        .navbar-imdb .navbar-toggler {
            padding: 0.25rem 0.5rem;
            border-color: var(--imdb-dark-4);
        }

        .navbar-imdb .navbar-toggler:focus {
            box-shadow: 0 0 0 0.25rem rgba(245, 197, 24, 0.25);
        }

        .navbar-imdb .nav-link {
            color: #fff !important;
            font-weight: 500;
            font-size: clamp(0.85rem, 1.5vw, 0.95rem);
            padding: 0.5rem 0.75rem !important;
            border-radius: 4px;
            transition: background-color var(--transition-speed) ease;
            position: relative;
        }

        .navbar-imdb .nav-link:hover {
            background-color: var(--imdb-dark-4);
        }

        .navbar-imdb .nav-link i {
            margin-right: 0.4rem;
        }

        .navbar-imdb .btn-login {
            background-color: var(--imdb-yellow);
            color: #000;
            font-weight: 600;
            border: none;
            padding: 0.4rem 1rem;
            border-radius: 4px;
            font-size: clamp(0.8rem, 1.5vw, 0.95rem);
            transition: background-color var(--transition-speed) ease;
        }

        .navbar-imdb .btn-login:hover {
            background-color: #e0b416;
            color: #000;
        }

        .navbar-imdb .input-group {
            width: 100%;
            max-width: 300px;
        }

        .navbar-imdb .form-control {
            font-size: 0.9rem;
            padding: 0.4rem 0.75rem;
        }

        /* ---- HERO SECTION - Responsive ---- */
        .hero-section {
            background: linear-gradient(135deg, var(--imdb-dark-2) 0%, var(--imdb-dark-3) 100%);
            color: white;
            padding: clamp(2rem, 8vw, 4rem) 1rem;
            text-align: center;
            margin-bottom: clamp(1.5rem, 5vw, 2rem);
            border-bottom: 3px solid var(--imdb-yellow);
            border-radius: 8px 8px 0 0;
        }

        .hero-section h1 {
            font-size: clamp(1.5rem, 6vw, 2.8rem);
            font-weight: 700;
            color: var(--imdb-yellow);
            margin-bottom: 0.5rem;
        }

        .hero-section p {
            font-size: clamp(0.9rem, 2.5vw, 1.2rem);
            color: var(--imdb-text-muted);
            margin-bottom: 0;
        }

        /* ---- CARD FILM - Responsive ---- */
        .card-film {
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
            border: none;
            border-radius: 8px;
            overflow: hidden;
            background-color: var(--imdb-dark-3);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-film:hover,
        .card-film:active {
            transform: translateY(-8px);
            box-shadow: 0 8px 30px rgba(245, 197, 24, 0.3);
        }

        .card-film:focus-within {
            outline: 2px solid var(--imdb-yellow);
            outline-offset: 2px;
        }

        .card-film .card-img-top {
            height: clamp(200px, 50vw, 350px);
            object-fit: cover;
            display: block;
        }

        .card-film .card-body {
            padding: clamp(1rem, 3vw, 1.25rem);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .card-film .card-title {
            color: #fff;
            font-size: clamp(0.9rem, 2vw, 1.05rem);
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .card-film .text-muted {
            color: var(--imdb-text-muted) !important;
            font-size: clamp(0.75rem, 1.5vw, 0.85rem);
        }

        .badge-genre {
            background-color: var(--imdb-dark-5);
            color: var(--imdb-text);
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            border: 1px solid var(--imdb-dark-4);
            display: inline-block;
            margin-top: 0.5rem;
            margin-right: 0.3rem;
        }

        /* ---- RATING BINTANG ---- */
        .star-rating {
            color: var(--imdb-yellow);
            font-size: clamp(0.9rem, 2vw, 1.2rem);
        }

        .star-rating-large {
            color: var(--imdb-yellow);
            font-size: clamp(1.4rem, 4vw, 2rem);
        }

        .star-interactive {
            cursor: pointer;
            color: #555;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            transition: color var(--transition-speed) ease, transform var(--transition-speed) ease;
            display: inline-block;
            padding: 0.25rem;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }

        .star-interactive:hover,
        .star-interactive:active,
        .star-interactive.active {
            color: var(--imdb-yellow);
            transform: scale(1.15);
        }

        .imdb-rating-badge {
            background-color: var(--imdb-yellow);
            color: #000;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 4px;
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* ---- DETAIL FILM - Responsive ---- */
        .detail-poster {
            max-height: clamp(350px, 60vw, 550px);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            object-fit: cover;
            width: 100%;
            margin-bottom: clamp(1rem, 3vw, 1.5rem);
        }

        .detail-info h1 {
            font-size: clamp(1.5rem, 5vw, 2.2rem);
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
        }

        .rating-box {
            background: var(--imdb-dark-3);
            border: 1px solid var(--imdb-dark-4);
            color: white;
            padding: clamp(1rem, 3vw, 1.5rem);
            border-radius: 8px;
            display: inline-block;
            text-align: center;
            margin-bottom: 1rem;
        }

        .rating-box .rating-number {
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 700;
            line-height: 1;
            color: var(--imdb-yellow);
        }

        .rating-box .rating-text {
            font-size: 0.85rem;
            color: var(--imdb-text-muted);
            margin-top: 0.5rem;
        }

        /* ---- ULASAN ---- */
        .ulasan-item {
            background: var(--imdb-dark-3);
            border-radius: 8px;
            padding: clamp(1rem, 3vw, 1.5rem);
            margin-bottom: 1rem;
            border: 1px solid var(--imdb-dark-4);
            border-left: 3px solid var(--imdb-yellow);
        }

        .ulasan-item .username {
            font-weight: 600;
            color: var(--imdb-link);
            font-size: clamp(0.9rem, 2vw, 1rem);
        }

        .ulasan-item .tanggal {
            font-size: 0.75rem;
            color: var(--imdb-text-muted);
        }

        .form-rating {
            background: var(--imdb-dark-3);
            border-radius: 8px;
            padding: clamp(1.5rem, 4vw, 2rem);
            border: 1px solid var(--imdb-dark-4);
            margin-bottom: 1.5rem;
        }

        .form-rating h4 {
            font-size: clamp(1.1rem, 2.5vw, 1.3rem);
        }

        /* ---- LOGIN BOX ---- */
        .login-box {
            width: 100%;
            max-width: 450px;
            margin: clamp(2rem, 10vw, 4rem) auto;
            background: var(--imdb-dark-3);
            border-radius: 8px;
            padding: clamp(1.5rem, 4vw, 2.5rem);
            border: 1px solid var(--imdb-dark-4);
        }

        .login-box h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 2rem;
            font-size: clamp(1.3rem, 3vw, 1.8rem);
        }

        /* ---- FORM STYLING - Touch-Friendly ---- */
        .form-control,
        .form-select {
            background-color: var(--imdb-dark-4);
            border: 1px solid var(--imdb-dark-5);
            color: var(--imdb-text);
            font-size: 1rem;
            padding: 0.6rem 0.8rem;
            border-radius: 4px;
            transition: border-color var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
            min-height: 44px;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--imdb-dark-4);
            border-color: var(--imdb-yellow);
            color: var(--imdb-text);
            box-shadow: 0 0 0 0.2rem rgba(245, 197, 24, 0.25);
            outline: none;
        }

        .form-control::placeholder {
            color: var(--imdb-text-muted);
        }

        .form-label {
            color: var(--imdb-text);
            font-weight: 500;
            font-size: clamp(0.85rem, 1.5vw, 1rem);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control:disabled {
            background-color: var(--imdb-dark-5);
            color: var(--imdb-text-muted);
        }

        /* ---- BUTTONS - Touch-Friendly ---- */
        .btn {
            min-height: 44px;
            padding: 0.6rem 1rem;
            font-size: clamp(0.85rem, 1.5vw, 1rem);
            border-radius: 4px;
            transition: all var(--transition-speed) ease;
            font-weight: 600;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-imdb {
            background-color: var(--imdb-yellow);
            color: #000;
            border: none;
        }

        .btn-imdb:hover,
        .btn-imdb:active {
            background-color: #e0b416;
            color: #000;
        }

        .btn-outline-imdb {
            border: 1px solid var(--imdb-dark-5);
            color: var(--imdb-text);
            background: transparent;
        }

        .btn-outline-imdb:hover,
        .btn-outline-imdb:active {
            background-color: var(--imdb-dark-4);
            color: #fff;
            border-color: var(--imdb-text);
        }

        /* ---- DASHBOARD ---- */
        .stat-card {
            border-radius: 8px;
            padding: clamp(1.5rem, 3vw, 2rem);
            text-align: center;
            background: var(--imdb-dark-3);
            border: 1px solid var(--imdb-dark-4);
            transition: transform var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
        }

        .stat-card .stat-number {
            font-size: clamp(1.8rem, 5vw, 2.8rem);
            font-weight: 700;
            color: var(--imdb-yellow);
        }

        .stat-card .stat-label {
            font-size: clamp(0.8rem, 1.5vw, 0.95rem);
            color: var(--imdb-text-muted);
            margin-top: 0.5rem;
        }

        .stat-card.bg-film {
            border-left: 4px solid var(--imdb-yellow);
        }

        .stat-card.bg-user {
            border-left: 4px solid #5799ef;
        }

        .stat-card.bg-rating {
            border-left: 4px solid var(--imdb-yellow);
        }

        .stat-card.bg-ulasan {
            border-left: 4px solid #20c997;
        }

        /* ---- TABEL ADMIN - Responsive ---- */
        .table-admin {
            border-radius: 8px;
            overflow: auto;
            border: 1px solid var(--imdb-dark-4);
        }

        .table-admin .card-header {
            background-color: var(--imdb-dark-3) !important;
            border-bottom: 1px solid var(--imdb-dark-4);
        }

        .table {
            color: var(--imdb-text);
            font-size: clamp(0.8rem, 1.5vw, 1rem);
        }

        .table thead {
            background-color: var(--imdb-dark-3);
        }

        .table thead th {
            color: var(--imdb-yellow);
            border-bottom: 2px solid var(--imdb-dark-4);
            padding: clamp(0.5rem, 1.5vw, 1rem);
        }

        .table td {
            border-color: var(--imdb-dark-4);
            vertical-align: middle;
            padding: clamp(0.5rem, 1.5vw, 0.75rem);
        }

        .table-hover tbody tr:hover {
            background-color: var(--imdb-dark-4);
            color: var(--imdb-text);
        }

        .poster-thumbnail {
            width: 50px;
            height: 70px;
            object-fit: cover;
            border-radius: 4px;
        }

        /* ---- CARD ---- */
        .card {
            background-color: var(--imdb-dark-3);
            border: 1px solid var(--imdb-dark-4);
            border-radius: 8px;
        }

        .card-header {
            background-color: var(--imdb-dark-3);
            border-bottom: 1px solid var(--imdb-dark-4);
        }

        /* ---- ALERT ---- */
        .alert {
            padding: clamp(0.75rem, 2vw, 1rem);
            border-radius: 8px;
            font-size: clamp(0.85rem, 1.5vw, 1rem);
        }

        .alert-success {
            background-color: #1a3a2a;
            color: #6fdc8c;
            border-color: #2d5a3d;
        }

        .alert-danger {
            background-color: #3a1a1a;
            color: #ff6b6b;
            border-color: #5a2d2d;
        }

        .alert-info {
            background-color: #1a2a3a;
            color: #6baed6;
            border-color: #2d3d5a;
        }

        /* ---- POSTER DEFAULT ---- */
        .no-poster {
            background: linear-gradient(135deg, var(--imdb-dark-4), var(--imdb-dark-5));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--imdb-text-muted);
            font-size: clamp(2rem, 6vw, 5rem);
            min-height: 300px;
        }

        /* ---- SECTION HEADING ---- */
        .section-heading {
            color: var(--imdb-yellow);
            font-weight: 700;
            font-size: clamp(1.2rem, 3vw, 1.5rem);
            border-left: 4px solid var(--imdb-yellow);
            padding-left: 12px;
            margin-bottom: 1.5rem;
            margin-top: 1rem;
        }

        /* ---- FOOTER ---- */
        .footer-imdb {
            background-color: var(--imdb-dark-2);
            border-top: 1px solid var(--imdb-dark-4);
            padding: clamp(1.5rem, 4vw, 2rem) 1rem;
            font-size: clamp(0.8rem, 1.5vw, 1rem);
        }

        .footer-imdb p {
            margin-bottom: 0.5rem;
        }

        /* ---- TMDB BACKDROP ---- */
        .tmdb-backdrop {
            height: clamp(200px, 50vw, 350px);
            background-size: cover;
            background-position: center top;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
        }

        .tmdb-backdrop-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(18, 18, 18, 0.3), rgba(18, 18, 18, 0.95));
        }

        /* ---- PAGINATION DARK ---- */
        .pagination {
            flex-wrap: wrap;
        }

        .page-link {
            background-color: var(--imdb-dark-3);
            border-color: var(--imdb-dark-4);
            color: var(--imdb-text);
            min-height: 44px;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: all var(--transition-speed) ease;
        }

        .page-link:hover {
            background-color: var(--imdb-dark-4);
            border-color: var(--imdb-dark-5);
            color: var(--imdb-yellow);
        }

        .page-item.active .page-link {
            background-color: var(--imdb-yellow);
            border-color: var(--imdb-yellow);
            color: #000;
            font-weight: 700;
        }

        .page-item.disabled .page-link {
            background-color: var(--imdb-dark-2);
            border-color: var(--imdb-dark-4);
            color: var(--imdb-text-muted);
        }

        /* Tablet (768px ke atas) */
        @media (min-width: 768px) {
            main {
                padding: 2rem 1rem;
            }

            .navbar-imdb .input-group {
                max-width: 400px;
            }

            .detail-info h1 {
                font-size: clamp(1.8rem, 4vw, 2.5rem);
            }
        }

        /* Desktop (992px ke atas) */
        @media (min-width: 992px) {
            main {
                padding: 2rem;
            }

            .card-film .card-img-top {
                height: 350px;
            }

            .hero-section {
                border-radius: 8px;
            }
        }

        /* ---- ORIENTASI LANDSCAPE ---- */
        @media (max-height: 600px) and (orientation: landscape) {
            .hero-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .hero-section h1 {
                font-size: 1.4rem;
            }

            .hero-section p {
                font-size: 0.8rem;
            }
        }

        /* ---- TOUCH DEVICE SPECIFIC ---- */
        @media (hover: none) and (pointer: coarse) {
            .card-film:hover {
                transform: none;
            }

            .card-film:active {
                transform: scale(0.98);
                box-shadow: 0 4px 15px rgba(245, 197, 24, 0.2);
            }

            a:active {
                opacity: 0.7;
            }

            button:active {
                transform: scale(0.97);
            }
        }

        /* ---- HIGH DPI / RETINA ---- */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            body {
                -webkit-font-smoothing: subpixel-antialiased;
            }
        }

        /* ---- DARK MODE SUPPORT ---- */
        @media (prefers-color-scheme: dark) {
            :root {
                color-scheme: dark;
            }
        }

        /* ---- REDUCED MOTION ---- */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            html {
                scroll-behavior: auto;
            }
        }

        /* ---- TOMBOL STREAMING ---- */
        .btn-streaming {
            background: linear-gradient(135deg, #e50914, #b20710);
            color: #fff;
            border: none;
            font-weight: 700;
            font-size: clamp(0.95rem, 2vw, 1.1rem);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(229, 9, 20, 0.4);
        }

        .btn-streaming:hover {
            background: linear-gradient(135deg, #ff1a25, #e50914);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(229, 9, 20, 0.5);
        }

        .btn-streaming:active {
            transform: translateY(0);
        }

        .btn-streaming i {
            font-size: 1.2em;
        }

        /* ---- CARD WATCH OVERLAY ---- */
        .card-watch-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: clamp(200px, 50vw, 350px);
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 2;
            text-decoration: none;
        }

        .card-film:hover .card-watch-overlay {
            opacity: 1;
        }

        .card-watch-overlay i {
            font-size: 3.5rem;
            color: #fff;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .card-watch-overlay:hover i {
            transform: scale(1.15);
            color: var(--imdb-yellow);
        }

        /* ---- SMALL WATCH BUTTON ---- */
        .btn-watch-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e50914, #b20710);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .btn-watch-small:hover {
            background: linear-gradient(135deg, #ff1a25, #e50914);
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 2px 10px rgba(229, 9, 20, 0.4);
        }

        /* ---- NAVBAR USER DROPDOWN ---- */
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-user .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--imdb-yellow);
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .navbar-user .user-name {
            color: #fff;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--imdb-dark-5);
            color: var(--imdb-text-muted);
            padding: 0.3rem 0.75rem;
            font-size: 0.8rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(255, 59, 48, 0.15);
            border-color: #ff3b30;
            color: #ff3b30;
        }
    </style>
</head>
<body>

{{-- ============================================ --}}
{{-- NAVBAR - IMDb Style                          --}}
{{-- ============================================ --}}
<nav class="navbar navbar-expand-lg navbar-dark navbar-imdb sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">CineRate</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="bi bi-house"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tmdb.popular') }}">
                        <i class="bi bi-fire"></i> Popular
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tmdb.top-rated') }}">
                        <i class="bi bi-trophy"></i> Top Rated
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tmdb.now-playing') }}">
                        <i class="bi bi-play-circle"></i> Now Playing
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tmdb.discover') }}">
                        <i class="bi bi-compass"></i> Discover
                    </a>
                </li>
            </ul>

            {{-- Search Bar --}}
            <form class="d-flex me-3" action="{{ route('tmdb.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control form-control-sm"
                           placeholder="Cari film..." value="{{ request('q') }}"
                           style="min-width: 200px;">
                    <button class="btn btn-imdb btn-sm" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- Auth Buttons --}}
            <div class="d-flex align-items-center gap-2 ms-2">
                @auth
                    <div class="navbar-user">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </div>
                        <span class="user-name d-none d-lg-inline">{{ Auth::user()->username }}</span>
                    </div>
                    <form method="POST" action="{{ Auth::user()->isAdmin() ? route('admin.logout') : route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-imdb btn-sm" style="font-size: 0.85rem;">
                        <i class="bi bi-person-plus"></i> Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Container utama untuk konten --}}
<main class="container my-4">
    {{-- Pesan sukses global --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-auto-hide">
            <i class="bi bi-check-circle"></i> {{ session('sukses') }}
        </div>
    @endif

    @yield('content')
</main>

{{-- ============================================ --}}
{{-- FOOTER - IMDb Style                          --}}
{{-- ============================================ --}}
<footer class="footer-imdb text-center py-4 mt-5">
    <div class="container">
        <p class="mb-1" style="color: var(--imdb-yellow); font-weight: 700;">
            CineRate
        </p>
        <p class="mb-0" style="color: var(--imdb-text-muted); font-size: 0.85rem;">
            &copy; {{ date('Y') }} | CineRate - Rating, Review & Streaming Film
        </p>
    </div>
</footer>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- JavaScript Custom - Enhanced for All Devices --}}
<script>
    // ============================================
    // RATING BINTANG INTERAKTIF
    // Support untuk click dan touch
    // ============================================
    function pilihRating(nilai) {
        document.getElementById('input-rating').value = nilai;
        const bintang = document.querySelectorAll('.star-interactive');
        bintang.forEach(function (star, index) {
            if (index < nilai) {
                star.classList.add('active');
                star.innerHTML = '<i class="bi bi-star-fill"></i>';
            } else {
                star.classList.remove('active');
                star.innerHTML = '<i class="bi bi-star"></i>';
            }
        });
    }

    // Support touch untuk rating bintang
    document.addEventListener('DOMContentLoaded', function () {
        const stars = document.querySelectorAll('.star-interactive');
        stars.forEach(function (star, index) {
            // Mouse events
            star.addEventListener('click', function () {
                pilihRating(index + 1);
            });

            // Touch events
            star.addEventListener('touchstart', function (e) {
                e.preventDefault();
                pilihRating(index + 1);
            });

            // Hover pada desktop
            star.addEventListener('mouseenter', function () {
                stars.forEach(function (s, i) {
                    s.style.opacity = i <= index ? '1' : '0.5';
                });
            });
        });

        // Reset hover
        const ratingContainer = document.querySelector('.star-interactive')
            ?.parentElement;
        if (ratingContainer) {
            ratingContainer.addEventListener('mouseleave', function () {
                stars.forEach(function (s) {
                    s.style.opacity = '1';
                });
            });
        }
    });

    // ============================================
    // PREVIEW POSTER SEBELUM UPLOAD
    // ============================================
    function previewPoster(event) {
        var file = event.target.files[0];
        if (file) {
            // Validasi ukuran file (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar (max 5MB)');
                event.target.value = '';
                return;
            }

            var reader = new FileReader();
            reader.onload = function (e) {
                var preview = document.getElementById('preview-poster');
                if (preview) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // ============================================
    // AUTO-HIDE ALERT SETELAH 5 DETIK
    // ============================================
    function initAutoHideAlert() {
        document.querySelectorAll('.alert-auto-hide').forEach(function (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.remove();
                }, 500);
            }, 5000);
        });
    }

    document.addEventListener('DOMContentLoaded', initAutoHideAlert);

    // ============================================
    // LAZY LOADING UNTUK GAMBAR
    // ============================================
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src || img.src;
                            img.classList.add('loaded');
                            observer.unobserve(img);
                        }
                    });
                },
                {
                    rootMargin: '50px',
                }
            );

            document
                .querySelectorAll('img[data-src]')
                .forEach((img) => imageObserver.observe(img));
        }
    }

    document.addEventListener('DOMContentLoaded', initLazyLoading);

    // ============================================
    // SMOOTH SCROLL UNTUK ANCHOR
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth',
                });
            }
        });
    });

    // ============================================
    // NAVBAR MOBILE MENU AUTO-CLOSE
    // ============================================
    const navbarCollapse = document.querySelector('.navbar-collapse');
    if (navbarCollapse) {
        document.querySelectorAll('.nav-link').forEach((link) => {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                        toggle: false,
                    });
                    bsCollapse.hide();
                }
            });
        });
    }

    // ============================================
    // FORM VALIDATION DENGAN FEEDBACK VISUAL
    // ============================================
    function initFormValidation() {
        const forms = document.querySelectorAll('form');
        forms.forEach((form) => {
            form.addEventListener('submit', function (e) {
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                form.classList.add('was-validated');
            });

            // Real-time validation
            const inputs = form.querySelectorAll(
                'input, textarea, select'
            );
            inputs.forEach((input) => {
                input.addEventListener('change', function () {
                    if (input.checkValidity()) {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    } else {
                        input.classList.remove('is-valid');
                        input.classList.add('is-invalid');
                    }
                });

                input.addEventListener('blur', function () {
                    if (input.value && !input.checkValidity()) {
                        input.classList.add('is-invalid');
                    }
                });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initFormValidation);

    // ============================================
    // RESPONSIVE TABLE UNTUK MOBILE
    // ============================================
    function initResponsiveTable() {
        const tables = document.querySelectorAll('table');
        tables.forEach((table) => {
            if (!table.classList.contains('table-responsive-custom')) {
                table.classList.add('table-responsive-custom');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initResponsiveTable);

    // ============================================
    // HANDLE VIEWPORT CHANGES
    // ============================================
    function handleViewportChange() {
        const width = window.innerWidth;
        const height = window.innerHeight;

        // Update CSS variables jika diperlukan
        document.documentElement.style.setProperty(
            '--viewport-width',
            width + 'px'
        );
        document.documentElement.style.setProperty(
            '--viewport-height',
            height + 'px'
        );
    }

    window.addEventListener('resize', handleViewportChange);
    document.addEventListener('DOMContentLoaded', handleViewportChange);
    window.addEventListener('orientationchange', handleViewportChange);

    // ============================================
    // KEYBOARD NAVIGATION
    // ============================================
    document.addEventListener('keydown', function (e) {
        // ESC untuk menutup dropdown
        if (e.key === 'Escape') {
            const dropdowns = document.querySelectorAll(
                '.dropdown-menu.show'
            );
            dropdowns.forEach((dd) => {
                const toggleButton = dd.previousElementSibling;
                if (toggleButton) {
                    toggleButton.click();
                }
            });
        }

        // Tab untuk navigasi
        if (e.key === 'Tab') {
            document.body.classList.add('using-keyboard');
        }
    });

    // Remove keyboard class saat mouse digunakan
    document.addEventListener('mousedown', function () {
        document.body.classList.remove('using-keyboard');
    });

    // ============================================
    // LOAD INDICATOR UNTUK FORM SUBMISSION
    // ============================================
    document.addEventListener('submit', function (e) {
        if (e.target.tagName === 'FORM') {
            const submitBtn = e.target.querySelector(
                'button[type="submit"]'
            );
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<i class="bi bi-hourglass-split"></i> Loading...';
            }
        }
    });

    // ============================================
    // ACCESSIBILITY ENHANCEMENTS
    // ============================================
    document.addEventListener('DOMContentLoaded', function () {
        // Focus visible untuk keyboard navigation
        const style = document.createElement('style');
        style.textContent = `
            .btn:focus-visible,
            a:focus-visible,
            input:focus-visible,
            select:focus-visible,
            textarea:focus-visible {
                outline: 2px solid var(--imdb-yellow);
                outline-offset: 2px;
            }
        `;
        document.head.appendChild(style);

        // Add ARIA labels untuk icon buttons
        document.querySelectorAll('button i').forEach((icon) => {
            const button = icon.parentElement;
            if (!button.getAttribute('aria-label')) {
                const text = button.innerText
                    .replace(icon.outerHTML, '')
                    .trim();
                if (text) {
                    button.setAttribute('aria-label', text);
                }
            }
        });
    });

    // ============================================
    // PERFORMANCE OPTIMIZATION
    // ============================================
    // Debounce function untuk scroll/resize events
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Optimize scroll performance
    let lastScrollTop = 0;
    const navbar = document.querySelector('.navbar-imdb');
    if (navbar) {
        window.addEventListener(
            'scroll',
            debounce(function () {
                let scrolling = window.scrollY;
                // Add shadow to navbar saat scroll
                if (scrolling > 10) {
                    navbar.style.boxShadow =
                        '0 2px 10px rgba(0,0,0,0.3)';
                } else {
                    navbar.style.boxShadow = 'none';
                }
            }, 50)
        );
    }

    // ============================================
    // SERVICE WORKER UNTUK OFFLINE SUPPORT (Optional)
    // ============================================
    if ('serviceWorker' in navigator) {
        // Uncomment untuk enable service worker
        // navigator.serviceWorker.register('/sw.js').catch(err => console.log('SW registration failed'));
    }
</script>

@yield('scripts')
</body>
</html>
