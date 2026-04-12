<?php

namespace App\Http\Controllers;

// ============================================
// CONTROLLER: AuthController
// Menangani proses autentikasi:
// - Register & Login pengguna biasa
// - Login & Logout admin (terpisah)
// ============================================

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ============================================
    // AUTENTIKASI PENGGUNA BIASA
    // ============================================

    /**
     * Menampilkan halaman register pengguna
     * URL: GET /register
     */
    public function showRegister()
    {
        // Jika sudah login, langsung ke home
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    /**
     * Memproses registrasi pengguna baru
     * URL: POST /register
     */
    public function register(Request $request)
    {
        // Validasi input form register
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Buat user baru dengan role 'user'
        $user = User::create([
            'username' => $request->username,
            'password' => $request->password, // otomatis di-hash oleh cast
            'role'     => 'user',
        ]);

        // Langsung login setelah register
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
                         ->with('sukses', 'Registrasi berhasil! Selamat datang, ' . $user->username . '!');
    }

    /**
     * Menampilkan halaman login pengguna
     * URL: GET /login
     */
    public function showLoginUser()
    {
        // Jika sudah login, langsung ke home
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Memproses login pengguna biasa
     * URL: POST /login
     */
    public function loginUser(Request $request)
    {
        // Validasi input form login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Jika yang login adalah admin, arahkan ke halaman login admin
            if ($user->isAdmin()) {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Silakan login melalui halaman Admin Login.',
                ])->withInput($request->only('username'));
            }

            // Regenerasi session untuk keamanan
            $request->session()->regenerate();
            return redirect()->intended(route('home'))
                             ->with('sukses', 'Login berhasil! Selamat datang, ' . $user->username . '!');
        }

        // Login gagal
        return back()->withErrors([
            'username' => 'Username atau password salah!',
        ])->withInput($request->only('username'));
    }

    /**
     * Memproses logout pengguna biasa
     * URL: POST /logout
     */
    public function logoutUser(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                         ->with('sukses', 'Anda telah berhasil logout.');
    }

    // ============================================
    // AUTENTIKASI ADMIN (terpisah)
    // ============================================

    /**
     * Menampilkan halaman login admin
     * URL: GET /admin/login
     */
    public function showLogin()
    {
        // Jika sudah login sebagai admin, langsung ke dashboard
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    /**
     * Memproses login admin
     * URL: POST /admin/login
     */
    public function login(Request $request)
    {
        // Validasi input form login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Mencoba login dengan username dan password
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            }

            // Bukan admin, logout dan tampilkan error
            Auth::logout();
            return back()->withErrors([
                'username' => 'Akun ini bukan admin!',
            ]);
        }

        // Login gagal
        return back()->withErrors([
            'username' => 'Username atau password salah!',
        ])->withInput($request->only('username'));
    }

    /**
     * Memproses logout admin
     * URL: POST /admin/logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
