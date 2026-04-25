<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Import class Auth
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles (Ini akan menangkap semua peran yang kita kirim, misal: 'admin', 'dosen')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            // Jika belum login, arahkan ke halaman login
            return redirect('login');
        }

        // 2. Cek apakah peran user ada di dalam daftar $roles yang diizinkan
        if (!in_array(Auth::user()->role, $roles)) {
            // 3. Jika tidak diizinkan, kembalikan ke dashboard dengan pesan error
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        // 4. Jika diizinkan, lanjutkan request ke halaman tujuan
        return $next($request);
    }
}