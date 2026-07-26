<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureUserIsAdmin Middleware
 *
 * Memblokir akses pengguna yang bukan admin (role != 'admin') ke seluruh
 * route yang dilindungi prefix '/admin'. Jika bukan admin, akan mendapat
 * respon 403 Forbidden — sesuai spec Global Edge Case §1 (Manipulasi URL Manual).
 *
 * Alias yang didaftarkan: 'role'
 * Cara penggunaan di routes: ->middleware('role:admin')
 */
class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role = 'admin'): Response
    {
        // Pengecekan ganda: harus sudah auth + role harus cocok
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk guru/admin.');
        }

        return $next($request);
    }
}
