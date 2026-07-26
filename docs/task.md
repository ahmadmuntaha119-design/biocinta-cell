# FINAL DEVELOPMENT TASK MANIFESTO - BIOCINTA CELL
## VERSION: 1.3.0
## TARGET FRAMEWORK: Laravel 13.x, Alpine.js v3.x, TailwindCSS, MySQL 8.x

Manifes tugas ini mencakup seluruh siklus hidup pengembangan sistem BioCinta Cell secara utuh. Ikuti urutan fase demi keamanan arsitektur kode. Centang `[x]` untuk tugas yang berhasil lolos uji coba.

---

## FASE 1: FONDASI ARSITEKTUR DATA (BACKEND SCHEMAS)
*Fokus: Membangun struktur tabel database, relasi, dan indeks penunjang sistem kuis.*

- [ ] **Task 1.1: Skema Tabel Pengguna & Hak Akses**
  - Buat file migrasi tabel `users` dengan kolom: `nis` (VARCHAR, Unique), `name` (VARCHAR), `role` (ENUM: 'admin', 'student'), `password` (VARCHAR), dan `is_password_changed` (BOOLEAN, default: false).
- [ ] **Task 1.2: Skema Tabel Konten Akademis**
  - Buat migrasi tabel `materials` dengan kolom: `type` (ENUM: 'animal', 'plant', 'microscope'), `title`, `slug` (Unique), `description` (TEXT), `youtube_id` (VARCHAR, Nullable), `history_founder` (TEXT), dan `quran_verse` (TEXT).
- [ ] **Task 1.3: Skema Tabel Gamifikasi Puzzle Harian**
  - Buat migrasi tabel `puzzle_banks` dengan kolom `publish_date` (DATE, Unique) untuk menyimpan tepat 4 target teks fungsi beserta 4 jawaban nama organel benar per hari.
  - Buat migrasi tabel `puzzle_scores` dengan kolom `user_id` (FK), `score` (INTEGER), `earned_points` (INTEGER), dan `completed_at` (TIMESTAMP). 
  - Pasang indeks komposit `user_daily_constraint(user_id, completed_at)` untuk efisiensi kueri harian.
- [ ] **Task 1.4: Skema Tabel Kuis Ber-Token & Evaluasi**
  - Buat migrasi tabel `quizzes` (`title`, `token`, `is_active`, `duration_minutes`).
  - Buat migrasi tabel `quiz_questions` (`quiz_id`, `question_text`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`).
  - Buat migrasi tabel `quiz_attempts` (`user_id`, `quiz_id`, `score`, `started_at`, `submitted_at`).
  - Buat migrasi tabel `reflections` (`user_id`, `connection_text`, `wisdom_text`, `implementation_text`) dan tabel `collaborative_projects` (`user_id`, `title`, `description`, `video_url`, `grade`, `teacher_feedback`).

---

## FASE 2: GERBANG AUTENTIKASI & KEAMANAN WAJIB (SECURITY LIFECYCLE)
*Fokus: Mengunci akun siswa dari penyalahgunaan hak akses nilai.*

- [ ] **Task 2.1: Implementasi Session Controller**
  - Bangun logika fungsi login menggunakan kombinasi `nis` dan `password` serta fungsi logout aman.
- [ ] **Task 2.2: Pembuatan Middleware Proteksi Kata Sandi**
  - Buat middleware kustom bernama `CheckPasswordChanged`.
  - Tulis logika interseptor: Jika pengguna sudah terautentikasi namun nilai kolom `is_password_changed == false`, blokir semua akses navigasi halaman dalam dan arahkan paksa ke route `/change-password`.
  - Daftarkan middleware ke dalam file konfigurasi HTTP Kernel (`verified.password`).
- [ ] **Task 2.3: Logika Update Password Pertama Kali**
  - Buat halaman UI ubah password bawaan sekolah yang bersih.
  - Tulis logika pembaruan kata sandi di Controller: Enkripsi inputan menggunakan `Hash::make()` dan ubah nilai flag `is_password_changed` menjadi `true` di database.

---

## FASE 3: LAYOUT GLOBAL MOBILE-FIRST & NAVIGATION HUB (UI BASE)
*Fokus: Memastikan seluruh tampilan antarmuka ramah jempol dan rapi saat diakses lewat HP.*

- [ ] **Task 3.1: Master Layout Responsif HP**
  - Buat file base Blade template yang menyertakan meta-tag viewport seluler.
  - Setup konfigurasi Tailwind CSS dengan font-family utama *Plus Jakarta Sans* / *Inter* dan font heading *Playfair Display*.
  - Pasang global CSS token warna: Hijau Utama (`bg-emerald-900`) dan Emas (`bg-amber-500`).
- [ ] **Task 3.2: Grid Layout 6 Kartu Simetris (Beranda Siswa)**
  - Bangun halaman Dashboard Utama dengan struktur Tailwind Mobile-First: gunakan class `grid-cols-1` untuk layar HP kecil dan otomatis naik ke `md:grid-cols-3` pada desktop.
  - Susun 6 Core Cards navigasi secara presisi: Materi Sel, Alat Mikroskop, Lab Maya, Game Puzzle, Projek Kelompok, dan Ruang Tadabbur.
  - Pastikan seluruh tombol navigasi memiliki tinggi area sentuh minimal **44px** (`min-h-[44px]`) demi kenyamanan akses layar sentuh HP siswa.

---

## FASE 4: MODUL LITERASI AKADEMIS & JURNAL SPIRITUAL (CONTENT LAYER)
*Fokus: Mengembangkan sistem materi interaktif sel, materi mikroskop, dan form refleksi.*

- [ ] **Task 4.1: Antarmuka Anatomi Sel 2D & Detail Sub-Bab**
  - Buat halaman materi sel hewan & tumbuhan dengan diagram gambar responsif yang titik koordinat organelnya dapat diklik secara independen.
  - Hubungkan klik organel tersebut ke halaman detail yang menyajikan data Fungsi Teknis, Video Pendek, Sejarah Penemu, dan Tadabbur Ayat Al-Quran terkait.
- [ ] **Task 4.2: Halaman Teori "Alat Mikroskop & Jenis-jenisnya"**
  - Bangun satu halaman materi khusus untuk tipe konten `microscope`.
  - Sederhanakan penyajian teks perbandingan di layar HP menggunakan komponen tab / akordion Tailwind yang interaktif untuk membahas perbedaan Mikroskop Cahaya vs Elektron, serta Monokuler vs Binokuler.
- [ ] **Task 4.3: Formulir Jurnal Refleksi Islami & Pengumpulan Projek**
  - Bangun halaman form esai untuk Ruang Tadabbur dengan 3 text-area wajib: Connection, Wisdom, dan Implementation.
  - Buat form pengumpulan link tugas projek kelompok dengan validasi regex URL yang ketat untuk tautan YouTube / Google Drive.

---

## FASE 5: SIMULASI INTERAKTIF & ENGINE PUZZLE HARIAN (GAMIFICATION)
*Fokus: Memasang logika game pencocokan 4-target berbasis Alpine.js yang ringan.*

- [ ] **Task 5.1: Modul Lab Virtual (Macro-to-Micro Image Toggle)**
  - Implementasikan logika penukar gambar instan menggunakan Alpine.js (`x-data="{ isMicro: false }"`).
  - Saat tombol ditekan, lakukan penggantian visual dari gambar daun makro ke gambar sel stomata mikro berformat WebP dengan transisi kelas `transition-opacity duration-300`.
- [ ] **Task 5.2: UI/UX Puzzle Organel Sesuai Berkas image_f22c9c.jpg**
  - Terapkan layout dua kolom responsif: Sisi kiri memuat 4 kotak target deskripsi fungsi, sisi kanan memuat 4 tombol vertikal nama organel pilihan.
  - Integrasikan sistem penghitung skor dinamis kelipatan 25 di sisi klien menggunakan Alpine.js hingga batas atas maksimal 100 poin.
- [ ] **Task 5.3: API Pengunci Skor Tantangan Harian (Backend Guard)**
  - Tulis logika validasi di `POST /api/puzzle/submit`: Cek records pengerjaan milik siswa bersangkutan pada tanggal server hari ini.
  - Aturan: Jika pengerjaan pertama, simpan skor game dan beri nilai tambahan leaderboard `earned_points = 10`. Jika merupakan pengerjaan ulang (percobaan ke-2 dst), sistem tetap menyimpan log skor latihan namun memberi nilai tambahan `earned_points = 0`.

---

## FASE 6: ENGINE KUIS KELAS & SISTEM TIMING (HIGH-STAKES QUIZ)
*Fokus: Mengembangkan sistem kuis ujian utama dengan sistem pengaman token manual.*

- [ ] **Task 6.1: Gerbang Token Validasi Siswa**
  - Buat halaman antara sebelum masuk kuis yang mewajibkan siswa menginput 6-digit token aktif dari guru.
- [ ] **Task 6.2: JavaScript Countdown Timer & Auto-Submit**
  - Bangun UI lembar soal kuis pilihan ganda yang dilengkapi komponen penghitung mundur waktu pengerjaan.
  - Tulis fungsi keamanan JavaScript: Jika waktu hitung mundur menyentuh angka `00:00`, picu fungsi pengiriman form jawaban secara otomatis (*auto-submit*) via AJAX ke server untuk mencegah kecurangan.
- [ ] **Task 6.3: Tombol Anti-Double Click**
  - Gunakan direktif Alpine.js `x-bind:disabled="loading"` atau vanilla JS pada tombol kirim kuis agar tombol langsung membeku setelah ditekan satu kali oleh siswa guna menghindari pengiriman data ganda akibat latensi jaringan HP.

---

## FASE 7: PAPAN PERINGKAT GLOBAL BERBASIS HAK AKSES (LEADERBOARD)
*Fokus: Mengolah agregasi nilai kuis dan poin harian puzzle secara adil.*

- [ ] **Task 7.1: Algoritma Kueri Nilai Akumulatif**
  - Susun kueri database (Eloquent/Query Builder) untuk menjumlahkan Nilai Kuis Utama dengan total Poin Tambahan Puzzle (`earned_points`) untuk mengukur performa total siswa kelas XI.
- [ ] **Task 7.2: Pembatasan Tampilan Top 10 Sisi Siswa**
  - Sisi Halaman Siswa: Batasi kueri baris peringkat murni hanya menampilkan urutan peringkat 1 sampai 10 saja (`->take(10)`) demi menjaga privasi psikologis siswa papan bawah.
- [ ] **Task 7.3: Visibilitas Peringkat Penuh 100% Sisi Admin**
  - Sisi Dashboard Admin Guru: Tampilkan daftar tabel seluruh siswa angkatan tanpa batasan urutan (100% data), lengkap dengan kolom input pencarian nama/NIS dan tombol ekspor dokumen.

---

## FASE 8: DASHBOARD BACK-OFFICE MANAJEMEN GURU (ADMIN PANEL)
*Fokus: Menyediakan pusat kendali manajemen data konten dan siswa bagi guru.*

- [ ] **Task 8.1: Pengelolaan Data Siswa Massal (Excel Import)**
  - Bangun antarmuka unggah berkas di menu admin.
  - Integrasikan library parser (misal: `Maatwebsite/Laravel-Excel`) untuk membaca data Excel/CSV dan melakukan registrasi akun siswa massal secara otomatis.
- [ ] **Task 8.2: Panel Kontrol Kuis & Aktivasi Token**
  - Buat halaman administrasi pembuatan soal kuis pilihan ganda.
  - Sediakan tombol saklar "Aktifkan Kuis" yang memicu fungsi pembuatan string acak 6-digit token kuis unik di database.
- [ ] **Task 8.3: Kalender Penjadwalan Rilis Puzzle**
  - Buat form CRUD input bank soal puzzle harian di mana guru bisa menentukan isi 4 pertanyaan fungsi organel beserta tanggal penayangannya (`publish_date`).

---

## FASE 9: OPTIMASI KINERJA JARINGAN SELULER (PERFORMANCE TUNING)
*Fokus: Menghemat kuota internet HP siswa dan meringankan beban kerja memory server VPS.*

- [x] **Task 9.1: Layanan Kompresi Gambar Otomatis (WebP Compactor)**
  - Buat utilitas fungsi penanganan berkas (*File Upload Helper*) di sisi admin menggunakan ekstensi GD PHP atau Imagick.
  - Logika: Setiap kali guru mengunggah gambar materi/lab baru, sistem wajib melakukan kompresi otomatis dan mengubah ekstensi file menjadi format `.webp` dengan tingkat kualitas 75% sebelum disimpan ke storage disk.
- [x] **Task 9.2: Mekanisme Caching Data Leaderboard**
  - Bungkus kueri agregasi nilai papan peringkat global menggunakan fasad `Cache::remember()`.
  - Set waktu kedaluwarsa penyimpanan cache selama 10 menit (600 detik) dengan kata kunci `global_leaderboard_data` agar server tidak kolaps akibat beban baca berulang dari ratusan HP siswa secara bersamaan.

---

## FASE 10: SEEDING, PENGUJIAN AKHIR, & DEPLOYMENT VPS (GO-LIVE)
*Fokus: Melakukan instalasi aplikasi ke peladen publik agar aman diakses.*

- [ ] **Task 10.1: Database Master Seeder**
  - Buat file `UserSeeder` untuk menginjeksikan data akun guru/admin utama pertama kali agar sistem bisa dioperasikan setelah migrasi.
- [ ] **Task 10.2: Konfigurasi Server Produksi (Nginx & PHP-FPM)**
  - Arahkan domain/subdomain sekolah menuju IP publik VPS IDCloudHost.
  - Setup file blok konfigurasi Nginx, sesuaikan batasan alokasi memory di `php.ini` agar optimal berjalan di spesifikasi RAM 2 GB.
- [ ] **Task 10.3: Instalasi Sertifikat Pengaman SSL HTTPS**
  - Pasang SSL Let's Encrypt menggunakan Certbot agar seluruh transmisi data ujian terlindungi enkripsi HTTPS, aman dari blokir browser mobile.
- [ ] **Task 10.4: Eksekusi Kode Optimasi Laravel Production**
  - Jalankan rangkaian perintah optimasi akhir di terminal VPS:
    - `composer install --optimize-autoloader --no-dev`
    - `php artisan config:cache`
    - `php artisan route:cache`
    - `php artisan view:cache`