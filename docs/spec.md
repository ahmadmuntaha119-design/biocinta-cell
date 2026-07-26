# PRODUCT REQUIREMENT DOCUMENT (PRD) - SPECIFICATION
## SYSTEM NAME: BioCinta Cell
## VERSION: 1.3.0
## DESCRIPTION: Media Pembelajaran Sel Hewan & Tumbuhan Berbasis Kurikulum CINTA (MAN 2 Kebumen)

---

## 1. USER ROLES & PERSONAS

Sistem memiliki 2 (dua) tipe pengguna dengan batasan hak akses yang ketat:

### 1.1 Admin (Guru Biologi)
- Mengelola data akun siswa melalui mekanisme import massal (Excel/CSV).
- Mengelola materi pembelajaran interaktif (Sel Hewan, Sel Tumbuhan, dan Pengenalan Mikroskop).
- Mengelola Bank Soal Tantangan Puzzle Harian (Menentukan rilis teks fungsi & organel berdasarkan tanggal).
- Menilai, mengevaluasi, dan meninjau portofolio proyek kolaboratif siswa.
- Mengontrol penuh kuis utama (Membuat soal, tombol buka-tutup akses kuis, dan pembuatan token 6-digit).
- Memantau rekapitulasi nilai seluruh siswa secara penuh (100% data) dan melakukan ekspor nilai ke Excel.

### 1.2 User (Siswa Kelas XI)
- Mengakses sub-materi sel dan alat mikroskop secara mandiri.
- Melakukan simulasi pengamatan sel makro-ke-mikro di modul Lab Virtual.
- Mengisi refleksi spiritual keagamaan (Kurikulum CINTA) di setiap akhir bab materi.
- Mengunggah link proyek kelompok berupa video pembelajaran luar (YouTube/Drive).
- Berpartisipasi dalam Kuis Kelas (Wajib Token Guru) dan Tantangan Puzzle Harian Otomatis (Tanpa Token).
- Melihat peringkat 10 besar (Top 10) pada papan peringkat global.

---

## 2. CORE FUNCTIONAL MODULES

### MODULE 1: AUTHENTICATION & MANDATORY SECURITY LIFECYCLE

#### 2.1.1 Deskripsi Fitur
Mengatur gerbang masuk utama sistem, pemisahan hak akses, dan kebijakan keamanan kata sandi awal yang wajib dilewati oleh siswa demi melindungi integritas data nilai kuis.

#### 2.1.2 Urutan Perjalanan Pengguna (User Journey Sequences)
**A. Alur Siswa Baru (Pertama Kali Login):**
1. Siswa mengakses URL aplikasi ➡️ Sistem mendeteksi siswa belum masuk (*Guest*) ➡️ Siswa diarahkan paksa ke **Halaman Login**.
2. Siswa memasukkan Nomor Induk Siswa (NIS) dan kata sandi bawaan (*default*) dari sekolah.
3. Siswa menekan tombol masuk ➡️ Sistem memverifikasi kredensial dan mendeteksi bahwa status `is_password_changed` masih bernilai `false`.
4. Sistem **WAJIB MEMBLOKIR** akses ke halaman utama dan mengalihkan siswa secara paksa ke **Halaman Ubah Kata Sandi** (`/change-password`).
5. Siswa memasukkan kata sandi baru yang unik dan menekan tombol simpan.
6. Sistem memperbarui status keamanan akun siswa menjadi `is_password_changed = true` ➡️ Siswa dialihkan ke **Halaman Beranda (Homepage)** dengan akses penuh.

**B. Alur Siswa Lama (Sudah Pernah Mengubah Kata Sandi):**
1. Siswa mengakses URL aplikasi ➡️ Diarahkan ke **Halaman Login**.
2. Siswa memasukkan NIS dan kata sandi baru mereka yang telah diubah sebelumnya.
3. Sistem memverifikasi kredensial dan mendeteksi bahwa status keamanan akun sudah aman (`is_password_changed = true`).
4. Sistem langsung mengalihkan siswa ke **Halaman Beranda (Homepage)** tanpa hambatan.

---

### MODULE 2: CENTRAL HOMEPAGE DASHBOARD (NAVIGATION HUB)

#### 2.2.1 Deskripsi Fitur
Halaman utama (*Beranda*) yang berfungsi sebagai pusat kendali dan navigasi utama siswa untuk mengakses seluruh menu pembelajaran setelah sukses melewati gerbang autentikasi. Tampilan mengadopsi struktur *Card-Based Layout* yang bersih dan modern.

#### 2.2.2 Aturan Bisnis (Business Rules)
1. **Pusat Navigasi Terintegrasi (6 Core Cards):** Untuk memastikan estetika grid yang seimbang dan simetris (susunan layout 3x2 di desktop), menu utama **WAJIB** dipecah menjadi 6 kartu navigasi terpisah:
   - **Card 1: Materi Interaktif Sel** (Materi visual Sel Hewan & Tumbuhan).
   - **Card 2: Pengenalan Alat Mikroskop** (Materi teori jenis-jenis mikroskop).
   - **Card 3: Laboratorium Maya** (Simulasi klik makro-ke-mikro).
   - **Card 4: Puzzle Cocok Organel** (Game tantangan harian otomatis).
   - **Card 5: Projek Kolaboratif** (Tempat unggah tugas video kelompok).
   - **Card 6: Ruang Tadabbur & Peringkat** (Akses pengisian jurnal refleksi spiritual & Papan Peringkat).
2. **Identitas Pengguna:** Bagian atas halaman Beranda wajib menampilkan teks sambutan personal yang dinamis yang memuat Nama Lengkap Siswa dan NIS mereka.
3. **Banner Informasi:** Menyediakan ruang banner statis di bagian atas bertuliskan informasi identitas media pembelajaran *"BioCinta Cell: Platform Belajar Biologi Interaktif Berbasis Kurikulum CINTA"* untuk memperkuat *branding* sekolah.

---

### MODULE 3: INTERACTIVE BIOLOGY MATERIAL & MICROSCOPE ANATOMY

#### 2.3.1 Deskripsi Fitur
Modul literasi dan visual tempat siswa berinteraksi secara mandiri untuk mempelajari struktur sel dan dasar-dasar alat laboratorium (mikroskop).

#### 2.3.2 Aturan Bisnis (Business Rules)
1. **Navigasi Visual Sel (2D Component):** Menyajikan diagram ilustrasi sel utuh (Hewan & Tumbuhan). Setiap komponen organel di dalam gambar harus dapat diklik secara independen untuk masuk ke halaman detail sub-materi.
2. **Konten Sub-Materi Sel:** Halaman detail organel wajib menyajikan 4 pilar informasi: Penjelasan fungsi teknis, video visualisasi pendek, sejarah penemu (*founder*), dan Integrasi Nilai Islami (Tadabbur ayat Al-Quran yang selaras dengan fungsi organel tersebut).
3. **Sub-Bab Materi Alat Mikroskop:** Menyediakan satu halaman materi khusus yang membahas "Alat Mikroskop dan Jenis-jenisnya". Materi mencakup:
   - Perbedaan karakteristik Mikroskop Cahaya dan Mikroskop Elektron.
   - Perbedaan Mikroskop Monokuler dan Binokuler.
   - Fungsi dari masing-masing komponen mekanik dan optik mikroskop (Lensa okuler, lensa objektif, makrometer, mikrometer, reflektor/kondensor) sebagai materi prasyarat.
4. **Umpan Balik (Feedback System):** Siswa dapat menuliskan pertanyaan atau keluhan jika materi dirasa sulit dipahami. Pesan ini terkirim langsung ke dashboard guru.

---

### MODULE 4: VIRTUAL MICROSCOPE LAB (MACRO-TO-MICRO TOGGLE)

#### 2.4.1 Deskripsi Fitur
Media simulasi digital yang meniru pengalaman praktikum nyata menggunakan mikroskop laboratorium untuk mengamati jaringan sel daun atau sel hewan secara bertahap.

#### 2.4.2 Aturan Bisnis (Business Rules)
1. **Mekanisme Kerja Toggle Mandiri:**
   - Tampilan awal memunculkan **Gambar Level 1 (Makro)**: Wujud objek penampang luar yang dilihat oleh mata telanjang (contoh: Gambar daun utuh).
   - Terdapat tombol aksi interaktif bertuliskan "Lihat Lewat Lensa Mikroskop".
   - Ketika tombol ditekan, sistem melakukan *swapping* gambar dan menampilkan **Gambar Level 2 (Mikro)**: Struktur selular terdalam hasil perbesaran lensa mikroskop (contoh: Susunan jaringan stomata/kloroplas daun WebP kualitas tinggi) dengan efek transisi halus.

---

### MODULE 5: COLLABORATIVE PROJECT SPACE

#### 2.5.1 Deskripsi Fitur
Wadah digital bagi siswa untuk mengumpulkan hasil karya proyek kelompok/individu mengenai pembuatan replika model sel atau animasi sel.

#### 2.5.2 Aturan Bisnis (Business Rules)
1. **Mekanisme Pengumpulan:** Siswa menginput judul proyek, deskripsi teks naratif kelompok, dan menyematkan tautan (URL) video proyek mereka yang sudah diunggah ke platform luar (YouTube atau Google Drive).
2. **Penilaian Guru:** Guru dapat memberikan nilai angka (skala 0-100) beserta catatan evaluasi kritis pada setiap proyek melalui dashboard admin. Nilai dan evaluasi ini langsung tampil *real-time* di halaman siswa pemilik proyek.

---

### MODULE 6: ISLAMIC REFLECTION (KURIKULUM CINTA)

#### 2.6.1 Deskripsi Fitur
Formulir evaluasi afektif dan spiritual untuk menilai tingkat ketakjuban siswa terhadap keteraturan penciptaan sel setelah menyelesaikan pembelajaran.

#### 2.6.2 Aturan Bisnis (Business Rules)
1. **Struktur Pertanyaan Esai:** Form refleksi bersifat wajib diisi pada setiap akhir sesi materi, mencakup 3 kolom esai pendek wajib:
   - **Connection:** Struktur sel apa yang paling membuatmu takjub akan kebesaran Sang Pencipta?
   - **Wisdom:** Hikmah spiritual apa yang kamu dapatkan setelah mempelajari keteraturan sel ini?
   - **Implementation:** Apa langkah nyata tindakan sehari-hari sebagai perwujudan rasa syukur atas keteraturan sel dalam tubuhmu?

---

### MODULE 7: GAMIFICATION - DAILY ORGANELLE MATCHING PUZZLE

#### 2.7.1 Deskripsi Fitur
Game edukatif harian berbasis pencocokan objek (*Click-to-Match*) yang menyajikan tepat 4 tantangan fungsi organel per hari (Berjalan penuh di sisi klien menggunakan Alpine.js).

#### 2.7.2 Aturan Bisnis (Business Rules)
1. **Struktur Grid Permainan (Fixed Layout):**
   - Sisi Kiri: Menampilkan panel **Target Fungsi Organel** yang berisi tepat **4 kotak teks deskripsi fungsi** yang diambil secara dinamis berdasarkan tanggal server hari ini.
   - Sisi Kanan: Menampilkan panel **Pilihan Nama Organel** berupa **4 tombol nama organel** yang diacak.
2. **Sistem Poin Validasi Eksplisit (Format Gambar):**
   - Setiap pasangan yang dicocokkan dengan benar menghasilkan nilai **25 poin**.
   - Total nilai maksimal jika ke-4 organel terpasang dengan benar adalah **100 poin** (`Skor: 4 / 4`).
3. **Kebijakan Pembatasan Tantangan Harian (Daily Quest Reset):**
   - Game terbuka secara otomatis setiap hari tanpa membutuhkan token atau aktivasi manual dari guru.
   - **Hanya percobaan pertama (1st Attempt) pada hari tersebut yang skornya akan dikirim dan disimpan ke server** untuk akumulasi poin *leaderboard*.
   - Siswa tetap dibebaskan bermain ulang (mencoba kembali) game tersebut di hari yang sama untuk sarana latihan mandiri, namun sistem backend akan menolak memberikan poin tambahan tambahan (`earned_points = 0`).
4. **Manajemen Penjadwalan Guru:** Guru memasukkan bank soal puzzle dari menu admin dan menetapkan tanggal tayang (*publish date*). Sistem otomatis menayangkan soal baru saat tanggal server berganti ke hari berikutnya.

---

### MODULE 8: ACCUMULATED GLOBAL LEADERBOARD

#### 2.8.1 Deskripsi Fitur
Halaman rekapitulasi skor kompetitif angkatan untuk memacu semangat belajar siswa. Skor total diperoleh dari gabungan nilai kuis pilihan ganda dan poin akumulatif tantangan puzzle harian.

#### 2.8.2 Aturan Bisnis (Business Rules)
1. **Pembedaan Hak Akses Visibilitas Peringkat (Crucial Rule):**
   - **Sisi Siswa:** Papan peringkat **HANYA** diperbolehkan menampilkan urutan peringkat 1 sampai 10 saja (Top 10) demi menjaga iklim kompetisi yang sehat dan melindungi kondisi mental/privasi nilai siswa yang berada di papan bawah.
   - **Sisi Admin (Guru):** Dashboard admin **WAJIB** menampilkan **seluruh peringkat siswa secara utuh tanpa batasan (Full List 100%)**, lengkap dengan fitur pencarian nama/NIS siswa dan tombol unduh rekap nilai keseluruhan dalam format .xlsx untuk kebutuhan nilai rapor.

---

## 3. GLOBAL SYSTEM EDGE CASES & ERROR BEHAVIORS

1. **Manipulasi URL Manual:** Segala bentuk upaya manipulasi URL bypass hak akses (contoh: siswa mengetik `/admin/dashboard` secara manual) harus langsung dihadang oleh sistem keamanan internal (Middleware Route) dan dialihkan ke halaman error `403 Unauthorized Access`.
2. **Double-Click Submission Prevention:** Pada saat siswa menekan tombol "Kirim Hasil" di modul kuis atau puzzle, sistem wajib menonaktifkan (*disable*) tombol tersebut secara instan guna mencegah pengiriman data ganda (*duplicate rows*) akibat koneksi internet siswa yang lambat.
```