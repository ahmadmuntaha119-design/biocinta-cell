<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

/**
 * Task 8.2: AdminQuizController — Panel Kontrol Kuis & Aktivasi Token
 * Mengelola pembuatan, modifikasi, penghapusan kuis, serta aktivasi token ujian.
 */
class AdminQuizController extends Controller
{
    /**
     * Tampilkan daftar kuis.
     */
    public function index()
    {
        $quizzes = Quiz::withCount('questions')
            ->latest()
            ->paginate(10);

        return view('admin.quizzes.index', compact('quizzes'));
    }

    /**
     * Tampilkan formulir pembuatan kuis baru.
     */
    public function create()
    {
        return view('admin.quizzes.create');
    }

    /**
     * Simpan kuis baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'min:3', 'max:200'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'timer_mode'       => ['required', 'string', 'in:quiz,question'],
        ], [
            'title.required'            => 'Judul kuis wajib diisi.',
            'title.min'                 => 'Judul kuis minimal 3 karakter.',
            'duration_minutes.required' => 'Durasi pengerjaan wajib diisi.',
            'duration_minutes.integer'  => 'Durasi harus berupa angka.',
            'duration_minutes.min'      => 'Durasi minimal 1 menit.',
            'timer_mode.required'       => 'Mode timer wajib dipilih.',
            'timer_mode.in'             => 'Mode timer tidak valid.',
        ]);

        Quiz::create([
            'title'            => $validated['title'],
            'duration_minutes' => $validated['duration_minutes'],
            'timer_mode'       => $validated['timer_mode'],
            'is_active'        => false,
            'token'            => null,
        ]);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil dibuat. Silakan tambahkan soal kuis.');
    }

    /**
     * Tampilkan detail kuis beserta daftar soal pilihan ganda di dalamnya.
     */
    public function show(Quiz $quiz)
    {
        $quiz->load(['questions' => function ($q) {
            $q->orderBy('sort_order')->orderBy('id');
        }]);

        return view('admin.quizzes.show', compact('quiz'));
    }

    /**
     * Tampilkan formulir edit kuis.
     */
    public function edit(Quiz $quiz)
    {
        return view('admin.quizzes.edit', compact('quiz'));
    }

    /**
     * Perbarui data kuis di database.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'min:3', 'max:200'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'timer_mode'       => ['required', 'string', 'in:quiz,question'],
        ], [
            'title.required'            => 'Judul kuis wajib diisi.',
            'title.min'                 => 'Judul kuis minimal 3 karakter.',
            'duration_minutes.required' => 'Durasi pengerjaan wajib diisi.',
            'duration_minutes.integer'  => 'Durasi harus berupa angka.',
            'duration_minutes.min'      => 'Durasi minimal 1 menit.',
            'timer_mode.required'       => 'Mode timer wajib dipilih.',
            'timer_mode.in'             => 'Mode timer tidak valid.',
        ]);

        $quiz->update([
            'title'            => $validated['title'],
            'duration_minutes' => $validated['duration_minutes'],
            'timer_mode'       => $validated['timer_mode'],
        ]);

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Detail kuis berhasil diperbarui.');
    }

    /**
     * Hapus kuis dari database beserta soal-soalnya (cascade delete).
     */
    public function destroy(Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Kuis berhasil dihapus.');
    }

    /**
     * Export nilai kuis ke file Excel (.xlsx).
     */
    public function exportResults(Quiz $quiz)
    {
        // Muat semua data attempt yang sudah disubmit beserta relasi user & questions
        $quiz->load(['questions', 'attempts' => function ($q) {
            $q->whereNotNull('submitted_at')
              ->with('user')
              ->orderBy('score', 'desc');
        }]);

        $totalSoal = $quiz->questions->count();

        // ── Spreadsheet Setup ─────────────────────────────────────────
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Nilai Kuis');

        // ── Judul Laporan ─────────────────────────────────────────────
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'LAPORAN NILAI KUIS — ' . strtoupper($quiz->title));
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF1E6B3C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        // Sub-judul info kuis
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2',
            'Durasi: ' . $quiz->duration_minutes . ' menit  |  '
            . 'Jumlah Soal: ' . $totalSoal . ' butir  |  '
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
        $headers = ['No', 'Nama Siswa', 'NIS', 'Nilai (0–100)', 'Jumlah Benar', 'Total Soal', 'Waktu Pengerjaan', 'Tanggal Ujian'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

        foreach ($headers as $i => $header) {
            $cell = $cols[$i] . '4';
            $sheet->setCellValue($cell, $header);
        }

        $sheet->getStyle('A4:H4')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF2D8A56']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF1E6B3C']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(22);

        // ── Data Baris ────────────────────────────────────────────────
        $row = 5;
        foreach ($quiz->attempts as $index => $attempt) {
            // Hitung jumlah benar
            $answers  = $attempt->answers ?? [];
            $benar    = 0;
            foreach ($quiz->questions as $question) {
                if (($answers[$question->id] ?? null) === $question->correct_option) {
                    $benar++;
                }
            }

            // Waktu pengerjaan
            $durasi = 'N/A';
            if ($attempt->started_at && $attempt->submitted_at) {
                $detik  = $attempt->started_at->diffInSeconds($attempt->submitted_at);
                $menit  = intdiv($detik, 60);
                $sisa   = $detik % 60;
                $durasi = $menit . ' mnt ' . $sisa . ' dtk';
            }

            $isEven = ($index % 2 === 0);
            $bgColor = $isEven ? 'FFFFFFFF' : 'FFF4FBF7';

            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $attempt->user->name ?? '-');
            $sheet->setCellValue('C' . $row, $attempt->user->nis ?? '-');
            $sheet->setCellValue('D' . $row, (float) $attempt->score);
            $sheet->setCellValue('E' . $row, $benar);
            $sheet->setCellValue('F' . $row, $totalSoal);
            $sheet->setCellValue('G' . $row, $durasi);
            $sheet->setCellValue('H' . $row, $attempt->submitted_at?->format('d/m/Y H:i') ?? '-');

            // Style per baris
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => $bgColor]],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1E8DA']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'font'      => ['size' => 10],
            ]);

            // Center beberapa kolom
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        // Baris kosong jika tidak ada data
        if ($quiz->attempts->isEmpty()) {
            $sheet->mergeCells('A5:H5');
            $sheet->setCellValue('A5', 'Belum ada siswa yang mengerjakan kuis ini.');
            $sheet->getStyle('A5')->applyFromArray([
                'font'      => ['italic' => true, 'color' => ['argb' => 'FF999999']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $row = 6;
        }

        // ── Ringkasan / Footer ────────────────────────────────────────
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->setCellValue('A' . $row, 'RINGKASAN');
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
            'font'  => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'  => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF1E6B3C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $row++;

        $avgScore    = $quiz->attempts->avg('score') ?? 0;
        $maxScore    = $quiz->attempts->max('score') ?? 0;
        $minScore    = $quiz->attempts->min('score') ?? 0;
        $peserta     = $quiz->attempts->count();

        $summary = [
            ['Jumlah Peserta', $peserta],
            ['Nilai Rata-rata', number_format($avgScore, 1)],
            ['Nilai Tertinggi', number_format($maxScore, 1)],
            ['Nilai Terendah', number_format($minScore, 1)],
        ];

        foreach ($summary as $item) {
            $sheet->setCellValue('A' . $row, $item[0]);
            $sheet->setCellValue('B' . $row, $item[1]);
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFF0F7F4']],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFD1E8DA']]],
                'font'      => ['size' => 10],
            ]);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $row++;
        }

        // ── Auto-width Kolom ──────────────────────────────────────────
        $widths = ['A' => 6, 'B' => 32, 'C' => 16, 'D' => 16, 'E' => 16, 'F' => 14, 'G' => 22, 'H' => 22];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // ── Stream Response ───────────────────────────────────────────
        $filename = 'Nilai-Kuis-' . str_replace(' ', '-', $quiz->title) . '-' . now()->format('Ymd') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Toggle status aktif kuis dan buat token 6-digit baru jika diaktifkan.
     */
    public function toggle(Quiz $quiz)
    {
        if ($quiz->is_active) {
            $quiz->deactivate();
            return redirect()->back()
                ->with('success', 'Kuis "' . $quiz->title . '" berhasil dinonaktifkan.');
        } else {
            $quiz->activate();
            return redirect()->back()
                ->with('success', 'Kuis "' . $quiz->title . '" berhasil diaktifkan. Token aktif: ' . $quiz->token);
        }
    }
}
