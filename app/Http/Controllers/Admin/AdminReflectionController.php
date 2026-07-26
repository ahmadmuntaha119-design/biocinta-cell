<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reflection;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminReflectionController extends Controller
{
    /**
     * Tampilkan daftar seluruh refleksi siswa.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $reflections = Reflection::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalCount = Reflection::count();

        return view('admin.reflections.index', compact('reflections', 'search', 'totalCount'));
    }

    /**
     * Ekspor daftar refleksi siswa ke dalam file CSV (Excel-Compatible).
     */
    public function export(Request $request)
    {
        $search = $request->input('search');

        $reflections = Reflection::with('user')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=jurnal-refleksi-' . now()->format('Y-m-d') . '.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () use ($reflections) {
            $file = fopen('php://output', 'w');
            
            // UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header kolom
            fputcsv($file, ['No', 'Tanggal', 'Nama Siswa', 'NIS', 'Connection', 'Wisdom', 'Implementation']);

            foreach ($reflections as $index => $r) {
                fputcsv($file, [
                    $index + 1,
                    $r->created_at->format('d-m-Y H:i'),
                    $r->user->name ?? '—',
                    $r->user->nis ?? '—',
                    $r->connection_text,
                    $r->wisdom_text,
                    $r->implementation_text
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }
}
