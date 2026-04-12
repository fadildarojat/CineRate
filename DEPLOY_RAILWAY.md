# Deploy CineRate ke Railway

Panduan ini bikin project Laravel CineRate langsung jalan di Railway dengan:
- 1 Web Service (Laravel)
- 1 MySQL database (Railway)

## 1) Prasyarat

- Repo project sudah ada di GitHub.
- Akun Railway aktif.
- Punya TMDB API Key aktif.

## 2) File yang Sudah Disiapkan di Repo

Project ini sudah punya file railway.json.
Template variable juga sudah ada di .env.railway.example.

Artinya Railway akan pakai Nixpacks dan start command otomatis dari file itu.

## 3) Deploy Project dari GitHub

1. Push semua perubahan ke branch utama.
2. Login ke Railway.
3. Klik New Project -> Deploy from GitHub Repo.
4. Pilih repository CineRate.
5. Setelah service web terbentuk, tambah 1 service database MySQL.

## 4) Set Environment Variable di Service Web

Isi variable berikut di tab Variables service web:

APP_NAME=CineRate
APP_ENV=production
APP_DEBUG=false
APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}
APP_KEY=(isi dari hasil generate key)

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync

TMDB_API_KEY=(isi API key TMDB kamu)
TMDB_BASE_URL=https://api.themoviedb.org/3

Catatan:
- Nama service database dianggap MySQL (default Railway).
- Kalau nama service DB kamu beda, sesuaikan prefix variablenya.

## 5) Generate APP_KEY

Generate dari lokal:

php artisan key:generate --show

Copy hasil format base64:... ke APP_KEY di Railway.

## 6) Migrasi Database (Wajib untuk fitur rating/ulasan/admin)

Setelah deploy pertama, jalankan di Railway service web:

php artisan migrate --force

Lalu opsional bersihkan cache konfigurasi:

php artisan optimize:clear

## 7) Verifikasi Setelah Live

Cek setelah deploy sukses:
- Halaman home kebuka normal.
- Browse/Search/Discover jalan.
- Submit rating dan ulasan berhasil.
- Data masuk ke database.

## 8) Troubleshooting Singkat

### Error 500

- Cek APP_KEY sudah valid.
- Cek TMDB_API_KEY benar.
- Cek log di Railway tab Deployments/Logs.

### Tidak konek database

- Pastikan DB_CONNECTION=mysql.
- Pastikan variable DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD sudah ke-link ke service MySQL yang benar.

### Session error table tidak ada

- Jika pakai SESSION_DRIVER=file, error ini tidak relevan.
- Kalau mau simpan session di database, set SESSION_DRIVER=database lalu jalankan migration.

### Aplikasi tidak bisa diakses publik

- Pastikan service expose HTTP dan pakai port dari environment PORT.
- Cek start command aktif: php artisan serve --host=0.0.0.0 --port=$PORT
