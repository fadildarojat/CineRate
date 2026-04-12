<?php

namespace Database\Seeders;

// ============================================
// SEEDER: DatabaseSeeder
// Mengisi database dengan data contoh awal
// Jalankan dengan: php artisan db:seed
// ============================================

use App\Models\Film;
use App\Models\Rating;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // DATA USER
        // ============================================

        // Akun admin (username: admin, password: admin123)
        $admin = User::create([
            'username' => 'admin',
            'password' => 'admin123',  // Otomatis di-hash oleh Laravel
            'role'     => 'admin',
        ]);

        // Akun user contoh (password: user123)
        $budi = User::create(['username' => 'budi', 'password' => 'user123', 'role' => 'user']);
        $siti = User::create(['username' => 'siti', 'password' => 'user123', 'role' => 'user']);
        $andi = User::create(['username' => 'andi', 'password' => 'user123', 'role' => 'user']);

        // ============================================
        // DATA FILM CONTOH
        // ============================================

        $film1 = Film::create([
            'judul'    => 'Laskar Pelangi',
            'tahun'    => 2008,
            'genre'    => 'Drama',
            'sinopsis' => 'Film ini bercerita tentang kehidupan 10 anak dari keluarga miskin yang bersekolah di Sekolah Muhammadiyah di Belitung. Mereka berjuang keras untuk mendapatkan pendidikan meskipun dengan segala keterbatasan. Dipimpin oleh dua guru inspiratif, Bu Muslimah dan Pak Harfan, mereka membuktikan bahwa semangat belajar tidak mengenal batas.',
        ]);

        $film2 = Film::create([
            'judul'    => 'Dilan 1990',
            'tahun'    => 2018,
            'genre'    => 'Romance',
            'sinopsis' => 'Milea adalah siswi pindahan dari Jakarta yang bersekolah di SMA di Bandung. Di sana ia bertemu dengan Dilan, seorang anggota geng motor yang unik dan romantis. Dilan berusaha mendekati Milea dengan cara-cara yang tidak biasa namun berkesan. Kisah cinta mereka yang penuh warna menjadi kenangan indah masa SMA.',
        ]);

        $film3 = Film::create([
            'judul'    => 'The Raid',
            'tahun'    => 2011,
            'genre'    => 'Action',
            'sinopsis' => 'Sebuah tim SWAT elit ditugaskan untuk menyerbu gedung apartemen 15 lantai yang dikuasai oleh bandar narkoba paling berbahaya. Ketika misi mereka terungkap, mereka harus bertarung dari lantai ke lantai melawan puluhan penjahat bersenjata. Film ini menampilkan adegan laga pencak silat yang spektakuler.',
        ]);

        $film4 = Film::create([
            'judul'    => 'Pengabdi Setan',
            'tahun'    => 2017,
            'genre'    => 'Horror',
            'sinopsis' => 'Setelah ibunya meninggal, Rini dan keluarganya mulai diganggu oleh kejadian-kejadian misterius di rumah mereka yang terpencil. Boneka-boneka bergerak sendiri, suara-suara aneh terdengar di malam hari, dan sosok menyeramkan mulai muncul. Mereka harus mengungkap rahasia gelap ibu mereka sebelum terlambat.',
        ]);

        $film5 = Film::create([
            'judul'    => '5 cm',
            'tahun'    => 2012,
            'genre'    => 'Adventure',
            'sinopsis' => 'Lima sahabat dekat - Arial, Riani, Zafran, Ian, dan Genta - memutuskan untuk tidak saling berkomunikasi selama 3 bulan. Setelah masa tersebut berakhir, mereka memutuskan untuk mendaki Gunung Semeru bersama sebagai simbol persahabatan. Perjalanan mendaki ini mengajarkan mereka tentang arti persahabatan sejati.',
        ]);

        $film6 = Film::create([
            'judul'    => 'Habibie & Ainun',
            'tahun'    => 2012,
            'genre'    => 'Biography',
            'sinopsis' => 'Film ini mengisahkan kisah cinta sejati antara B.J. Habibie dan Hasri Ainun Besari. Dimulai dari pertemuan mereka saat muda hingga perjuangan mereka bersama di Jerman. Habibie yang jenius dalam teknologi pesawat terbang dan Ainun yang setia mendampinginya, bersama membangun kehidupan yang penuh inspirasi.',
        ]);

        // ============================================
        // DATA RATING CONTOH
        // ============================================

        Rating::create(['user_id' => $budi->id, 'film_id' => $film1->id, 'rating' => 5]);
        Rating::create(['user_id' => $budi->id, 'film_id' => $film2->id, 'rating' => 4]);
        Rating::create(['user_id' => $budi->id, 'film_id' => $film3->id, 'rating' => 5]);
        Rating::create(['user_id' => $siti->id, 'film_id' => $film1->id, 'rating' => 4]);
        Rating::create(['user_id' => $siti->id, 'film_id' => $film2->id, 'rating' => 5]);
        Rating::create(['user_id' => $siti->id, 'film_id' => $film4->id, 'rating' => 4]);
        Rating::create(['user_id' => $andi->id, 'film_id' => $film3->id, 'rating' => 5]);
        Rating::create(['user_id' => $andi->id, 'film_id' => $film5->id, 'rating' => 4]);
        Rating::create(['user_id' => $andi->id, 'film_id' => $film6->id, 'rating' => 5]);

        // ============================================
        // DATA ULASAN CONTOH
        // ============================================

        Ulasan::create(['user_id' => $budi->id, 'film_id' => $film1->id, 'komentar' => 'Film yang sangat inspiratif! Ceritanya menyentuh hati dan mengingatkan kita tentang pentingnya pendidikan.']);
        Ulasan::create(['user_id' => $siti->id, 'film_id' => $film1->id, 'komentar' => 'Suka banget sama film ini. Aktingnya natural dan ceritanya bikin terharu.']);
        Ulasan::create(['user_id' => $budi->id, 'film_id' => $film2->id, 'komentar' => 'Dilan memang pandai merayu. Film romantis terbaik Indonesia!']);
        Ulasan::create(['user_id' => $siti->id, 'film_id' => $film2->id, 'komentar' => 'Suka sama chemistry antara Dilan dan Milea. Bikin baper!']);
        Ulasan::create(['user_id' => $budi->id, 'film_id' => $film3->id, 'komentar' => 'Action-nya gila! Koreografi pertarungannya kelas dunia.']);
        Ulasan::create(['user_id' => $andi->id, 'film_id' => $film3->id, 'komentar' => 'Film action Indonesia yang bikin bangga. Pencak silatnya luar biasa!']);
        Ulasan::create(['user_id' => $siti->id, 'film_id' => $film4->id, 'komentar' => 'Serem banget! Nonton ini bikin tidak bisa tidur semalam.']);
        Ulasan::create(['user_id' => $andi->id, 'film_id' => $film5->id, 'komentar' => 'Persahabatan yang indah. Jadi pengen mendaki Semeru juga!']);
        Ulasan::create(['user_id' => $andi->id, 'film_id' => $film6->id, 'komentar' => 'Kisah cinta yang mengharukan. B.J. Habibie adalah sosok yang luar biasa.']);
    }
}
