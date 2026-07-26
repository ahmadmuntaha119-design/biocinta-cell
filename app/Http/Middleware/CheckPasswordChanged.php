<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Task 2.2: Middleware Proteksi Kata Sandi Default
 *
 * Middleware ini menjaga SELURUH route yang membutuhkan akses penuh.
 * Logika interseptor:
 *   - Jika user SUDAH login DAN is_password_changed masih false
 *     → Blokir akses dan paksa redirect ke halaman ubah password
 *   - Jika user sudah ganti password → lanjutkan request seperti biasa
 *
 * Didaftarkan sebagai alias 'verified.password' di bootstrap/app.php
 */
class CheckPasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && ! Auth::user()->is_password_changed) {
            // Jangan redirect jika memang sedang di halaman change-password
            // (mencegah infinite redirect loop)
            if (! $request->routeIs('password.change') && ! $request->routeIs('password.update')) {
                return redirect()->route('password.change')
                    ->with('warning', 'Kamu harus mengganti kata sandi bawaan sekolah terlebih dahulu untuk dapat mengakses halaman ini.');
            }
        }

        return $next($request);
    }
}
