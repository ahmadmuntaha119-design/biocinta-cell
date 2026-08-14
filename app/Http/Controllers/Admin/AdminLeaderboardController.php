<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Task 7.3: AdminLeaderboardController — Visibilitas Peringkat Penuh 100% Sisi Admin
 * Mengelola antarmuka penilaian peringkat untuk Guru, dengan fitur pencarian dan ekspor data Excel.
 */
class AdminLeaderboardController extends Controller
{
    /**
     * Tampilkan daftar peringkat 100% siswa dengan pagination dan search.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = User::query()
            ->where('role', 'student')
            ->select('users.id', 'users.nis', 'users.name')
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) as quiz_total')
            ->selectRaw('COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as puzzle_total')
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) + COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as grand_total');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $students = $query->orderByDesc('grand_total')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.leaderboard.index', compact('students', 'search'));
    }

    /**
     * Ekspor seluruh data peringkat siswa ke berkas Excel (.xlsx) yang rapi dan terformat.
     */
    public function export()
    {
        $students = User::query()
            ->where('role', 'student')
            ->select('users.id', 'users.nis', 'users.name')
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) as quiz_total')
            ->selectRaw('COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as puzzle_total')
            ->selectRaw('COALESCE((SELECT SUM(score) FROM quiz_attempts WHERE quiz_attempts.user_id = users.id AND submitted_at IS NOT NULL), 0) + COALESCE((SELECT SUM(earned_points) FROM puzzle_scores WHERE puzzle_scores.user_id = users.id), 0) as grand_total')
            ->orderByDesc('grand_total')
            ->orderBy('name')
            ->get();

        // ── Spreadsheet Setup ─────────────────────────────────────────
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Papan Peringkat');

        // ── Judul Laporan ─────────────────────────────────────────────
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN PAPAN PERINGKAT — BIOCINTA CELL');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF1E6B3C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Sub-judul info ekspor
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2',
            'Total Siswa: ' . $students->count() . '  |  '
            . 'Diekspor: ' . now()->format('d/m/Y H:i')
        );
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF555555']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFF0F7F4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(18);

        // Baris kosong pemisah
        $sheet->getRowDimension(3)->setRowHeight(6);

        // ── Header Kolom ──────────────────────────────────────────────
        $headers = ['Peringkat', 'NIS', 'Nama Siswa', 'Total Nilai Kuis', 'Total Poin Puzzle', 'Nilai Akumulatif'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '4', $header);
        }

        $sheet->getStyle('A4:F4')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF2D8A56']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1E6B3C']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(22);

        // ── Data Baris ────────────────────────────────────────────────
        $row = 5;
        foreach ($students as $index => $student) {
            $isEven  = ($index % 2 === 0);
            $bgColor = $isEven ? 'FFFFFFFF' : 'FFF4FBF7';

            // Warna khusus untuk peringkat 1, 2, 3
            if ($index === 0) $bgColor = 'FFFFF8E1'; // emas muda
            if ($index === 1) $bgColor = 'FFF5F5F5'; // perak muda
            if ($index === 2) $bgColor = 'FFFFF3E0'; // perunggu muda

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $student->nis ?? '-');
            $sheet->setCellValue('C' . $row, $student->name);
            $sheet->setCellValue('D' . $row, number_format((float)$student->quiz_total, 2, '.', ''));
            $sheet->setCellValue('E' . $row, (int)$student->puzzle_total);
            $sheet->setCellValue('F' . $row, number_format((float)$student->grand_total, 2, '.', ''));

            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => $bgColor]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1E8DA']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'font'      => ['size' => 10],
            ]);

            foreach (['A', 'B', 'D', 'E', 'F'] as $c) {
                $sheet->getStyle($c . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }

            if ($index < 3) {
                $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
            }

            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        // Baris kosong jika tidak ada data
        if ($students->isEmpty()) {
            $sheet->mergeCells('A5:F5');
            $sheet->setCellValue('A5', 'Belum ada data siswa.');
            $sheet->getStyle('A5')->getFont()->setItalic(true);
            $row = 6;
        }

        // ── Ringkasan / Footer ────────────────────────────────────────
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->setCellValue('A' . $row, 'RINGKASAN');
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF1E6B3C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        $summary = [
            ['Jumlah Siswa',               $students->count()],
            ['Rata-rata Nilai Kuis',        number_format($students->avg('quiz_total') ?? 0, 1)],
            ['Rata-rata Poin Puzzle',       number_format($students->avg('puzzle_total') ?? 0, 1)],
            ['Nilai Akumulatif Tertinggi',  number_format($students->max('grand_total') ?? 0, 1)],
        ];

        foreach ($summary as $item) {
            $sheet->setCellValue('A' . $row, $item[0]);
            $sheet->setCellValue('B' . $row, $item[1]);
            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFF0F7F4']],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1E8DA']]],
                'font'      => ['size' => 10],
            ]);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
        }

        // ── Auto-width Kolom ──────────────────────────────────────────
        $widths = ['A' => 12, 'B' => 16, 'C' => 34, 'D' => 20, 'E' => 20, 'F' => 24];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // ── Stream Response ───────────────────────────────────────────
        $filename = 'Papan-Peringkat-BioCinta-' . now()->format('Ymd') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
