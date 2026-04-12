<?php

namespace Database\Seeders;

use App\Models\Film;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PosterFixSeeder extends Seeder
{
    /**
     * Warna-warna untuk genre film (gradient-style)
     */
    private array $genreColors = [
        'Action'    => ['bg' => [183, 28, 28],   'accent' => [244, 67, 54]],
        'Horror'    => ['bg' => [27, 20, 50],     'accent' => [156, 39, 176]],
        'Drama'     => ['bg' => [13, 71, 161],    'accent' => [33, 150, 243]],
        'Romance'   => ['bg' => [136, 14, 79],    'accent' => [233, 30, 99]],
        'Comedy'    => ['bg' => [230, 81, 0],     'accent' => [255, 152, 0]],
        'Sci-Fi'    => ['bg' => [0, 77, 64],      'accent' => [0, 150, 136]],
        'Animation' => ['bg' => [40, 53, 147],    'accent' => [63, 81, 181]],
        'Thriller'  => ['bg' => [38, 50, 56],     'accent' => [96, 125, 139]],
        'Adventure' => ['bg' => [46, 125, 50],    'accent' => [76, 175, 80]],
        'Biography' => ['bg' => [62, 39, 35],     'accent' => [121, 85, 72]],
    ];

    public function run(): void
    {
        $this->command->info('Membuat poster placeholder untuk film tanpa poster...');

        Storage::disk('public')->makeDirectory('posters');

        $films = Film::whereNull('poster')->get();
        $this->command->info("Ditemukan {$films->count()} film tanpa poster.");

        if ($films->isEmpty()) {
            $this->command->info('Semua film sudah memiliki poster!');
            return;
        }

        $fixed = 0;

        foreach ($films as $film) {
            $filename = 'posters/' . Str::slug($film->judul) . '.jpg';

            if ($this->generatePoster($film, $filename)) {
                $film->update(['poster' => $filename]);
                $this->command->info("  ✓ {$film->judul}");
                $fixed++;
            } else {
                $this->command->warn("  ✗ {$film->judul} (GD tidak tersedia)");
            }
        }

        $this->command->info('');
        $this->command->info("Selesai! {$fixed} poster dibuat.");
    }

    private function generatePoster(Film $film, string $filename): bool
    {
        if (!extension_loaded('gd')) {
            return false;
        }

        $width = 300;
        $height = 450;
        $img = imagecreatetruecolor($width, $height);

        // Ambil warna berdasarkan genre
        $colors = $this->genreColors[$film->genre] ?? $this->genreColors['Drama'];
        $bgColor = imagecolorallocate($img, $colors['bg'][0], $colors['bg'][1], $colors['bg'][2]);
        $accentColor = imagecolorallocate($img, $colors['accent'][0], $colors['accent'][1], $colors['accent'][2]);
        $white = imagecolorallocate($img, 255, 255, 255);
        $yellow = imagecolorallocate($img, 245, 197, 24);
        $lightGray = imagecolorallocate($img, 200, 200, 200);
        $darkOverlay = imagecolorallocate($img, 0, 0, 0);

        // Background
        imagefilledrectangle($img, 0, 0, $width, $height, $bgColor);

        // Gradient effect (manual - darker at bottom)
        for ($y = $height / 2; $y < $height; $y++) {
            $alpha = (int)(($y - $height / 2) / ($height / 2) * 80);
            $gradColor = imagecolorallocatealpha($img, 0, 0, 0, 127 - $alpha);
            imageline($img, 0, $y, $width, $y, $gradColor);
        }

        // Accent bar at top
        imagefilledrectangle($img, 0, 0, $width, 6, $yellow);

        // Decorative element - large accent circle
        imagefilledellipse($img, $width / 2, 160, 140, 140, $accentColor);
        imagefilledellipse($img, $width / 2, 160, 120, 120, $bgColor);

        // Film icon (simple clapperboard shape)
        $iconColor = imagecolorallocate($img, 245, 197, 24);
        // Camera/film icon using shapes
        imagefilledrectangle($img, 125, 135, 175, 175, $iconColor);
        imagefilledrectangle($img, 120, 125, 180, 140, $iconColor);
        // Stripes on clapperboard
        $stripeColor = $bgColor;
        for ($i = 0; $i < 5; $i++) {
            $x = 124 + ($i * 12);
            imageline($img, $x, 125, $x + 6, 140, $stripeColor);
            imageline($img, $x + 1, 125, $x + 7, 140, $stripeColor);
        }

        // Film title
        $title = mb_strtoupper($film->judul);
        $fontSize = 5; // GD built-in font size (1-5)

        $words = explode(' ', $title);
        $lines = [];
        $currentLine = '';
        $maxCharsPerLine = 18;

        foreach ($words as $word) {
            if (strlen($currentLine . ' ' . $word) > $maxCharsPerLine && $currentLine !== '') {
                $lines[] = $currentLine;
                $currentLine = $word;
            } else {
                $currentLine = $currentLine ? $currentLine . ' ' . $word : $word;
            }
        }
        if ($currentLine) $lines[] = $currentLine;

        $lineHeight = 18;
        $startY = 240;

        foreach ($lines as $i => $line) {
            $textWidth = strlen($line) * imagefontwidth($fontSize);
            $x = ($width - $textWidth) / 2;
            $y = $startY + ($i * $lineHeight);
            imagestring($img, $fontSize, (int)$x, (int)$y, $line, $white);
        }

        // Year and genre
        $yearGenre = $film->tahun . ' | ' . strtoupper($film->genre);
        $infoWidth = strlen($yearGenre) * imagefontwidth(3);
        $infoX = ($width - $infoWidth) / 2;
        $infoY = $startY + (count($lines) * $lineHeight) + 15;
        imagestring($img, 3, (int)$infoX, (int)$infoY, $yearGenre, $yellow);

        // Rating stars decoration
        $starY = (int)$infoY + 25;
        $starText = '★ ★ ★ ★ ★';
        $starWidth = strlen($starText) * imagefontwidth(3);
        imagestring($img, 3, (int)(($width - $starWidth) / 2), $starY, $starText, $lightGray);

        // Bottom bar
        imagefilledrectangle($img, 0, $height - 30, $width, $height, imagecolorallocatealpha($img, 0, 0, 0, 50));
        $brand = 'CINERATE';
        $brandWidth = strlen($brand) * imagefontwidth(4);
        imagestring($img, 4, (int)(($width - $brandWidth) / 2), $height - 22, $brand, $yellow);

        // Border
        imagerectangle($img, 0, 0, $width - 1, $height - 1, imagecolorallocatealpha($img, 255, 255, 255, 100));

        // Save
        $path = Storage::disk('public')->path($filename);
        imagejpeg($img, $path, 90);
        unset($img);

        return true;
    }
}
