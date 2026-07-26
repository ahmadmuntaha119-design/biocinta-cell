# TECHNICAL DESIGN SPECIFICATION
## SYSTEM NAME: BioCinta Cell (v1.3.0)
## CORE FRAMEWORK: Laravel 13.x, PHP 8.3+, Alpine.js v3.x, TailwindCSS, MySQL 8.x
## INFRASTRUCTURE TARGET: VPS 2 vCPU, 2 GB RAM (IDCloudHost)

---

## 1. UI/UX DESIGN SYSTEM TOKENS & MOBILE-RESPONSIVE STRATEGY
Mengacu pada persetujuan visual (`image_94d75f.jpg`), semua halaman sisi pengguna wajib menerapkan strategi **Mobile-First Responsive Design** menggunakan utility classes Tailwind CSS:

- **Breakpoints Strategy:**
  - Desain wajib rapi di layar HP kecil (Lebar mulai `360px`) hingga desktop.
  - Gunakan class `w-full` sebagai default, dan naikkan ukuran ke `md:w-auto` atau `md:grid-cols-X` hanya pada layar tablet/desktop.
- **Touch Targets Rule:**
  - Semua tombol, link navigasi, dan elemen interaktif (termasuk tombol organel puzzle) wajib memiliki tinggi minimal **44px** (`py-3` atau `h-11`) agar nyaman ditekan menggunakan jempol di layar sentuh HP siswa.
- **Typography Tokens (Keterbacaan Tinggi):**
  - Font Utama (Body & UI): `font-sans` (Dipetakan ke *Plus Jakarta Sans* atau *Inter*).
  - Font Heading (Judul/Tadabbur): `font-serif` (Dipetakan ke *Playfair Display*).
- **Color Palette (Tema Hijau-Emas Madrasah):**
  - Primary (Hijau Utama): `bg-emerald-900` (`#064e3b`) & `text-emerald-900`.
  - Secondary/Accent (Emas Kurikulum CINTA): `bg-amber-500` (`#f59e0b`) & `text-amber-500`.
  - Background Base: `bg-slate-50` (`#f8fafc`) -> Mencegah mata siswa cepat lelah saat membaca materi di HP.
  - Text Body: `text-slate-700` (`#334155`).

---

## 2. ARCHITECTURE & ROUTING CONTRACTS

### 2.1 Middleware Pipeline Execution
Sistem wajib menjalankan urutan pengecekan akses URL dengan struktur pipa pengaman berikut:
1. `Guest` -> Hanya bisa mengakses route `/login`.
2. `Auth` -> Bisa mengakses `/change-password`. Jika mencoba ke halaman lain saat `is_password_changed == false`, tendang kembali ke `/change-password`.
3. `Auth + CheckPasswordChanged` -> Bisa mengakses seluruh fitur Siswa.
4. `Auth + role:admin` -> Akses penuh ke Dashboard Admin `/admin/*`.

### 2.2 Web Routes Blueprint

```php
// Route Publik
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route Proteksi Tahap 1: Wajib Login (Ubah Password Default)
Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordController::class, 'edit'])->name('password.change');
    Route::post('/change-password', [PasswordController::class, 'update'])->name('password.update');
});

// Route Proteksi Tahap 2: Wajib Login & Sudah Ganti Password
Route::middleware(['auth', 'verified.password'])->group(function () {
    
    // MODULE 2: CENTRAL HOMEPAGE DASHBOARD (6 Core Cards - Responsive Hub)
    Route::get('/', [PageController::class, 'home'])->name('home'); 

    // MODULE 3: INTERACTIVE MATERIAL (Sel & Mikroskop)
    Route::get('/materials/microscope', [MaterialController::class, 'microscope'])->name('materials.microscope');
    Route::get('/materials/{slug}', [MaterialController::class, 'show'])->name('materials.show');
    Route::post('/materials/{id}/feedback', [MaterialController::class, 'storeFeedback'])->name('materials.feedback');

    // MODULE 4: VIRTUAL MICROSCOPE LAB
    Route::get('/virtual-lab', [VirtualLabController::class, 'index'])->name('labs.index');

    // MODULE 5: COLLABORATIVE PROJECT SPACE
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');

    // MODULE 6: ISLAMIC REFLECTION (Kurikulum CINTA)
    Route::get('/reflection', [ReflectionController::class, 'create'])->name('reflection.create');
    Route::post('/reflection', [ReflectionController::class, 'store'])->name('reflection.store');

    // MODULE 7 & 8: GAME PUZZLE & LEADERBOARD (Siswa: Terbatas Top 10)
    Route::get('/puzzle', [PuzzleController::class, 'index'])->name('puzzle.index');
    Route::post('/api/puzzle/submit', [PuzzleController::class, 'submitScore']);
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');
});

// AREA ADMIN (Protected by role check & Full Visibility)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('students', AdminStudentController::class);
    Route::post('/students/import', [AdminStudentController::class, 'import'])->name('students.import');
    Route::resource('materials', AdminMaterialController::class);
    
    // Admin View: Lihat 100% peringkat tanpa batasan & Ekspor data
    Route::get('/leaderboard/full', [AdminDashboardController::class, 'fullLeaderboard'])->name('leaderboard.full');
    Route::get('/export/grades', [AdminExportController::class, 'exportXlsx'])->name('export.grades');
});
```

---

## 3. DATABASE SCHEMA & INDEXING CONSTRAINTS

```sql
-- Struktur tabel yang dioptimalkan murni menggunakan mesin InnoDB MySQL

users (
  id BIGINT PK AI,
  nis VARCHAR(20) UNIQUE,
  name VARCHAR(100),
  role ENUM('admin', 'student') DEFAULT 'student',
  password VARCHAR(255),
  is_password_changed BOOLEAN DEFAULT false,
  timestamps
);

materials (
  id BIGINT PK AI,
  type ENUM('animal', 'plant', 'microscope'), -- Ditambahkan 'microscope' untuk sub-bab jenis alat
  title VARCHAR(150),
  slug VARCHAR(150) UNIQUE,
  description TEXT,
  youtube_id VARCHAR(20) NULL,
  history_founder TEXT NULL,
  quran_verse TEXT NULL,
  timestamps
);

puzzle_banks (
  id BIGINT PK AI,
  publish_date DATE UNIQUE, -- Memastikan sistem otomasi tanggal harian bekerja tanpa crash
  target_text_1 TEXT, correct_organelle_1 VARCHAR(100),
  target_text_2 TEXT, correct_organelle_2 VARCHAR(100),
  target_text_3 TEXT, correct_organelle_3 VARCHAR(100),
  target_text_4 TEXT, correct_organelle_4 VARCHAR(100),
  timestamps,
  INDEX (publish_date)
);

puzzle_scores (
  id BIGINT PK AI,
  user_id BIGINT FK -> users.id (CASCADE),
  score INTEGER,             -- Nilai riil game kelipatan 25 (0, 25, 50, 75, 100)
  earned_points INTEGER,     -- Poin harian leaderboard (10 jika pengerjaan ke-1, 0 jika diulang)
  completed_at TIMESTAMP,
  timestamps,
  COMPOSITE INDEX user_daily_constraint (user_id, completed_at)
);
```

---

## 4. HIGH-PERFORMANCE & FULLY RESPONSIVE CLIENT-SIDE UI

### 4.1 Game Puzzle Organel (HP Responsive & Click-to-Match Engine)
Logika UI di bawah dirancang menggunakan **Tailwind CSS Mobile-First Layout** (`grid-cols-12`, `col-span-12 md:col-span-X`) dan **Alpine.js** agar berjalan super ringan di HP tipe apapun tanpa membebani server RAM.

```html
<div x-data="puzzleGame()" class="grid grid-cols-12 gap-4 md:gap-6 bg-slate-50 font-sans p-2 md:p-4">
    
    <div class="col-span-12 md:col-span-7 space-y-3 md:space-y-4">
        <div class="flex justify-between items-center bg-white p-3 rounded-xl shadow-sm border border-slate-100">
            <span class="font-bold text-slate-800 text-xs md:text-sm flex items-center gap-2">🎯 Target Fungsi Organel</span>
            <div class="bg-amber-50 text-amber-600 font-bold px-2 py-1 md:px-3 md:py-1 rounded-lg text-xs">
                Skor: <span x-text="calculateCorrectCount()">0</span> / 4 (<span x-text="calculateScore()">0</span> PTS)
            </div>
        </div>

        <template x-for="(target, index) in targets" :key="target.id">
            <div class="p-4 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <p x-text="target.text" class="text-slate-700 text-xs md:text-sm leading-relaxed max-w-md"></p>
                
                <div @click="selectTarget(index)" 
                     :class="[
                        target.matchedWith ? 'bg-emerald-50 border-emerald-500 text-emerald-900 font-semibold' : 'bg-slate-50 border-dashed border-slate-300 text-slate-400',
                        selectedTargetIndex === index ? 'ring-2 ring-amber-500 bg-amber-50' : ''
                     ]"
                     class="w-full sm:w-48 p-3 rounded-lg text-center cursor-pointer transition-all border min-h-[44px] flex items-center justify-center text-xs">
                    <span x-text="target.matchedWith ? '✅ ' + target.matchedWith.name : 'Taruh jawaban di sini'"></span>
                </div>
            </div>
        </template>
    </div>

    <div class="col-span-12 md:col-span-5 bg-white p-4 md:p-6 rounded-xl shadow-sm h-fit border border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 md:mb-4 font-serif">📦 Pilihan Organel</h3>
        <div class="space-y-2 md:space-y-3">
            <template x-for="organelle in organelles" :key="organelle.id">
                <button @click="matchOrganelle(organelle)"
                        :disabled="organelle.isUsed"
                        :class="organelle.isUsed ? 'bg-slate-100 text-slate-300 cursor-not-allowed border-transparent' : 'bg-emerald-900 text-white hover:bg-emerald-800'"
                        class="w-full min-h-[44px] p-3 rounded-lg font-semibold text-xs md:text-sm transition-all text-left px-4 flex justify-between items-center shadow-sm">
                    <span x-text="organelle.name"></span>
                    <span x-show="!organelle.isUsed" class="text-amber-500 text-xs">➡️</span>
                </button>
            </template>
        </div>
        
        <div class="mt-6 pt-4 border-t border-slate-100 space-y-2">
            <button @click="submitPuzzleScore()" x-show="isGameFinished()" class="w-full bg-amber-500 text-white min-h-[44px] p-3 rounded-lg font-bold shadow-md hover:bg-amber-600 transition-all text-sm">
                📤 Kirim Hasil Tantangan Hari Ini
            </button>
            <button @click="resetPuzzle()" class="w-full bg-slate-100 text-slate-600 min-h-[44px] p-3 rounded-lg font-semibold text-sm hover:bg-slate-200 transition-all flex items-center justify-center gap-2">
                🔄 Reset Puzzle
            </button>
        </div>
    </div>
</div>

<script>
function puzzleGame() {
    return {
        selectedTargetIndex: null,
        targets: [
            { id: 1, text: 'Pusat respirasi seluler yang menghasilkan energi (ATP) bagi seluruh aktivitas sel.', correctName: 'Mitokondria', matchedWith: null },
            { id: 2, text: 'Mengubah energi cahaya matahari menjadi energi kimia makanan melalui proses fotosintesis.', correctName: 'Kloroplas', matchedWith: null },
            { id: 3, text: 'Pusat kendali seluruh aktivitas sel sekaligus tempat penyimpanan materi genetik (DNA).', correctName: 'Nukleus (Inti Sel)', matchedWith: null },
            { id: 4, text: 'Organel kecil berbutir-butir yang berfungsi sebagai tempat berlangsungnya sintesis protein.', correctName: 'Ribosom', matchedWith: null }
        ],
        organelles: [
            { id: 101, name: 'Mitokondria', isUsed: false },
            { id: 102, name: 'Kloroplas', isUsed: false },
            { id: 103, name: 'Nukleus (Inti Sel)', isUsed: false },
            { id: 104, name: 'Ribosom', isUsed: false }
        ],
        selectTarget(index) { this.selectedTargetIndex = index; },
        matchOrganelle(organelle) {
            if (this.selectedTargetIndex === null) { alert('Pilih salah satu kotak target jawaban di sebelah kiri terlebih dahulu!'); return; }
            let target = this.targets[this.selectedTargetIndex];
            if (target.matchedWith) { target.matchedWith.isUsed = false; }
            target.matchedWith = organelle;
            organelle.isUsed = true;
            this.selectedTargetIndex = null;
        },
        calculateCorrectCount() {
            return this.targets.filter(t => t.matchedWith && t.matchedWith.name === t.correctName).length;
        },
        calculateScore() {
            return this.calculateCorrectCount() * 25; // Sesuai Kontrak: 4 Soal x 25 = 100 Poin
        },
        isGameFinished() { return this.targets.every(t => t.matchedWith !== null); },
        submitPuzzleScore() {
            let finalScore = this.calculateScore();
            fetch('/api/puzzle/submit', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ score: finalScore })
            }).then(res => res.json()).then(data => {
                alert(data.message);
                window.location.href = '/';
            });
        },
        resetPuzzle() {
            this.selectedTargetIndex = null;
            this.targets.forEach(t => t.matchedWith = null);
            this.organelles.forEach(o => o.isUsed = false);
        }
    }
}
</script>
```

---

## 5. BACKEND OPTIMIZATION FOR MOBILE NETWORKS

1. **Responsive Image Compactor Service:**
   - Dikarenakan kuota internet seluler siswa bervariasi, semua modul gambar mikroskop/materi di dashboard guru wajib dipaksa melewati kompresi native GD/Imagick PHP untuk diubah ke tipe berkas `.webp` dengan kualitas maksimal 75%. Langkah ini memotong ukuran file gambar dari yang awalnya berukuran megabyte (MB) menjadi di bawah 150 Kilobyte (KB) agar *loading* di HP siswa super cepat.
2. **Leaderboard Aggregation Cache Provider:**
   - Query pencarian ranking global dibungkus menggunakan Cache Driver File bawaan Laravel selama 10 menit (`600` detik) dengan key `global_leaderboard_data`. Server tidak diizinkan memproses ulang rumus matematika nilai setiap kali ada siswa yang memuat halaman papan peringkat via HP.