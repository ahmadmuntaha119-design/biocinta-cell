<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Task 2.1: Tampilkan halaman login.
     * Sudah diproteksi middleware 'guest' di routes — redirect ke home jika sudah login.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Task 2.1: Proses login menggunakan NIS + password.
     *
     * Alur:
     *  1. Validasi input
     *  2. Coba autentikasi via Auth::attempt() dengan field 'nis'
     *  3. Jika gagal → lempar ValidationException
     *  4. Jika berhasil → regenerate session (CSRF protection)
     *  5. Cek is_password_changed → arahkan ke halaman ubah password jika false
     *  6. Redirect ke home dengan akses penuh
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nis'      => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ], [
            'nis.required'      => 'Nomor Induk Siswa (NIS) wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Attempt login dengan field 'nis' (bukan 'email')
        if (! Auth::attempt(['nis' => $credentials['nis'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'nis' => 'NIS atau kata sandi yang kamu masukkan salah. Periksa kembali.',
            ]);
        }

        // Wajib regenerasi session ID setelah login untuk mencegah session fixation attack
        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();

        // Gerbang keamanan: paksa ganti password bawaan sekolah
        if (! $user->is_password_changed) {
            return redirect()->route('password.change')
                ->with('info', 'Selamat datang! Demi keamanan akunmu, harap ganti kata sandi bawaan sekolah terlebih dahulu.');
        }

        return redirect()->intended(route('home'))
            ->with('success', 'Selamat datang kembali, ' . $user->name . '! 🌿');
    }

    /**
     * Task 2.1: Logout aman.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Kamu telah berhasil keluar dari sistem.');
    }
}
