<?php

namespace Database\Seeders;

// ============================================
// SEEDER: FilmSeeder
// Mengisi database dengan 50 film populer lengkap
// dengan poster, rating, dan ulasan
// Jalankan: php artisan db:seed --class=FilmSeeder
// ============================================

use App\Models\Film;
use App\Models\Rating;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class FilmSeeder extends Seeder
{
    /**
     * Daftar nama untuk rating & ulasan guest
     */
    private array $namaGuest = [
        'Budi Santoso', 'Siti Rahayu', 'Andi Pratama', 'Dewi Lestari',
        'Rizky Firmansyah', 'Putri Wulandari', 'Agus Setiawan', 'Rina Marlina',
        'Dimas Prasetyo', 'Ayu Ningrum', 'Fajar Hidayat', 'Nisa Amelia',
        'Yoga Saputra', 'Intan Permata', 'Bayu Nugroho', 'Citra Dewi',
        'Hendra Wijaya', 'Lina Sari', 'Raka Aditya', 'Maya Anggraini',
        'Taufik Rahman', 'Winda Kusuma', 'Arif Budiman', 'Dina Fitriani',
        'Gilang Mahardika', 'Sari Mulyani', 'Eko Purnomo', 'Ratna Sari',
        'Irfan Hakim', 'Nurul Hidayah',
    ];

    /**
     * Template ulasan positif
     */
    private array $ulasanPositif = [
        'Film yang luar biasa! Ceritanya sangat menarik dan bikin penasaran dari awal sampai akhir.',
        'Salah satu film terbaik yang pernah saya tonton. Aktingnya sangat memukau!',
        'Wajib nonton! Alur ceritanya sangat bagus dan tidak membosankan.',
        'Film ini benar-benar menghibur. Saya menontonnya sampai dua kali!',
        'Sinematografinya keren banget. Setiap adegannya indah dan penuh makna.',
        'Ceritanya dalam dan bermakna. Film yang bikin mikir setelah menontonnya.',
        'Suka banget sama film ini! Pemerannya sangat cocok dengan karakternya.',
        'Film yang sangat mengharukan. Beberapa kali hampir menangis saat menontonnya.',
        'Rekomendasi banget buat yang suka genre ini. Tidak akan kecewa!',
        'Filmnya epic! Efek visualnya keren dan ceritanya bikin ketagihan.',
        'Masterpiece! Setiap detail filmnya sangat diperhatikan oleh sutradaranya.',
        'Film yang cocok ditonton bareng keluarga. Pesannya sangat positif.',
        'Soundtrack-nya juga bagus banget, cocok sama suasana filmnya.',
        'Akting pemain utamanya luar biasa. Pantas dapat penghargaan!',
        'Alur ceritanya tidak bisa ditebak. Twist di akhir bikin kaget!',
        'Saya sudah nonton ini 3 kali dan masih suka. Film legendaris!',
        'Kualitas produksinya sangat baik. Terlihat profesional dari setiap sudut.',
        'Film ini berhasil membuat saya tertawa sekaligus terharu. Luar biasa!',
        'Durasi filmnya pas, tidak terlalu panjang dan tidak terlalu pendek.',
        'Cerita yang sangat relate dengan kehidupan sehari-hari. Bikin refleksi diri.',
    ];

    /**
     * Template ulasan netral
     */
    private array $ulasanNetral = [
        'Filmnya lumayan bagus, tapi ada beberapa bagian yang bisa lebih baik.',
        'Ceritanya oke, cuma agak lambat di bagian tengah. Overall masih bagus.',
        'Aktingnya bagus tapi alur ceritanya agak mudah ditebak.',
        'Film yang cukup menghibur untuk ditonton di akhir pekan.',
        'Ada kelebihan dan kekurangannya. Tapi secara keseluruhan worth it ditonton.',
        'Visualnya bagus banget, sayangnya ceritanya kurang mendalam.',
        'Film yang oke untuk sekali tonton. Tidak terlalu memorable tapi tidak buruk juga.',
        'Premisnya menarik tapi eksekusinya bisa lebih baik lagi.',
    ];

    public function run(): void
    {
        $this->command->info('Memulai import film...');

        // Pastikan folder poster ada
        Storage::disk('public')->makeDirectory('posters');

        $films = $this->getFilmData();

        foreach ($films as $index => $filmData) {
            // Cek apakah film sudah ada
            if (Film::where('judul', $filmData['judul'])->exists()) {
                $this->command->comment("  Skip: {$filmData['judul']} (sudah ada)");
                continue;
            }

            // Download poster
            $posterPath = null;
            if (!empty($filmData['poster_url'])) {
                $posterPath = $this->downloadPoster($filmData['poster_url'], $filmData['judul']);
            }

            // Simpan film
            $film = Film::create([
                'judul'    => $filmData['judul'],
                'tahun'    => $filmData['tahun'],
                'genre'    => $filmData['genre'],
                'sinopsis' => $filmData['sinopsis'],
                'poster'   => $posterPath,
            ]);

            // Generate rating (5-15 rating per film)
            $jumlahRating = rand(5, 15);
            $namaUsed = [];
            for ($i = 0; $i < $jumlahRating; $i++) {
                $nama = $this->namaGuest[array_rand($this->namaGuest)];
                // Hindari nama duplikat per film
                while (in_array($nama, $namaUsed) && count($namaUsed) < count($this->namaGuest)) {
                    $nama = $this->namaGuest[array_rand($this->namaGuest)];
                }
                $namaUsed[] = $nama;

                // Rating cenderung tinggi (3-5) untuk film populer
                $rating = $this->generateRating();

                Rating::create([
                    'film_id' => $film->id,
                    'nama'    => $nama,
                    'rating'  => $rating,
                ]);
            }

            // Generate ulasan (2-6 ulasan per film)
            $jumlahUlasan = rand(2, 6);
            $namaUlasanUsed = [];
            for ($i = 0; $i < $jumlahUlasan; $i++) {
                $nama = $this->namaGuest[array_rand($this->namaGuest)];
                while (in_array($nama, $namaUlasanUsed) && count($namaUlasanUsed) < count($this->namaGuest)) {
                    $nama = $this->namaGuest[array_rand($this->namaGuest)];
                }
                $namaUlasanUsed[] = $nama;

                // Pilih ulasan positif atau netral secara random
                if (rand(1, 10) <= 7) {
                    $komentar = $this->ulasanPositif[array_rand($this->ulasanPositif)];
                } else {
                    $komentar = $this->ulasanNetral[array_rand($this->ulasanNetral)];
                }

                Ulasan::create([
                    'film_id'  => $film->id,
                    'nama'     => $nama,
                    'komentar' => $komentar,
                ]);
            }

            $num = $index + 1;
            $this->command->info("  [{$num}/" . count($films) . "] {$filmData['judul']} ✓");
        }

        $this->command->info('');
        $this->command->info('Selesai! ' . count($films) . ' film berhasil diimport.');
    }

    /**
     * Generate rating yang cenderung tinggi untuk film populer
     */
    private function generateRating(): int
    {
        $rand = rand(1, 100);
        if ($rand <= 5) return 1;       // 5% rating 1
        if ($rand <= 10) return 2;      // 5% rating 2
        if ($rand <= 25) return 3;      // 15% rating 3
        if ($rand <= 55) return 4;      // 30% rating 4
        return 5;                        // 45% rating 5
    }

    /**
     * Download poster dari URL dan simpan ke storage
     */
    private function downloadPoster(string $url, string $judul): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);

            if ($response->successful()) {
                $filename = 'posters/' . \Illuminate\Support\Str::slug($judul) . '.jpg';
                Storage::disk('public')->put($filename, $response->body());
                return $filename;
            }
        } catch (\Exception $e) {
            $this->command->warn("  Gagal download poster: {$judul}");
        }

        return null;
    }

    /**
     * Data 50 film populer
     * Poster dari TMDB (The Movie Database) - gratis untuk penggunaan edukasi
     */
    private function getFilmData(): array
    {
        $tmdb = 'https://image.tmdb.org/t/p/w500';

        return [
            // ---- FILM INDONESIA ----
            [
                'judul'      => 'Laskar Pelangi',
                'tahun'      => 2008,
                'genre'      => 'Drama',
                'sinopsis'   => 'Film ini bercerita tentang kehidupan 10 anak dari keluarga miskin yang bersekolah di Sekolah Muhammadiyah di Belitung. Mereka berjuang keras untuk mendapatkan pendidikan meskipun dengan segala keterbatasan. Dipimpin oleh dua guru inspiratif, Bu Muslimah dan Pak Harfan, mereka membuktikan bahwa semangat belajar tidak mengenal batas.',
                'poster_url' => $tmdb . '/mIBhS0aGKVAPkDFhMFiLnNx6iqo.jpg',
            ],
            [
                'judul'      => 'The Raid: Redemption',
                'tahun'      => 2011,
                'genre'      => 'Action',
                'sinopsis'   => 'Sebuah tim SWAT elit ditugaskan untuk menyerbu gedung apartemen 15 lantai yang dikuasai oleh bandar narkoba paling berbahaya. Ketika misi mereka terungkap, mereka harus bertarung dari lantai ke lantai melawan puluhan penjahat bersenjata. Film ini menampilkan adegan laga pencak silat yang spektakuler dan memukau penonton dunia.',
                'poster_url' => $tmdb . '/7seCpuGB3bBjF0aYQat1McK4lxl.jpg',
            ],
            [
                'judul'      => 'Pengabdi Setan',
                'tahun'      => 2017,
                'genre'      => 'Horror',
                'sinopsis'   => 'Setelah ibunya meninggal, Rini dan keluarganya mulai diganggu oleh kejadian-kejadian misterius di rumah mereka yang terpencil. Boneka-boneka bergerak sendiri, suara-suara aneh terdengar di malam hari, dan sosok menyeramkan mulai muncul. Mereka harus mengungkap rahasia gelap ibu mereka sebelum terlambat.',
                'poster_url' => $tmdb . '/rOusBRVBFh0klFtKxDiTkP0Z8nu.jpg',
            ],
            [
                'judul'      => 'Dilan 1990',
                'tahun'      => 2018,
                'genre'      => 'Romance',
                'sinopsis'   => 'Milea adalah siswi pindahan dari Jakarta yang bersekolah di SMA di Bandung. Di sana ia bertemu dengan Dilan, seorang anggota geng motor yang unik dan romantis. Dilan berusaha mendekati Milea dengan cara-cara yang tidak biasa namun berkesan. Kisah cinta mereka yang penuh warna menjadi kenangan indah masa SMA.',
                'poster_url' => $tmdb . '/dOBHoMNyrMBoeXdqGlwBmA0lYN9.jpg',
            ],
            [
                'judul'      => 'Habibie & Ainun',
                'tahun'      => 2012,
                'genre'      => 'Biography',
                'sinopsis'   => 'Film ini mengisahkan kisah cinta sejati antara B.J. Habibie dan Hasri Ainun Besari. Dimulai dari pertemuan mereka saat muda hingga perjuangan mereka bersama di Jerman. Habibie yang jenius dalam teknologi pesawat terbang dan Ainun yang setia mendampinginya, bersama membangun kehidupan yang penuh inspirasi.',
                'poster_url' => $tmdb . '/mxqLcPE2RKMBN2ufNLIdFasNqfr.jpg',
            ],
            [
                'judul'      => 'KKN di Desa Penari',
                'tahun'      => 2022,
                'genre'      => 'Horror',
                'sinopsis'   => 'Enam mahasiswa menjalani program KKN di sebuah desa terpencil yang menyimpan misteri kelam. Mereka dilarang memasuki hutan terlarang, namun salah satu dari mereka melanggar aturan tersebut. Teror pun dimulai ketika sosok penari misterius mulai menghantui mereka satu per satu.',
                'poster_url' => $tmdb . '/4SKMzLTmefmjsM7RHlgZgGvcf8.jpg',
            ],
            [
                'judul'      => 'Filosofi Kopi',
                'tahun'      => 2015,
                'genre'      => 'Drama',
                'sinopsis'   => 'Ben dan Jody adalah dua sahabat yang memiliki kedai kopi bernama Filosofi Kopi. Ben adalah seorang barista perfeksionis yang terobsesi untuk membuat kopi terbaik di dunia, sementara Jody mengelola bisnis mereka. Ketika kedai mereka terancam bangkrut, mereka harus mencari biji kopi legendaris untuk menyelamatkannya.',
                'poster_url' => $tmdb . '/2F2bYvxPtVl20OiJJFIetXrjNJt.jpg',
            ],
            [
                'judul'      => 'Ayat-Ayat Cinta',
                'tahun'      => 2008,
                'genre'      => 'Romance',
                'sinopsis'   => 'Fahri adalah mahasiswa Indonesia yang sedang menempuh pendidikan di Universitas Al-Azhar, Kairo. Kehidupannya berubah ketika ia terlibat dalam situasi rumit dengan beberapa wanita yang memiliki perasaan terhadapnya. Film ini mengisahkan tentang cinta, pengorbanan, dan keimanan dalam menghadapi ujian kehidupan.',
                'poster_url' => $tmdb . '/opPiwLEnQTn0aEjIrcSJANpUiPv.jpg',
            ],
            [
                'judul'      => 'Perahu Kertas',
                'tahun'      => 2012,
                'genre'      => 'Romance',
                'sinopsis'   => 'Kugy adalah gadis yang bermimpi menjadi penulis dongeng, sementara Keenan memiliki bakat melukis yang luar biasa. Keduanya bertemu di Bandung dan menjalin persahabatan yang perlahan berubah menjadi cinta. Namun takdir seperti menguji mereka dengan berbagai rintangan yang memisahkan impian dan perasaan.',
                'poster_url' => $tmdb . '/tREbMqTSWr16X5bDNjmqkNnnMB7.jpg',
            ],
            [
                'judul'      => 'Warkop DKI Reborn',
                'tahun'      => 2016,
                'genre'      => 'Comedy',
                'sinopsis'   => 'Dono, Kasino, dan Indro kembali dalam versi modern! Tiga sahabat ini harus menghadapi masalah ketika mereka terlibat dalam konspirasi besar yang melibatkan penjahat internasional. Dengan kelucuan khas Warkop DKI, mereka berusaha menyelamatkan diri sambil membuat penonton tertawa terbahak-bahak.',
                'poster_url' => $tmdb . '/m1hYt8tKSWSKvakueJZRnuM1bDK.jpg',
            ],

            // ---- FILM HOLLYWOOD POPULER ----
            [
                'judul'      => 'The Shawshank Redemption',
                'tahun'      => 1994,
                'genre'      => 'Drama',
                'sinopsis'   => 'Andy Dufresne, seorang bankir yang dihukum seumur hidup atas pembunuhan istrinya yang sebenarnya tidak ia lakukan. Di penjara Shawshank, ia menjalin persahabatan dengan Red dan perlahan merencanakan pelarian yang jenius. Film ini adalah cerita tentang harapan, persahabatan, dan kebebasan yang tidak pernah padam.',
                'poster_url' => $tmdb . '/9cjIGRQL1PiNYgzWUe8jfalBMDr.jpg',
            ],
            [
                'judul'      => 'The Dark Knight',
                'tahun'      => 2008,
                'genre'      => 'Action',
                'sinopsis'   => 'Batman menghadapi musuh terberatnya: Joker, seorang penjahat anarkis yang ingin menghancurkan Gotham City dari dalam. Dengan bantuan Harvey Dent, Batman berusaha memberantas kejahatan terorganisir. Namun Joker selalu selangkah lebih maju, memaksa Batman membuat pilihan-pilihan mustahil yang akan mengubah semuanya.',
                'poster_url' => $tmdb . '/qJ2tW6WMUDux911BytUk2QX6Zfj.jpg',
            ],
            [
                'judul'      => 'Inception',
                'tahun'      => 2010,
                'genre'      => 'Sci-Fi',
                'sinopsis'   => 'Dom Cobb adalah pencuri paling berbakat di dunia, yang mengkhususkan diri dalam ekstraksi rahasia dari alam bawah sadar manusia melalui mimpi. Ia ditawari kesempatan untuk menghapus catatan kriminalnya jika berhasil melakukan inception: menanamkan ide ke dalam pikiran seseorang. Misi ini membawanya ke dunia mimpi berlapis-lapis yang penuh bahaya.',
                'poster_url' => $tmdb . '/ljsZTbVsrQSqZgWeep2B1QiDKuh.jpg',
            ],
            [
                'judul'      => 'Interstellar',
                'tahun'      => 2014,
                'genre'      => 'Sci-Fi',
                'sinopsis'   => 'Di masa depan, Bumi sedang sekarat karena bencana alam yang menghancurkan pertanian. Cooper, mantan pilot NASA, harus meninggalkan keluarganya untuk melakukan perjalanan antariksa melalui lubang cacing demi mencari planet baru yang layak huni. Film ini menggabungkan sains, cinta, dan pengorbanan seorang ayah.',
                'poster_url' => $tmdb . '/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
            ],
            [
                'judul'      => 'The Avengers',
                'tahun'      => 2012,
                'genre'      => 'Action',
                'sinopsis'   => 'Ketika Loki mengancam Bumi dengan pasukan alien, Nick Fury dari SHIELD mengumpulkan tim superhero paling kuat: Iron Man, Captain America, Thor, Hulk, Black Widow, dan Hawkeye. Mereka harus belajar bekerja sama dan mengesampingkan ego masing-masing untuk menyelamatkan dunia dari kehancuran total.',
                'poster_url' => $tmdb . '/RYMX2wcKCBAr24UyPD7xwmjaTn.jpg',
            ],
            [
                'judul'      => 'Avengers: Endgame',
                'tahun'      => 2019,
                'genre'      => 'Action',
                'sinopsis'   => 'Setelah Thanos berhasil memusnahkan separuh kehidupan di alam semesta, para Avengers yang tersisa harus menemukan cara untuk membalikkan keadaan. Dengan bantuan perjalanan waktu, mereka melancarkan serangan terakhir yang paling berani dan emosional dalam sejarah MCU. Pengorbanan besar pun tak terhindarkan.',
                'poster_url' => $tmdb . '/or06FN3Dber5SO8ZsnFuFXmnaEq.jpg',
            ],
            [
                'judul'      => 'Spider-Man: No Way Home',
                'tahun'      => 2021,
                'genre'      => 'Action',
                'sinopsis'   => 'Identitas Spider-Man terungkap ke publik, membuat kehidupan Peter Parker berantakan. Dia meminta bantuan Doctor Strange untuk membuat semua orang melupakan rahasianya, namun mantra tersebut salah dan membuka multiverse. Musuh-musuh Spider-Man dari dimensi lain pun bermunculan, memaksa Peter membuat keputusan yang mengubah hidupnya.',
                'poster_url' => $tmdb . '/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
            ],
            [
                'judul'      => 'Joker',
                'tahun'      => 2019,
                'genre'      => 'Drama',
                'sinopsis'   => 'Arthur Fleck adalah seorang komedian gagal yang hidup di Gotham City yang penuh kekerasan. Dikucilkan oleh masyarakat dan diabaikan oleh sistem, ia perlahan berubah menjadi sosok kriminal yang dikenal sebagai Joker. Film ini menggambarkan transformasi tragis seorang pria yang terpinggirkan oleh dunia yang tidak peduli.',
                'poster_url' => $tmdb . '/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg',
            ],
            [
                'judul'      => 'Parasite',
                'tahun'      => 2019,
                'genre'      => 'Thriller',
                'sinopsis'   => 'Keluarga Kim yang miskin secara cerdik menyusup ke kehidupan keluarga Park yang kaya raya. Satu per satu anggota keluarga Kim mendapatkan pekerjaan di rumah Park dengan identitas palsu. Namun sebuah kejadian tak terduga mengungkap rahasia gelap yang mengubah nasib kedua keluarga secara dramatis.',
                'poster_url' => $tmdb . '/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
            ],
            [
                'judul'      => 'Oppenheimer',
                'tahun'      => 2023,
                'genre'      => 'Biography',
                'sinopsis'   => 'Kisah J. Robert Oppenheimer, fisikawan yang memimpin Proyek Manhattan untuk mengembangkan bom atom pertama di dunia. Film ini mengeksplorasi dilema moral yang dihadapinya, mulai dari ambisi ilmiah hingga dampak mengerikan dari ciptaannya. Keberhasilannya mengubah dunia selamanya, namun juga menghantui nuraninya.',
                'poster_url' => $tmdb . '/8Gxv8gSFCU0XGDykEGv7zR1n2ua.jpg',
            ],
            [
                'judul'      => 'The Godfather',
                'tahun'      => 1972,
                'genre'      => 'Drama',
                'sinopsis'   => 'Don Vito Corleone adalah kepala keluarga mafia paling berkuasa di New York. Ketika ia menolak terlibat dalam bisnis narkoba, perang antar keluarga mafia pun pecah. Putra bungsunya, Michael, yang awalnya menolak kehidupan kriminal, terpaksa mengambil alih kekuasaan keluarga setelah serangkaian peristiwa berdarah.',
                'poster_url' => $tmdb . '/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
            ],
            [
                'judul'      => 'Forrest Gump',
                'tahun'      => 1994,
                'genre'      => 'Drama',
                'sinopsis'   => 'Forrest Gump adalah pria sederhana dengan IQ di bawah rata-rata namun memiliki hati yang murni. Secara tidak sengaja, ia terlibat dalam berbagai peristiwa bersejarah Amerika dari tahun 1950-an hingga 1980-an. Film ini mengisahkan perjalanan hidupnya yang luar biasa, termasuk cinta seumur hidupnya pada Jenny.',
                'poster_url' => $tmdb . '/arw2vcBveWOVZr6pxd9XTd1TdQa.jpg',
            ],
            [
                'judul'      => 'Fight Club',
                'tahun'      => 1999,
                'genre'      => 'Thriller',
                'sinopsis'   => 'Seorang pekerja kantoran yang tidak puas dengan hidupnya bertemu dengan Tyler Durden, seorang pria karismatik dan anarkis. Bersama mereka mendirikan Fight Club, sebuah klub pertarungan rahasia yang berkembang menjadi gerakan bawah tanah. Film ini mengeksplorasi maskulinitas, konsumerisme, dan identitas diri.',
                'poster_url' => $tmdb . '/pB8BM7pdSp6B6Ih7QZ4DrQ3PmJK.jpg',
            ],
            [
                'judul'      => 'Pulp Fiction',
                'tahun'      => 1994,
                'genre'      => 'Drama',
                'sinopsis'   => 'Cerita-cerita yang saling terkait tentang dunia kriminal Los Angeles. Dua pembunuh bayaran yang filosofis, istri seorang gangster, seorang petinju, dan sepasang perampok restoran menjadi bagian dari narasi non-linear yang brilian. Film ini mengubah cara orang memandang sinema modern.',
                'poster_url' => $tmdb . '/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
            ],
            [
                'judul'      => 'The Matrix',
                'tahun'      => 1999,
                'genre'      => 'Sci-Fi',
                'sinopsis'   => 'Thomas Anderson, seorang programmer komputer, menemukan bahwa realitas yang ia kenal sebenarnya adalah simulasi komputer yang disebut Matrix. Dengan bantuan Morpheus dan Trinity, ia mengetahui bahwa ia adalah "The One" yang ditakdirkan untuk membebaskan umat manusia dari kontrol mesin. Dunia tidak akan pernah sama lagi.',
                'poster_url' => $tmdb . '/f89U3ADr1oiB1s9GkdPOEpXUk5H.jpg',
            ],
            [
                'judul'      => 'Gladiator',
                'tahun'      => 2000,
                'genre'      => 'Action',
                'sinopsis'   => 'Maximus, jenderal Romawi yang setia, dikhianati oleh Commodus, putra kaisar yang haus kekuasaan. Keluarganya dibunuh dan ia dijual sebagai budak gladiator. Dengan keahlian bertarung yang luar biasa, Maximus naik menjadi gladiator paling populer di Roma, bertekad membalas dendam dan mengembalikan kehormatan Republik Roma.',
                'poster_url' => $tmdb . '/ty8TGRuvJLPUmAR1H1nRIsgCLYh.jpg',
            ],
            [
                'judul'      => 'Titanic',
                'tahun'      => 1997,
                'genre'      => 'Romance',
                'sinopsis'   => 'Jack Dawson, seorang seniman miskin, memenangkan tiket kapal Titanic dalam permainan kartu. Di atas kapal mewah tersebut, ia bertemu dan jatuh cinta dengan Rose DeWitt Bukater, seorang wanita kaya yang terjebak dalam pertunangan tanpa cinta. Kisah cinta mereka diuji ketika kapal legendaris itu menabrak gunung es.',
                'poster_url' => $tmdb . '/9xjZS2rlVxm8SFx8kPC3aIGCOYQ.jpg',
            ],
            [
                'judul'      => 'The Lord of the Rings: The Return of the King',
                'tahun'      => 2003,
                'genre'      => 'Adventure',
                'sinopsis'   => 'Frodo dan Sam semakin dekat ke Gunung Doom untuk menghancurkan Cincin Utama, sementara Aragorn memimpin pasukan manusia dalam pertempuran terakhir melawan Sauron. Semua harapan Middle-earth bergantung pada keberhasilan misi kecil ini. Film epik yang menjadi penutup sempurna dari trilogi fantasi terbesar sepanjang masa.',
                'poster_url' => $tmdb . '/rCzpDGLbOoPwLjy3OAm5NUPOTrC.jpg',
            ],
            [
                'judul'      => 'Harry Potter and the Sorcerer\'s Stone',
                'tahun'      => 2001,
                'genre'      => 'Adventure',
                'sinopsis'   => 'Harry Potter, yatim piatu yang tinggal bersama keluarga Dursley yang kejam, menemukan bahwa ia adalah seorang penyihir. Ia diterima di Hogwarts School of Witchcraft and Wizardry, di mana ia menemukan teman-teman sejati dan bakat sihir yang luar biasa. Namun ancaman misterius mengintai di sekolah barunya.',
                'poster_url' => $tmdb . '/wuMc08IPKEatf9rnMNXvIDxqP4W.jpg',
            ],
            [
                'judul'      => 'Jurassic Park',
                'tahun'      => 1993,
                'genre'      => 'Adventure',
                'sinopsis'   => 'John Hammond berhasil mengkloning dinosaurus dan membuat taman hiburan di sebuah pulau terpencil. Sebelum pembukaan, ia mengundang sekelompok ahli untuk mengevaluasi keamanan taman. Ketika sistem keamanan gagal akibat sabotase, dinosaurus predator terlepas dan mengubah taman impian menjadi mimpi buruk yang mematikan.',
                'poster_url' => $tmdb . '/oU7Oez2kCnRi25MJZMJ2JzMl39y.jpg',
            ],
            [
                'judul'      => 'The Lion King',
                'tahun'      => 1994,
                'genre'      => 'Animation',
                'sinopsis'   => 'Simba, anak singa yang ditakdirkan menjadi raja, terpaksa melarikan diri dari Pride Lands setelah kematian ayahnya, Raja Mufasa, yang didalangi oleh pamannya sendiri, Scar. Dengan bantuan Timon dan Pumbaa, Simba tumbuh dewasa dan akhirnya harus menghadapi masa lalunya untuk merebut kembali takhtanya yang sah.',
                'poster_url' => $tmdb . '/sKCr78MXSLixwmZ8DyJLrpMsd15.jpg',
            ],
            [
                'judul'      => 'Spirited Away',
                'tahun'      => 2001,
                'genre'      => 'Animation',
                'sinopsis'   => 'Chihiro, gadis berusia 10 tahun, tanpa sengaja memasuki dunia roh ketika keluarganya tersesat dalam perjalanan pindah rumah. Orang tuanya berubah menjadi babi, dan Chihiro harus bekerja di pemandian roh milik penyihir Yubaba untuk menyelamatkan mereka. Film animasi Jepang yang memenangkan Oscar ini penuh dengan keajaiban.',
                'poster_url' => $tmdb . '/39wmItIWsg5sZMyRUHLkWBcuVCM.jpg',
            ],
            [
                'judul'      => 'Coco',
                'tahun'      => 2017,
                'genre'      => 'Animation',
                'sinopsis'   => 'Miguel Rivera bermimpi menjadi musisi, namun keluarganya melarang keras segala bentuk musik. Pada Hari Orang Mati, Miguel secara ajaib terjebak di Dunia Orang Mati. Untuk kembali ke dunia nyata, ia harus mendapat restu dari leluhurnya dan mengungkap misteri di balik larangan musik keluarganya.',
                'poster_url' => $tmdb . '/gGEsBPAijhVUFoiNpgZXqRVWJt2.jpg',
            ],
            [
                'judul'      => 'Inside Out',
                'tahun'      => 2015,
                'genre'      => 'Animation',
                'sinopsis'   => 'Di dalam pikiran Riley, seorang gadis berusia 11 tahun, tinggal lima emosi: Joy, Sadness, Anger, Fear, dan Disgust. Ketika Riley pindah ke kota baru, Joy dan Sadness tanpa sengaja terlempar ke area pikiran yang jauh. Mereka harus berpetualang kembali ke pusat kendali sebelum Riley kehilangan semua kebahagiaan dan kenangan indahnya.',
                'poster_url' => $tmdb . '/2H1TmgdfNtsKlU9jKdeNyYL5y8T.jpg',
            ],
            [
                'judul'      => 'Dune',
                'tahun'      => 2021,
                'genre'      => 'Sci-Fi',
                'sinopsis'   => 'Paul Atreides, pewaris muda bangsawan House Atreides, harus melakukan perjalanan ke planet paling berbahaya di alam semesta, Arrakis, untuk menjamin masa depan keluarganya. Ketika konflik mematikan meletus atas sumber daya eksklusif planet tersebut, Paul harus menghadapi takdirnya sebagai pemimpin yang dinubuatkan.',
                'poster_url' => $tmdb . '/d5NXSklXo0qyIYkgV94XAgMIckC.jpg',
            ],
            [
                'judul'      => 'John Wick',
                'tahun'      => 2014,
                'genre'      => 'Action',
                'sinopsis'   => 'John Wick, mantan pembunuh bayaran legendaris, hidup tenang setelah pensiun. Namun kedamaiannya hancur ketika putra seorang bos mafia Rusia mencuri mobilnya dan membunuh anjingnya, hadiah terakhir dari mendiang istrinya. Wick kembali ke dunia gelap untuk membalas dendam, mengingatkan semua orang mengapa namanya begitu ditakuti.',
                'poster_url' => $tmdb . '/fZPSd91yGE9fCcCe6OoQr6E3Bev.jpg',
            ],
            [
                'judul'      => 'The Social Network',
                'tahun'      => 2010,
                'genre'      => 'Drama',
                'sinopsis'   => 'Kisah pendirian Facebook oleh Mark Zuckerberg saat masih menjadi mahasiswa Harvard. Film ini mengeksplorasi ambisi, pengkhianatan, dan persahabatan yang hancur di balik terciptanya jejaring sosial terbesar di dunia. Dari kamar asrama kampus hingga menjadi miliarder termuda, perjalanan Zuckerberg penuh kontroversi.',
                'poster_url' => $tmdb . '/n0ybibhJtQ5icDqTp8eRytcIz9Q.jpg',
            ],
            [
                'judul'      => 'Get Out',
                'tahun'      => 2017,
                'genre'      => 'Horror',
                'sinopsis'   => 'Chris, seorang pria kulit hitam, mengunjungi keluarga pacarnya Rose yang berkulit putih untuk pertama kalinya. Awalnya ia menganggap sikap berlebihan keluarga Rose karena gugup soal hubungan antar-ras. Namun serangkaian penemuan mengerikan mengungkap kebenaran yang jauh lebih menyeramkan dari prasangka rasial biasa.',
                'poster_url' => $tmdb . '/qbaIViAyzLDLnMTPMFMZoMZ5cEv.jpg',
            ],
            [
                'judul'      => 'Whiplash',
                'tahun'      => 2014,
                'genre'      => 'Drama',
                'sinopsis'   => 'Andrew Neiman, drummer muda berbakat, bergabung dengan band jazz elit yang dipimpin oleh Terence Fletcher, konduktor yang terkenal kejam. Fletcher menggunakan metode pengajaran yang brutal dan psikologis untuk mendorong siswanya melampaui batas. Andrew harus memutuskan seberapa jauh ia bersedia mengorbankan segalanya demi kesempurnaan.',
                'poster_url' => $tmdb . '/7fn624j5lj3xTme2SgiLCeuedmO.jpg',
            ],
            [
                'judul'      => 'The Grand Budapest Hotel',
                'tahun'      => 2014,
                'genre'      => 'Comedy',
                'sinopsis'   => 'Gustave H. adalah concierge legendaris di hotel mewah Grand Budapest di negara fiktif Zubrowka. Ketika tamu lansia favoritnya meninggal dan mewariskan lukisan berharga kepadanya, Gustave dan lobby boy Zero terlibat dalam petualangan gila yang melibatkan pencurian seni, pembunuhan, dan perseteruan keluarga kaya.',
                'poster_url' => $tmdb . '/eWdyYQreja6JGCzqHWXpWHDrrPo.jpg',
            ],
            [
                'judul'      => 'Mad Max: Fury Road',
                'tahun'      => 2015,
                'genre'      => 'Action',
                'sinopsis'   => 'Di dunia pasca-apokaliptik yang gersang, Max Rockatansky bergabung dengan Imperator Furiosa dalam pelariannya dari tiran Immortan Joe. Furiosa membawa istri-istri Joe menuju tanah hijau yang dijanjikan. Pengejaran epik melintasi gurun yang mematikan pun dimulai, penuh dengan aksi kendaraan dan ledakan yang spektakuler.',
                'poster_url' => $tmdb . '/8tZYtuWezp8JbcsvHYO0O46tFbo.jpg',
            ],
            [
                'judul'      => 'Your Name',
                'tahun'      => 2016,
                'genre'      => 'Animation',
                'sinopsis'   => 'Mitsuha, gadis desa di Jepang, dan Taki, pemuda kota Tokyo, secara misterius bertukar tubuh saat tidur. Mereka mulai berkomunikasi melalui catatan dan pesan. Namun ketika mereka berusaha bertemu di dunia nyata, mereka menemukan bahwa waktu tidak berjalan seperti yang mereka kira. Sebuah bencana besar mengancam semuanya.',
                'poster_url' => $tmdb . '/q719jXXEhI5qUsgi4aKP8MRBMaG.jpg',
            ],
            [
                'judul'      => 'La La Land',
                'tahun'      => 2016,
                'genre'      => 'Romance',
                'sinopsis'   => 'Mia, aktris yang berjuang di Hollywood, bertemu Sebastian, pianis jazz yang bermimpi membuka klub musiknya sendiri. Mereka jatuh cinta dan saling mendukung mengejar impian masing-masing. Namun ketika karir mulai menuntut, mereka harus memilih antara cinta dan ambisi dalam kisah musikal yang indah dan mengharukan ini.',
                'poster_url' => $tmdb . '/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg',
            ],
            [
                'judul'      => 'Everything Everywhere All at Once',
                'tahun'      => 2022,
                'genre'      => 'Sci-Fi',
                'sinopsis'   => 'Evelyn Wang, pemilik laundry yang kelelahan, menemukan bahwa ia harus menjelajahi multiverse dan terhubung dengan versi dirinya di alam semesta lain untuk menyelamatkan dunia. Sambil menghadapi audit pajak dan masalah keluarga, ia harus melawan ancaman kosmis yang bisa menghancurkan seluruh realitas.',
                'poster_url' => $tmdb . '/w3LxiVYdWWRvEVdn5RYq6jIqkb1.jpg',
            ],
            [
                'judul'      => 'Django Unchained',
                'tahun'      => 2012,
                'genre'      => 'Action',
                'sinopsis'   => 'Django, seorang budak yang dibebaskan oleh pemburu bayaran Jerman bernama Dr. King Schultz, belajar menjadi pemburu bayaran dan bersama mereka mencari istri Django yang masih diperbudak di perkebunan kejam milik Calvin Candie. Film ini menggabungkan Western, aksi, dan drama dengan gaya khas Tarantino.',
                'poster_url' => $tmdb . '/7oWY8VDWW7thTzWh3OKYRkWUlD5.jpg',
            ],
            [
                'judul'      => 'The Batman',
                'tahun'      => 2022,
                'genre'      => 'Action',
                'sinopsis'   => 'Batman yang masih muda menyelidiki serangkaian pembunuhan sadis yang dilakukan oleh sosok misterius bernama Riddler. Setiap korban adalah pejabat korup Gotham City, dan petunjuk-petunjuk yang ditinggalkan mengarah pada rahasia gelap yang melibatkan keluarga Wayne sendiri. Batman harus menghadapi kebenaran yang mengubah segalanya.',
                'poster_url' => $tmdb . '/74xTEgt7R36Fpooo50r9T25onhq.jpg',
            ],
            [
                'judul'      => 'Top Gun: Maverick',
                'tahun'      => 2022,
                'genre'      => 'Action',
                'sinopsis'   => 'Setelah lebih dari 30 tahun mengabdi sebagai pilot angkatan laut, Pete "Maverick" Mitchell kembali ke Top Gun sebagai instruktur. Ia harus melatih sekelompok pilot muda untuk misi yang hampir mustahil, termasuk putra dari sahabatnya yang telah tiada. Maverick harus menghadapi masa lalunya sambil mendorong batas kemampuan terbang.',
                'poster_url' => $tmdb . '/62HCnUTziyWQpDaBO2i1DX17ljH.jpg',
            ],
            [
                'judul'      => 'Black Panther',
                'tahun'      => 2018,
                'genre'      => 'Action',
                'sinopsis'   => "T'Challa kembali ke Wakanda, negara Afrika tersembunyi yang sangat maju teknologinya, untuk dinobatkan menjadi raja setelah kematian ayahnya. Namun kekuasaannya ditantang oleh Erik Killmonger, sepupu yang memiliki rencana berbeda untuk Wakanda. T'Challa harus membuktikan dirinya layak menjadi Black Panther dan raja.",
                'poster_url' => $tmdb . '/uxzzxijgPIY7slzFvMotPv8wjKA.jpg',
            ],
            [
                'judul'      => 'Guardians of the Galaxy',
                'tahun'      => 2014,
                'genre'      => 'Comedy',
                'sinopsis'   => 'Peter Quill, petualang luar angkasa, secara tidak sengaja mencuri artefak kuat yang diincar oleh penjahat kosmis Ronan. Untuk bertahan hidup, ia membentuk aliansi tidak mungkin dengan sekelompok makhluk aneh: Gamora, Drax, Rocket Raccoon, dan Groot. Bersama mereka menjadi Guardians of the Galaxy.',
                'poster_url' => $tmdb . '/r7vmZjiyZw9rpJMQJp0Rv9pvKDj.jpg',
            ],
            [
                'judul'      => 'Bohemian Rhapsody',
                'tahun'      => 2018,
                'genre'      => 'Biography',
                'sinopsis'   => 'Kisah Freddie Mercury, vokalis legendaris band Queen, dari awal karirnya hingga puncak kejayaan. Film ini menelusuri perjalanan band dalam menciptakan musik yang mengubah dunia rock, perjuangan Mercury dengan identitasnya, dan penampilan epik mereka di konser Live Aid 1985 yang menjadi salah satu momen terbesar dalam sejarah musik.',
                'poster_url' => $tmdb . '/lHu1wtNaczFPGFDTrjCSzeLPTKN.jpg',
            ],
        ];
    }
}
