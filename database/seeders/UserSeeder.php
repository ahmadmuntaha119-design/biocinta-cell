<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Task 10.1 (Preview) & Task 2.x: Admin Seeder
 * Dijalankan saat `php artisan db:seed` untuk membuat akun admin/guru pertama.
 * Akun ini sudah is_password_changed = true agar langsung bisa masuk dashboard.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Akun Admin/Guru Utama ----
        User::updateOrCreate(
            ['nis' => 'ADMIN001'],
            [
                'name'                 => 'Guru Biologi (Admin)',
                'role'                 => 'admin',
                'password'             => Hash::make('Admin@BioCinta1'),
                'is_password_changed'  => true, // Admin sudah set password kuat langsung
            ]
        );

        // ---- Akun Siswa Demo (untuk testing alur first-login) ----
        User::updateOrCreate(
            ['nis' => '1234567890'],
            [
                'name'                => 'Siswa Demo (Kelas XI)',
                'role'                => 'student',
                'password'            => Hash::make('biocinta123'),  // Password bawaan sekolah
                'is_password_changed' => false, // false = wajib ganti saat pertama login
            ]
        );

        $this->command->info('✅ UserSeeder: Admin & Siswa Demo berhasil dibuat.');
        $this->command->line('   → Admin NIS: ADMIN001 | Password: Admin@BioCinta1');
        $this->command->line('   → Siswa NIS: 1234567890 | Password: biocinta123 (wajib ganti)');
    }
}
