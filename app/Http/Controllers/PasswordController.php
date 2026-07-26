<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Task 2.3: Tampilkan form ubah kata sandi bawaan sekolah.
     * Halaman ini hanya dapat diakses oleh siswa yang sudah login
     * namun belum mengubah password default-nya (is_password_changed = false).
     */
    public function edit(): View
    {
        return view('auth.change-password');
    }

    /**
     * Task 2.3: Proses simpan kata sandi baru.
     *
     * Alur:
     *  1. Validasi: password baru min 8 karakter, konfirmasi cocok
     *  2. Enkripsi password baru dengan Hash::make()
     *  3. Set flag is_password_changed = true
     *  4. Redirect ke Beranda dengan akses penuh
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),
            ],
        ], [
            'password.required'  => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'       => 'Kata sandi minimal harus 8 karakter.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->update([
            'password'            => Hash::make($request->password),
            'is_password_changed' => true,
        ]);

        return redirect()->route('home')
            ->with('success', 'Kata sandimu berhasil diperbarui. Selamat belajar, ' . $user->name . '! 🌿');
    }
}
