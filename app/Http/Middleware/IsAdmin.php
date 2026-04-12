<?php

namespace App\Http\Middleware;

// ============================================
// MIDDLEWARE: IsAdmin
// Mengecek apakah user yang login adalah admin
// Jika bukan admin, akan diarahkan ke halaman home
// ============================================

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Menangani request yang masuk
     * Middleware ini dijalankan SEBELUM controller
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user sudah login DAN memiliki role admin
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            // Jika admin, lanjutkan request ke controller
            return $next($request);
        }

        // Jika bukan admin, arahkan ke halaman login
        return redirect()->route('admin.login')
                         ->withErrors(['username' => 'Anda harus login sebagai admin!']);
    }
}
