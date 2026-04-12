# Deploy CineRate ke Render

Panduan ini bikin project Laravel CineRate langsung jalan di Render menggunakan:
- 1 Web Service (PHP)
- 1 PostgreSQL database bawaan Render

## 1) Prasyarat

- Repo project sudah ada di GitHub.
- Akun Render sudah aktif.
- Punya TMDB API Key aktif.

## 2) File yang Sudah Disiapkan di Repo

Project ini sudah punya file `render.yaml`.

Artinya kamu bisa deploy pakai Blueprint (otomatis buat web service + database).

## 3) Deploy via Blueprint (Recommended)

1. Push semua perubahan ke branch utama di GitHub.
2. Login ke Render.
3. Klik **New +** -> **Blueprint**.
4. Pilih repo ini.
5. Render akan baca `render.yaml` dan membuat:
   - Service: `cinerate-web`
   - Database: `cinerate-db`
6. Saat diminta isi environment variable yang `sync: false`:
   - `APP_KEY`
   - `APP_URL`
   - `TMDB_API_KEY`

## 4) Isi Environment Variable Penting

### APP_KEY

Generate lokal:

```bash
php artisan key:generate --show
```

Copy hasilnya (format `base64:...`) lalu paste ke `APP_KEY` di Render.

### APP_URL

Isi dengan URL aplikasi Render kamu, contoh:

```text
https://cinerate-web.onrender.com
```

### TMDB_API_KEY

Isi dengan API key dari akun TMDB kamu.

## 5) Proses Deploy yang Terjadi Otomatis

Dari `render.yaml`:
- Build command: install dependency Composer untuk production.
- Pre-deploy command: `php artisan migrate --force`.
- Start command: jalankan Laravel di host `0.0.0.0` dan port dari Render.

## 6) Verifikasi Setelah Live

Cek ini setelah deploy sukses:
- Halaman home terbuka normal.
- Endpoint browse/search/discover bisa diakses.
- Submit rating dan ulasan berhasil.
- Data masuk ke database (cek dashboard admin).

## 7) Troubleshooting Singkat

### Error 500 setelah deploy

- Pastikan `APP_KEY` sudah terisi valid.
- Pastikan `TMDB_API_KEY` benar.
- Cek log di Render dashboard.

### Tidak bisa konek database

- Pastikan deploy dilakukan dari `render.yaml` yang sama.
- Pastikan env `DB_*` otomatis terisi dari database service.

### Migrasi gagal

- Cek log pre-deploy command.
- Jalankan manual dari Shell Render:

```bash
php artisan migrate --force
```

## 8) Catatan Penting

- Render free plan bisa sleep saat idle (cold start saat dibuka lagi).
- Jangan commit file `.env` produksi ke repository.
- Kalau API key TMDB pernah terlanjur dipublish, segera rotate key di TMDB dashboard.
