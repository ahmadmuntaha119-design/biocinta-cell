<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminStudentController extends Controller
{
    /**
     * Tampilkan daftar siswa dengan pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::where('role', 'student');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // Ambil data paginated
        $students = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();

        // Hitung statistik
        $totalStudents = User::where('role', 'student')->count();
        $activeLogins = User::where('role', 'student')->where('is_password_changed', true)->count();
        $inactiveLogins = User::where('role', 'student')->where('is_password_changed', false)->count();

        return view('admin.students.index', compact(
            'students',
            'search',
            'totalStudents',
            'activeLogins',
            'inactiveLogins'
        ));
    }

    /**
     * Mengalirkan file template CSV untuk diunduh guru.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=template_import_siswa.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            // Header kolom
            fputcsv($file, ['nis', 'name']);
            
            // Baris contoh
            fputcsv($file, ['1234567801', 'Ahmad Fauzi']);
            fputcsv($file, ['1234567802', 'Siti Aminah']);
            fputcsv($file, ['1234567803', 'Budi Santoso']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Impor siswa massal dari file CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'file.required' => 'Silakan unggah berkas CSV terlebih dahulu.',
            'file.mimes' => 'Berkas harus berupa file format CSV.',
            'file.max' => 'Ukuran berkas tidak boleh melebihi 2MB.',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // Buka file CSV
        if (($handle = fopen($filePath, 'r')) === false) {
            return redirect()->back()->with('error', 'Gagal membuka berkas CSV.');
        }

        // Bersihkan UTF-8 BOM jika ada
        $headerLine = fgets($handle);
        if ($headerLine !== false) {
            // Strip BOM (EF BB BF) jika terdeteksi di awal file
            if (substr($headerLine, 0, 3) === pack('CCC', 0xEF, 0xBB, 0xBF)) {
                $headerLine = substr($headerLine, 3);
            }
            // Parse line tadi menjadi header array
            $header = str_getcsv($headerLine, ',');
            // Jika koma tidak menghasilkan array, coba semicolon
            if (count($header) <= 1) {
                $header = str_getcsv($headerLine, ';');
                $delimiter = ';';
            } else {
                $delimiter = ',';
            }
        } else {
            fclose($handle);
            return redirect()->back()->with('error', 'Berkas CSV kosong atau tidak valid.');
        }

        // Sanitasi header: trim spasi dan ubah ke lowercase
        $header = array_map(function ($h) {
            return strtolower(trim($h));
        }, $header);

        // Temukan index kolom nis dan name
        $nisIndex = array_search('nis', $header);
        // Cari variasi nama kolom name (name, nama, nama siswa, nama_lengkap)
        $nameIndex = false;
        foreach (['name', 'nama', 'nama siswa', 'nama_lengkap', 'fullname'] as $var) {
            $idx = array_search($var, $header);
            if ($idx !== false) {
                $nameIndex = $idx;
                break;
            }
        }

        if ($nisIndex === false || $nameIndex === false) {
            fclose($handle);
            return redirect()->back()->with('error', 'Format header CSV salah. Pastikan terdapat kolom "nis" dan "name".');
        }

        $inserted = 0;
        $updated = 0;
        $failed = 0;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                // Lewati baris kosong atau tidak lengkap
                if (count($row) <= max($nisIndex, $nameIndex)) {
                    $failed++;
                    continue;
                }

                $nis = trim($row[$nisIndex]);
                $name = trim($row[$nameIndex]);

                // Validasi data baris
                if (empty($nis) || empty($name)) {
                    $failed++;
                    continue;
                }

                // Cek panjang NIS (max 20) dan Nama (max 100)
                if (strlen($nis) > 20 || strlen($name) > 100) {
                    $failed++;
                    continue;
                }

                // Cek apakah NIS adalah numerik atau alphanumeric biasa
                if (!preg_match('/^[a-zA-Z0-9]+$/', $nis)) {
                    $failed++;
                    continue;
                }

                // Cek duplikat NIS
                $existingUser = User::where('nis', $nis)->first();

                if ($existingUser) {
                    // Hanya update nama jika ada perubahan dan user adalah student
                    if ($existingUser->role === 'student') {
                        if ($existingUser->name !== $name) {
                            $existingUser->update(['name' => $name]);
                            $updated++;
                        }
                    } else {
                        // Jika NIS yang diimpor bertabrakan dengan akun admin (role: admin)
                        $failed++;
                    }
                } else {
                    // Buat user baru dengan password default
                    User::create([
                        'nis' => $nis,
                        'name' => $name,
                        'role' => 'student',
                        'password' => Hash::make('biocinta123'),
                        'is_password_changed' => false,
                    ]);
                    $inserted++;
                }
            }

            fclose($handle);
            DB::commit();

            $message = "Impor data siswa selesai. " .
                       "Baru ditambahkan: {$inserted} siswa, " .
                       "Nama diperbarui: {$updated} siswa.";

            if ($failed > 0) {
                return redirect()->back()->with('success', $message)->with('warning', "Ada {$failed} baris dilewati karena tidak memenuhi format valid.");
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            fclose($handle);
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses impor: ' . $e->getMessage());
        }
    }

    /**
     * Reset password siswa ke default sekolah (biocinta123).
     */
    public function resetPassword(User $user)
    {
        if ($user->role !== 'student') {
            return redirect()->back()->with('error', 'Hanya password siswa yang dapat direset.');
        }

        $user->update([
            'password' => Hash::make('biocinta123'),
            'is_password_changed' => false,
        ]);

        return redirect()->back()->with('success', "Password siswa {$user->name} (NIS: {$user->nis}) berhasil direset menjadi 'biocinta123'.");
    }

    /**
     * Hapus data satu siswa secara individual.
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'student') {
            return redirect()->back()->with('error', 'Tidak dapat menghapus pengguna non-siswa.');
        }

        $name = $user->name;
        $user->delete(); // Otomatis cascade delete records yang berelasi di DB

        return redirect()->back()->with('success', "Data siswa {$name} berhasil dihapus beserta seluruh riwayat aktivitasnya.");
    }

    /**
     * Hapus banyak data siswa sekaligus (Bulk Delete).
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
        ], [
            'student_ids.required' => 'Pilih setidaknya satu siswa untuk dihapus.',
        ]);

        $ids = $request->input('student_ids');

        // Pastikan hanya menghapus user ber-role student untuk keamanan ekstra
        $deletedCount = User::whereIn('id', $ids)->where('role', 'student')->delete();

        return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} siswa beserta seluruh riwayat aktivitasnya secara massal.");
    }
}
