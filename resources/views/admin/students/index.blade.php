@extends('layouts.admin')

@section('title', 'Manajemen Siswa')

@section('content')
<div x-data="studentManagement()" class="space-y-6">

    {{-- Title & Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-serif font-bold text-2xl md:text-3xl text-slate-950 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-800" style="font-size: 32px;">group</span>
                Manajemen Siswa
            </h1>
            <p class="text-slate-500 text-sm mt-1">Daftarkan siswa baru via CSV, kelola akun, reset password, dan pantau status login.</p>
        </div>
        <div class="flex items-center gap-3">
            <button
                @click="showImportModal = true"
                class="flex items-center gap-2 px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white rounded-xl text-sm font-semibold shadow-sm transition-all min-h-[44px]"
            >
                <span class="material-symbols-outlined" style="font-size: 20px;">upload_file</span>
                Impor Siswa (CSV)
            </button>
            <a
                href="{{ route('admin.students.template') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold shadow-sm transition-all min-h-[44px]"
            >
                <span class="material-symbols-outlined" style="font-size: 20px;">download</span>
                Unduh Template CSV
            </a>
        </div>
    </div>

    {{-- Session Warning Alert (Specific for Import Failures) --}}
    @if(session('warning'))
        <div class="bg-amber-50 border border-amber-200 text-amber-900 px-5 py-4 rounded-xl shadow-sm text-sm flex items-start gap-3">
            <span class="material-symbols-outlined text-amber-600 flex-shrink-0" style="font-size: 22px;">warning</span>
            <div>
                <p class="font-semibold">Beberapa baris dilewati:</p>
                <p class="text-amber-800 mt-0.5">{{ session('warning') }}</p>
                <p class="text-xs text-amber-700 mt-1">Saran: Pastikan format NIS hanya berisi huruf/angka tanpa spasi, serta kolom "nis" dan "name" terisi lengkap.</p>
            </div>
        </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Total --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center">
                <span class="material-symbols-outlined" style="font-size: 28px;">groups</span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Siswa</p>
                <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $totalStudents }}</p>
            </div>
        </div>

        {{-- Aktif (Ubah Password) --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100/70 text-emerald-950 flex items-center justify-center">
                <span class="material-symbols-outlined text-emerald-800" style="font-size: 28px;">check_circle</span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Siswa Aktif (Password Diubah)</p>
                <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $activeLogins }}</p>
            </div>
        </div>

        {{-- Baru (Belum Ubah Password) --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center">
                <span class="material-symbols-outlined text-amber-600" style="font-size: 28px;">pending</span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Belum Ganti Password</p>
                <p class="text-2xl font-bold text-slate-900 mt-0.5">{{ $inactiveLogins }}</p>
            </div>
        </div>
    </div>

    {{-- Main Panel: Search & Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        
        {{-- Search & Bulk Action Bar --}}
        <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
            <form action="{{ route('admin.students.index') }}" method="GET" class="w-full md:max-w-md relative">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-slate-400" style="font-size: 20px;">search</span>
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari berdasarkan NIS atau Nama siswa..."
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 focus:border-emerald-800 transition-all min-h-[44px]"
                    >
                    @if($search)
                        <a
                            href="{{ route('admin.students.index') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 text-xs font-medium"
                        >
                            ✕ Bersihkan
                        </a>
                    @endif
                </div>
            </form>

            {{-- Bulk Actions --}}
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    x-show="selectedIds.length > 0"
                    @click="showBulkDeleteModal = true"
                    class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 rounded-xl text-sm font-semibold transition-all shadow-sm min-h-[44px]"
                    style="display: none;"
                >
                    <span class="material-symbols-outlined" style="font-size: 20px;">delete_sweep</span>
                    Hapus Terpilih (<span x-text="selectedIds.length"></span>)
                </button>
            </div>
        </div>

        {{-- Student Table --}}
        <form id="bulk-delete-form" action="{{ route('admin.students.bulk-destroy') }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-bold uppercase tracking-wider text-slate-400 bg-slate-50/20">
                            <th class="p-4 w-12 text-center">
                                <input
                                    type="checkbox"
                                    x-model="selectAll"
                                    @change="toggleSelectAll()"
                                    class="w-4.5 h-4.5 rounded border-slate-300 text-emerald-800 focus:ring-emerald-800"
                                >
                            </th>
                            <th class="p-4">NIS</th>
                            <th class="p-4">Nama Lengkap</th>
                            <th class="p-4 text-center">Status Password</th>
                            <th class="p-4 text-right pr-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="p-4 text-center">
                                    <input
                                        type="checkbox"
                                        name="student_ids[]"
                                        value="{{ $student->id }}"
                                        x-model="selectedIds"
                                        @change="checkSelectAll()"
                                        class="w-4.5 h-4.5 rounded border-slate-300 text-emerald-800 focus:ring-emerald-800"
                                    >
                                </td>
                                <td class="p-4 font-mono font-semibold text-slate-900">{{ $student->nis }}</td>
                                <td class="p-4 font-medium text-slate-800">{{ $student->name }}</td>
                                <td class="p-4 text-center">
                                    @if($student->is_password_changed)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Sudah Diubah
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Bawaan (biocinta123)
                                        </span>
                                    @endif
                                </td>
                                <td class="p-4 text-right pr-6">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Reset Password --}}
                                        <button
                                            type="button"
                                            @click="confirmReset({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                            class="w-9 h-9 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-emerald-800 flex items-center justify-center shadow-sm transition-colors"
                                            title="Reset Password ke default"
                                        >
                                            <span class="material-symbols-outlined" style="font-size: 18px;">key</span>
                                        </button>
                                        
                                        {{-- Delete --}}
                                        <button
                                            type="button"
                                            @click="confirmDelete({{ $student->id }}, '{{ addslashes($student->name) }}')"
                                            class="w-9 h-9 rounded-lg border border-red-100 bg-red-50 hover:bg-red-100 text-red-600 flex items-center justify-center shadow-sm transition-colors"
                                            title="Hapus Siswa"
                                        >
                                            <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-slate-400">
                                    <span class="material-symbols-outlined" style="font-size: 48px;">person_off</span>
                                    <p class="mt-2 text-sm">Tidak ada data siswa ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        {{-- Table Footer / Pagination --}}
        @if($students->hasPages())
            <div class="p-5 border-t border-slate-100 bg-slate-50/30">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    {{-- ========================================================
         MODAL 1: IMPOR DATA SISWA (CSV)
         ======================================================== --}}
    <div
        x-show="showImportModal"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div
            @click="showImportModal = false"
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"
        ></div>

        {{-- Modal Content --}}
        <div
            x-show="showImportModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden relative z-10 border border-slate-100"
        >
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-serif font-bold text-lg text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-800">upload_file</span>
                    Impor Siswa Massal
                </h3>
                <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">✕</button>
            </div>

            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 text-xs text-emerald-950 space-y-2">
                    <p class="font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-emerald-800" style="font-size:16px;">info</span>
                        Aturan Impor & Keberlanjutan Data:
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-emerald-800 pl-1">
                        <li>Gunakan file dengan format <strong>CSV</strong> (koma atau titik koma).</li>
                        <li>Format kolom header wajib memiliki: <code class="bg-emerald-100 px-1 py-0.5 rounded font-mono font-bold">nis</code> dan <code class="bg-emerald-100 px-1 py-0.5 rounded font-mono font-bold">name</code>.</li>
                        <li><strong>Keberlanjutan:</strong> Jika NIS sudah ada di sistem, **hanya nama siswa yang akan diperbarui**. Password dan status login siswa saat ini tetap terjaga tanpa ter-reset.</li>
                        <li>Siswa baru yang didaftarkan akan otomatis diberikan password default: <code class="bg-emerald-100 px-1 py-0.5 rounded font-mono font-bold">biocinta123</code>.</li>
                    </ul>
                </div>

                <div class="space-y-1.5">
                    <label for="csv_file" class="text-xs font-bold text-slate-400 uppercase tracking-wider">Pilih Berkas CSV</label>
                    <input
                        type="file"
                        id="csv_file"
                        name="file"
                        accept=".csv,text/csv,text/plain"
                        required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-800 focus:border-emerald-800 transition-all bg-slate-50 min-h-[44px]"
                    >
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button
                        type="button"
                        @click="showImportModal = false"
                        class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 min-h-[44px]"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="px-5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white rounded-xl text-sm font-semibold shadow-sm min-h-[44px]"
                    >
                        Proses Impor
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================
         MODAL 2: KONFIRMASI RESET PASSWORD
         ======================================================== --}}
    <div
        x-show="studentToReset !== null"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="display: none;"
    >
        <div @click="studentToReset = null" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden relative z-10 border border-slate-100">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-800 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined" style="font-size: 28px;">key</span>
                </div>
                <h3 class="font-serif font-bold text-lg text-slate-900">Reset Password Siswa?</h3>
                <p class="text-sm text-slate-500 mt-2">
                    Password untuk siswa <strong class="text-slate-800" x-text="studentToReset?.name"></strong> akan dikembalikan menjadi bawaan sistem: <code class="bg-slate-100 px-1 py-0.5 rounded font-mono font-bold text-emerald-800">biocinta123</code>.
                </p>
                <p class="text-xs text-amber-600 font-semibold mt-2">Siswa akan diwajibkan mengganti password baru pada saat login berikutnya.</p>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="studentToReset = null"
                    class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 min-h-[44px]"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="executeReset()"
                    class="px-5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white rounded-xl text-sm font-semibold min-h-[44px]"
                >
                    Ya, Reset Password
                </button>
            </div>
        </div>
    </div>

    {{-- ========================================================
         MODAL 3: KONFIRMASI HAPUS INDIVIDUAL
         ======================================================== --}}
    <div
        x-show="studentToDelete !== null"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="display: none;"
    >
        <div @click="studentToDelete = null" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden relative z-10 border border-slate-100">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined" style="font-size: 28px;">warning</span>
                </div>
                <h3 class="font-serif font-bold text-lg text-slate-900">Hapus Data Siswa?</h3>
                <p class="text-sm text-slate-500 mt-2">
                    Apakah Anda yakin ingin menghapus data siswa <strong class="text-slate-800" x-text="studentToDelete?.name"></strong> secara permanen?
                </p>
                <p class="text-xs text-red-600 font-medium mt-3 bg-red-50 border border-red-100 rounded-lg p-2.5">
                    ⚠️ Tindakan ini akan menghapus semua riwayat aktivitas, nilai kuis, poin puzzle, projek kelompok, dan tadabbur jurnal siswa tersebut secara permanen.
                </p>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="studentToDelete = null"
                    class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 min-h-[44px]"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="executeDelete()"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold min-h-[44px]"
                >
                    Ya, Hapus Permanen
                </button>
            </div>
        </div>
    </div>

    {{-- ========================================================
         MODAL 4: KONFIRMASI HAPUS MASSAL (BULK DELETE)
         ======================================================== --}}
    <div
        x-show="showBulkDeleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="display: none;"
    >
        <div @click="showBulkDeleteModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden relative z-10 border border-slate-100">
            <div class="p-6 text-center">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined" style="font-size: 28px;">delete_sweep</span>
                </div>
                <h3 class="font-serif font-bold text-lg text-slate-900">Hapus Massal Siswa Terpilih?</h3>
                <p class="text-sm text-slate-500 mt-2">
                    Anda akan menghapus secara permanen <strong class="text-slate-800"><span x-text="selectedIds.length"></span> siswa</strong> yang telah dipilih.
                </p>
                <p class="text-xs text-red-600 font-medium mt-3 bg-red-50 border border-red-100 rounded-lg p-2.5">
                    ⚠️ Tindakan ini akan menghapus semua riwayat aktivitas, nilai kuis, poin puzzle, projek kelompok, dan tadabbur jurnal untuk seluruh siswa terpilih secara permanen.
                </p>
            </div>
            <div class="px-6 py-4 bg-slate-50 flex items-center justify-end gap-3">
                <button
                    type="button"
                    @click="showBulkDeleteModal = false"
                    class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 min-h-[44px]"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="executeBulkDelete()"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold min-h-[44px]"
                >
                    Ya, Hapus Semua Terpilih
                </button>
            </div>
        </div>
    </div>

    {{-- Helper Hidden Forms for submitting POST/DELETE actions --}}
    <form id="delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="reset-form" method="POST" class="hidden">
        @csrf
    </form>

</div>
@endsection

@push('scripts')
<script>
    function studentManagement() {
        return {
            selectedIds: [],
            selectAll: false,
            showImportModal: false,
            showBulkDeleteModal: false,
            studentToDelete: null,
            studentToReset: null,

            toggleSelectAll() {
                if (this.selectAll) {
                    // Ambil seluruh value checkbox di dalam form bulk-delete
                    const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
                    this.selectedIds = Array.from(checkboxes).map(cb => parseInt(cb.value));
                } else {
                    this.selectedIds = [];
                }
            },

            checkSelectAll() {
                const checkboxes = document.querySelectorAll('input[name="student_ids[]"]');
                this.selectAll = checkboxes.length > 0 && this.selectedIds.length === checkboxes.length;
            },

            confirmReset(id, name) {
                this.studentToReset = { id, name };
            },

            executeReset() {
                if (!this.studentToReset) return;
                const form = document.getElementById('reset-form');
                form.action = `/admin/students/${this.studentToReset.id}/reset-password`;
                form.submit();
            },

            confirmDelete(id, name) {
                this.studentToDelete = { id, name };
            },

            executeDelete() {
                if (!this.studentToDelete) return;
                const form = document.getElementById('delete-form');
                form.action = `/admin/students/${this.studentToDelete.id}`;
                form.submit();
            },

            executeBulkDelete() {
                // Submit form bulk-delete-form
                document.getElementById('bulk-delete-form').submit();
            }
        };
    }
</script>
@endpush
