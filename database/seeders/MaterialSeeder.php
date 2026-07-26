<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialHotspot;
use Illuminate\Database\Seeder;

/**
 * Task 4.1 & 4.2: Seed konten materi awal — Organel Sel Hewan, Sel Tumbuhan, & Teori Mikroskop.
 * Data ini dirancang sesuai spec "4 Pilar Informasi": Fungsi Teknis, Video, Sejarah Penemu, Tadabbur Quran.
 */
class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        //  SEL HEWAN — 6 Organel Utama (type: 'animal')
        // ============================================================

        $animalOrganelles = [
            [
                'type'            => 'animal',
                'title'           => 'Membran Sel (Plasma)',
                'slug'            => 'membran-sel-hewan',
                'description'     => "Membran sel adalah lapisan tipis fleksibel yang menyelimuti seluruh sel dan berfungsi sebagai penjaga gerbang (gatekeeper) antara bagian dalam sel dengan lingkungan luar. Tersusun dari dua lapisan fosfolipid (bilayer) dengan protein tersemat di dalamnya, struktur ini dikenal sebagai Model Mosaik Fluid (Singer & Nicolson, 1972).\n\nFungsi utamanya adalah mengatur secara selektif zat-zat yang boleh masuk dan keluar sel — proses yang disebut permeabilitas selektif. Nutrien penting seperti glukosa dan oksigen diizinkan masuk, sementara zat racun ditolak. Membran sel juga berperan dalam komunikasi antar sel melalui reseptor protein.",
                'youtube_id'      => null,
                'history_founder' => "Robert Hooke pertama kali mengamati dinding sel pada tahun 1665. Pemahaman modern tentang membran sel berkembang melalui kontribusi banyak ilmuwan. Charles Overton (1890-an) menemukan bahwa lemak mudah menembus membran, menyimpulkan bahwa membran tersusun dari lipid. Konsep Mosaik Fluid kemudian disempurnakan oleh S.J. Singer dan G.L. Nicolson pada 1972 melalui percobaan ingenius menggunakan antibodi berlabel.",
                'quran_verse'     => "\"Dia-lah yang menjadikan segala yang ada di bumi untuk kamu\" (QS. Al-Baqarah: 29). Membran sel adalah bukti arsitektur Ilahi yang sempurna — sebuah lapisan setipis 7 nanometer mampu membedakan mana yang 'baik' dan 'buruk' bagi sel, jauh melampaui kemampuan teknologi filtrasi manusia mana pun.",
            ],
            [
                'type'            => 'animal',
                'title'           => 'Nukleus (Inti Sel)',
                'slug'            => 'nukleus-hewan',
                'description'     => "Nukleus adalah organel terbesar dalam sel eukariot dan berfungsi sebagai 'otak' sel — pusat kendali yang menyimpan seluruh informasi genetik dalam bentuk DNA (asam deoksiribonukleat). Di dalam nukleus terdapat nukleolus, yaitu struktur padat tempat ribosom diproduksi.\n\nNukleus diselimuti oleh selubung inti (nuclear envelope) berpori yang mengatur lalu lintas molekul: RNA pesan (mRNA) keluar menuju ribosom untuk sintesis protein, sementara protein regulasi dari sitoplasma dapat masuk. Saat sel akan membelah, DNA dikemas menjadi kromosom yang terlihat jelas di bawah mikroskop cahaya.",
                'youtube_id'      => null,
                'history_founder' => "Robert Brown, botanis Skotlandia, pertama kali mendeskripsikan nukleus secara ilmiah pada tahun 1831 saat mengamati sel anggrek di bawah mikroskop. Ia menyebutnya 'areola' atau 'nucleus'. Kemudian, teori sel yang dibangun oleh Matthias Schleiden (1838) dan Theodor Schwann (1839) menjadikan nukleus sebagai bukti bahwa semua makhluk hidup tersusun dari sel.",
                'quran_verse'     => "\"Sesungguhnya Allah Maha Mengetahui segala sesuatu\" (QS. Al-Hujurat: 16). Seperti Allah yang Maha Mengetahui segala rahasia alam semesta, nukleus menyimpan 'rencana induk' kehidupan — 3 miliar pasang basa DNA dalam setiap sel tubuhmu yang jika direntangkan akan mencapai panjang 2 meter, namun dikemas secara menakjubkan dalam inti sel yang tak kasat mata.",
            ],
            [
                'type'            => 'animal',
                'title'           => 'Mitokondria',
                'slug'            => 'mitokondria-hewan',
                'description'     => "Dijuluki 'pembangkit tenaga sel' (powerhouse of the cell), mitokondria menghasilkan energi dalam bentuk ATP (Adenosin Trifosfat) melalui proses respirasi seluler aerobik. Mitokondria memiliki dua membran: membran luar yang halus dan membran dalam yang berlipat-lipat membentuk struktur yang disebut krista — lipatan ini memperluas permukaan area produksi energi secara dramatis.\n\nYang mengagumkan: mitokondria memiliki DNA sendiri dan bereplikasi secara mandiri! Ini mendukung Teori Endosimbion yang menyatakan bahwa mitokondria dulunya adalah bakteri bebas yang bersimbiosis dengan sel eukariot lebih dari 1,5 miliar tahun lalu.",
                'youtube_id'      => null,
                'history_founder' => "Richard Altmann pertama kali mengamati mitokondria pada tahun 1890 dan menyebutnya 'bioblast'. Nama 'mitokondria' diberikan oleh Carl Benda pada 1898 dari bahasa Yunani (mitos = benang, chondros = butiran). Peter Mitchell memenangkan Nobel Kimia 1978 atas penemuannya tentang mekanisme chemiosmosis — cara mitokondria menggunakan gradien proton untuk mensintesis ATP.",
                'quran_verse'     => "\"Dan Kami jadikan dari air segala sesuatu yang hidup\" (QS. Al-Anbiya: 30). Mitokondria adalah mesin kehidupan yang tak pernah berhenti bekerja. Setiap sel jantungmu mengandung lebih dari 5.000 mitokondria yang memompa energi 24 jam tanpa henti — sebuah keteraturan yang hanya bisa tercipta dari kekuasaan Al-Hayyu (Yang Maha Hidup).",
            ],
            [
                'type'            => 'animal',
                'title'           => 'Retikulum Endoplasma Kasar (RE Kasar)',
                'slug'            => 're-kasar-hewan',
                'description'     => "Retikulum Endoplasma Kasar (RE Kasar atau RER) adalah jaringan membran berlipat yang terhubung langsung dengan selubung nukleus. Disebut 'kasar' karena permukaannya dipenuhi oleh ribuan ribosom yang menempel — ribosom inilah yang mensintesis protein.\n\nProtein yang baru disintesis di ribosom RE Kasar langsung masuk ke dalam lumen (rongga dalam) RE untuk dilipat, dimodifikasi, dan dikemas dengan benar. RE Kasar kemudian membentuk vesikel (kantong kecil) berisi protein yang dikirim ke Apparatus Golgi untuk pemrosesan lebih lanjut — sebuah jalur pengiriman internal yang sangat efisien.",
                'youtube_id'      => null,
                'history_founder' => "Albert Claude, ahli biologi Belgia-Amerika, adalah yang pertama berhasil memisahkan dan mengidentifikasi retikulum endoplasma menggunakan teknik sentrifugasi sel pada tahun 1940-an. Atas penemuan-penemuannya dalam biologi sel, Claude memenangkan Hadiah Nobel Fisiologi atau Kedokteran pada tahun 1974 bersama George Palade dan Christian de Duve.",
                'quran_verse'     => "\"Dia mengatur urusan dari langit ke bumi\" (QS. As-Sajdah: 5). RE Kasar adalah 'sistem logistik' sel yang mengagumkan — ia menerima 'pesanan' dari DNA, memproduksi protein sesuai spesifikasi, dan mengirimkannya ke tujuan yang tepat. Ini adalah bukti nyata sistem manajemen ciptaan Allah yang jauh melampaui sistem distribusi manapun buatan manusia.",
            ],
            [
                'type'            => 'animal',
                'title'           => 'Aparatus Golgi',
                'slug'            => 'golgi-hewan',
                'description'     => "Aparatus Golgi (atau Badan Golgi) adalah organel yang berfungsi sebagai 'kantor pos' atau 'pusat distribusi' sel. Berbentuk tumpukan kantong pipih berlapis (sisterna), organel ini menerima protein dan lipid dari Retikulum Endoplasma, lalu memodifikasi, menyortir, dan mengemas ulang mereka sebelum dikirimkan ke tujuan akhirnya.\n\nAda tiga tujuan pengiriman dari Golgi: (1) ke membran sel untuk sekresi keluar, (2) ke lisosom sebagai enzim pencernaan, atau (3) tetap berada di dalam sel. Golgi juga berperan penting dalam pembentukan glikoprotein — protein yang dilekat dengan rantai gula untuk berbagai fungsi.",
                'youtube_id'      => null,
                'history_founder' => "Camillo Golgi, dokter dan ahli anatomi Italia, menemukan organel ini pada tahun 1897 menggunakan teknik pewarnaan perak yang ia temukan sendiri (kemudian dikenal sebagai 'Reaksi Hitam Golgi'). Penemuan ini sangat kontroversial — banyak ilmuwan tidak percaya strukturnya nyata dan mengira itu artefak pewarnaan. Golgi akhirnya memenangkan Hadiah Nobel Fisiologi atau Kedokteran pada 1906, bersama Santiago Ramón y Cajal.",
                'quran_verse'     => "\"Sesungguhnya Allah tidak menyia-nyiakan pahala orang-orang yang berbuat baik\" (QS. At-Tawbah: 120). Aparatus Golgi mengajarkan kita tentang efisiensi dan ketelitian — tidak ada satu pun protein yang dikirim ke tujuan yang salah dalam sistem distribusi sel ini. Beginilah keteraturan ciptaan Allah bekerja: setiap bagian menjalankan fungsinya dengan presisi yang sempurna.",
            ],
            [
                'type'            => 'animal',
                'title'           => 'Lisosom',
                'slug'            => 'lisosom-hewan',
                'description'     => "Lisosom adalah organel berbentuk bola kecil yang berfungsi sebagai 'sistem pencernaan' sel. Mengandung lebih dari 50 jenis enzim hidrolitik yang aktif pada pH asam (~5), lisosom mampu mencerna/mendaur ulang berbagai bahan biologis: protein rusak, organel tua yang tidak berfungsi, partikel makanan, dan bahkan patogen (bakteri yang masuk ke sel).\n\nProses pencernaan organel sel sendiri oleh lisosom disebut autofagi — mekanisme 'self-cleaning' luar biasa yang memungkinkan sel memperbarui komponennya sendiri. Disfungsi lisosom dapat menyebabkan penyakit penimbunan lisosom yang serius seperti Tay-Sachs dan Gaucher.",
                'youtube_id'      => null,
                'history_founder' => "Christian de Duve, biokimiawan Belgia, menemukan lisosom secara tidak sengaja pada tahun 1955 saat sedang melakukan penelitian tentang enzim hati tikus menggunakan teknik sentrifugasi bertahap. Penemuan lisosom (dan peroksisom) mengantarkannya meraih Nobel Fisiologi atau Kedokteran 1974 bersama Albert Claude dan George Palade.",
                'quran_verse'     => "\"Dan Kami turunkan hujan dari langit lalu Kami tumbuhkan padanya segala jenis tanaman yang baik\" (QS. Luqman: 10). Lisosom mengajarkan nilai penting daur ulang dan pembaruan diri (autofagi) — konsep yang Allah tanamkan jauh di tingkat sel. Seperti hutan yang mendaur ulang dedaunan gugur menjadi tanah subur, lisosom mendaur ulang komponen sel tua menjadi bahan bangunan sel yang baru.",
            ],
        ];

        // ============================================================
        //  SEL TUMBUHAN — 6 Organel Utama (type: 'plant')
        // ============================================================

        $plantOrganelles = [
            [
                'type'            => 'plant',
                'title'           => 'Dinding Sel',
                'slug'            => 'dinding-sel-tumbuhan',
                'description'     => "Dinding sel adalah lapisan kaku yang terletak di luar membran plasma, memberikan bentuk, perlindungan mekanik, dan kekuatan struktural pada sel tumbuhan. Tersusun terutama dari selulosa (polisakarida linear yang membentuk fibril), hemiselulosa, pektin, dan terkadang lignin pada sel-sel tua yang mengalami penebalan.\n\nDinding sel bersifat permeabel — air dan zat-zat terlarut dapat melewatinya dengan bebas. Antar sel tumbuhan dihubungkan melalui plasmodesmata (saluran sitoplasmik kecil) yang menembus dinding sel, memungkinkan komunikasi dan pertukaran material antar sel.",
                'youtube_id'      => null,
                'history_founder' => "Robert Hooke adalah ilmuwan pertama yang mengamati dinding sel pada tahun 1665 menggunakan mikroskop sederhana buatannya sendiri. Saat mengamati irisan gabus (kulit kayu pohon), ia melihat struktur mirip sarang lebah dan menamakannya 'cellulae' (ruangan kecil dalam bahasa Latin) — inilah asal-usul kata 'cell' yang kita gunakan hingga saat ini!",
                'quran_verse'     => "\"Dia-lah yang menjadikan bumi sebagai hamparan bagimu\" (QS. Al-Baqarah: 22). Dinding sel adalah fondasi dari segala keteguhan pada tumbuhan — ia memungkinkan pohon raksasa berdiri menjulang tinggi dan bunga harum bermekaran elok. Semuanya tersusun dari molekul selulosa sekecil atom yang dirancang Allah dengan ketepatan yang memukau.",
            ],
            [
                'type'            => 'plant',
                'title'           => 'Nukleus (Inti Sel)',
                'slug'            => 'nukleus-tumbuhan',
                'description'     => "Sama seperti pada sel hewan, nukleus sel tumbuhan adalah pusat kendali yang menyimpan informasi genetik dalam DNA. Pada sel tumbuhan, nukleus biasanya terletak di pinggir sel akibat desakan dari vakuola sentral yang besar.\n\nNukleus mengandung kromosom yang tersusun dari DNA dan protein histon. Pada tumbuhan, jumlah kromosom bervariasi: tomat memiliki 24 kromosom, kentang 48, dan paku-pakuan tertentu bisa memiliki lebih dari 1000 kromosom! Nukleus juga mengandung nukleolus — tempat produksi rRNA yang menjadi komponen ribosom.",
                'youtube_id'      => null,
                'history_founder' => "Walther Flemming (1843-1905), ahli anatomi Jerman, memberikan kontribusi besar dalam memahami nukleus tumbuhan. Ia menemukan proses pembelahan sel (mitosis) dan menamai zat yang mudah terwarnai di dalam nukleus sebagai 'chromatin' (dari bahasa Yunani chroma = warna) pada tahun 1879. Temuannya membuka jalan bagi pemahaman tentang pewarisan sifat.",
                'quran_verse'     => "\"Dan Allah mengetahui apa yang tersembunyi dalam dada\" (QS. Hud: 5). Nukleus menyimpan 'rahasia kehidupan' tumbuhan dalam untaian DNA yang tidak kasat mata — mengandung instruksi untuk setiap daun, bunga, buah, dan akar yang akan tumbuh. Hanya Allah yang Maha Mengetahui cara kerja kode genetik yang luar biasa rumit ini.",
            ],
            [
                'type'            => 'plant',
                'title'           => 'Kloroplas',
                'slug'            => 'kloroplas-tumbuhan',
                'description'     => "Kloroplas adalah organel unik pada sel tumbuhan dan alga yang berfungsi sebagai 'pabrik makanan' — tempat berlangsungnya fotosintesis. Di dalam kloroplas terdapat membran berlapis yang disebut tilakoid (berbentuk kantong pipih bertumpuk membentuk grana), dan cairan yang mengisi ruang antar grana disebut stroma.\n\nFotosintesis berlangsung dalam dua tahap: reaksi terang di membran tilakoid (menangkap energi cahaya dan menghasilkan ATP & NADPH) dan siklus Calvin di stroma (menggunakan energi tersebut untuk mengubah CO₂ menjadi glukosa). Seperti mitokondria, kloroplas juga memiliki DNA sendiri — bukti Teori Endosimbion dari sianobakteri purba.",
                'youtube_id'      => null,
                'history_founder' => "Jan Ingenhousz, dokter asal Belanda, menemukan bahwa tanaman membutuhkan cahaya untuk menghasilkan oksigen pada tahun 1779. Melvin Calvin memenangkan Nobel Kimia 1961 atas penemuannya tentang siklus Calvin (siklus Benson-Calvin). Peter Mitchell (Nobel 1978) menjelaskan mekanisme chemiosmosis yang terjadi di membran tilakoid.",
                'quran_verse'     => "\"Dan apakah mereka tidak memperhatikan, bagaimana Kami menciptakan untuk mereka dari apa yang telah Kami ciptakan, binatang-binatang ternak, lalu mereka menguasainya?\" (QS. Ya Sin: 71-72). Kloroplas mengubah cahaya matahari menjadi makanan — sebuah mukjizat biokimia yang menjadi landasan seluruh rantai makanan di bumi. Tanpa kloroplas, tidak ada satu pun kehidupan yang bisa bertahan.",
            ],
            [
                'type'            => 'plant',
                'title'           => 'Vakuola Sentral',
                'slug'            => 'vakuola-tumbuhan',
                'description'     => "Vakuola sentral adalah organel paling mencolok pada sel tumbuhan dewasa — dapat menempati hingga 90% volume total sel! Berisi cairan sel (getah sel) yang kaya akan air, ion, gula, asam organik, dan pigmen warna (seperti antosianin yang memberi warna merah, biru, dan ungu pada bunga).\n\nFungsi utama vakuola sentral: (1) Turgor — tekanan cairan di dalam vakuola mendorong keluar ke dinding sel, menjaga kekerasan dan tegaknya batang tumbuhan; (2) Penyimpanan senyawa kimia; (3) Pembuangan limbah seluler; (4) Menekan sitoplasma ke pinggir sel agar kloroplas mendapat lebih banyak cahaya.",
                'youtube_id'      => null,
                'history_founder' => "Wilhelm Pfeffer (1877) adalah ilmuwan pertama yang melakukan studi sistematis tentang osmosis dan tekanan turgor sel tumbuhan menggunakan osmometer. Penemuannya menjelaskan mengapa tumbuhan layu saat kekurangan air — vakuola mengempis dan turgor hilang — serta bagaimana tumbuhan pulih setelah disiram.",
                'quran_verse'     => "\"Dan Kami turunkan dari langit air yang penuh keberkahan\" (QS. Qaf: 9). Vakuola sentral adalah 'tangki cadangan air' dalam setiap sel tumbuhan. Allah menciptakan sistem penyimpanan air yang sangat efisien ini jauh sebelum manusia mengenal teknologi reservoir. Itulah mengapa tumbuhan di gurun pun dapat bertahan — karena keajaiban vakuola yang diciptakan-Nya.",
            ],
            [
                'type'            => 'plant',
                'title'           => 'Mitokondria',
                'slug'            => 'mitokondria-tumbuhan',
                'description'     => "Sel tumbuhan juga memiliki mitokondria sebagai penghasil energi (ATP) melalui respirasi seluler — proses yang berlangsung terus-menerus, bahkan di malam hari ketika fotosintesis terhenti. Menariknya, sel tumbuhan melakukan KEDUA proses sekaligus di siang hari: kloroplas berfotosintesis menghasilkan glukosa, sementara mitokondria membakar glukosa tersebut untuk energi sel.\n\nPada tumbuhan, mitokondria sering bergerak (motil) di dalam sel mengikuti aliran sitoplasma (siklosis) — gerakan ini membantu distribusi ATP yang efisien ke seluruh bagian sel.",
                'youtube_id'      => null,
                'history_founder' => "Penelitian tentang mitokondria tumbuhan banyak dikembangkan oleh Peter Mitchell (Nobel 1978) dan kemudian oleh Paul Boyer (Nobel 1997) yang menjelaskan mekanisme rotasi molekuler ATP sintase — sebuah 'mesin molekuler' berdiameter 10 nanometer yang berputar seperti turbin untuk menghasilkan ATP, baik di mitokondria sel hewan maupun sel tumbuhan.",
                'quran_verse'     => "\"Dan Dia-lah yang menghidupkan dan mematikan dan Dialah yang mengatur pergantian malam dan siang\" (QS. Al-Mu'minun: 80). Mitokondria tumbuhan bekerja siang dan malam tanpa kenal lelah — saat kloroplas 'istirahat' di malam hari, mitokondria tetap berputar menghasilkan energi. Ini adalah wujud nyata keseimbangan sempurna dalam ciptaan Allah.",
            ],
            [
                'type'            => 'plant',
                'title'           => 'Plasmodesmata',
                'slug'            => 'plasmodesmata-tumbuhan',
                'description'     => "Plasmodesmata adalah saluran sitoplasmik kecil berdiameter ~50 nanometer yang menembus dinding sel, menghubungkan sitoplasma sel-sel tumbuhan yang bersebelahan. Melalui saluran ini, sel-sel tumbuhan saling berkomunikasi dan berbagi material seperti air, ion, dan molekul sinyal hormonal.\n\nSemua sel tumbuhan yang terhubung melalui plasmodesmata membentuk satu kesatuan sitoplasmik yang disebut simplast — sistem komunikasi internal tumbuhan yang menakjubkan. Ukuran molekul yang bisa melewati plasmodesmata dikontrol oleh protein spesifik, mirip 'penjaga pintu' molecular.",
                'youtube_id'      => null,
                'history_founder' => "Eduard Tangl, ahli botani Austria, pertama kali mengamati dan mendeskripsikan plasmodesmata pada tahun 1879 saat mempelajari endosperma biji kurma. Ia menyebutnya 'saluran komunikasi' antar sel. Nama 'plasmodesmata' diberikan oleh Eduard Strasburger (1901). Pemahaman modern tentang fungsi plasmodesmata dalam signaling tumbuhan baru berkembang pesat pada akhir abad ke-20.",
                'quran_verse'     => "\"Dan berpeganglah kamu semuanya kepada tali (agama) Allah, dan janganlah kamu bercerai berai\" (QS. Ali Imran: 103). Plasmodesmata mengajarkan makna persatuan — sel-sel tumbuhan tidak bekerja sendiri-sendiri, melainkan saling terhubung dan berbagi dalam satu kesatuan. Seperti umat yang bersatu, tumbuhan lebih kuat karena setiap selnya terhubung satu sama lain.",
            ],
        ];

        // ============================================================
        //  MIKROSKOP — 4 Bagian Teori (type: 'microscope')
        // ============================================================

        $microscopeSections = [
            [
                'type'            => 'microscope',
                'title'           => 'Mikroskop Cahaya (Optik)',
                'slug'            => 'mikroskop-cahaya',
                'description'     => "Mikroskop cahaya (light microscope / optical microscope) menggunakan cahaya tampak yang dibiaskan melalui serangkaian lensa kaca untuk memperbesar objek. Ini adalah jenis mikroskop yang paling umum digunakan di laboratorium sekolah.\n\n**Prinsip Kerja:** Cahaya dari sumber (lampu/cermin) melewati kondensor, lalu menembus spesimen transparan/tembus cahaya, kemudian dibiaskan oleh lensa objektif dan diperbesar lebih lanjut oleh lensa okuler, sehingga mata pengamat melihat bayangan yang diperbesar.\n\n**Spesifikasi Teknis:**\n- Pembesaran: 40× hingga 1000× (dengan minyak imersi)\n- Resolusi: ~200 nanometer (0,2 µm)\n- Objek harus tipis dan tembus cahaya\n- Spesimen sering diwarnai (Giemsa, Safranin, Hematoxylin)\n- Dapat mengamati sel hidup yang bergerak\n\n**Kelebihan:** Murah, mudah digunakan, dapat melihat sel hidup\n**Kekurangan:** Resolusi terbatas, tidak bisa melihat struktur sub-seluler dengan jelas",
                'youtube_id'      => null,
                'history_founder' => "Antonie van Leeuwenhoek (1632-1723), pedagang kain Belanda, adalah Bapak Mikrobiologi yang mengembangkan mikroskop cahaya sederhana dengan pembesaran hingga 270×. Ia pertama kali mengamati bakteri, protozoa, dan sel darah merah menggunakan mikroskopnya sendiri. Robert Hooke (1665) mengembangkan mikroskop majemuk dan menerbitkan 'Micrographia', buku pertama tentang observasi mikroskopik yang berpengaruh.",
                'quran_verse'     => "\"Apakah mereka tidak memperhatikan unta, bagaimana ia diciptakan?\" (QS. Al-Ghashiyah: 17). Mikroskop cahaya adalah perpanjangan mata manusia yang memungkinkan kita 'memperhatikan' keajaiban ciptaan Allah di tingkat sel — sebuah alam kecil yang tak pernah terbayangkan sebelumnya oleh manusia.",
            ],
            [
                'type'            => 'microscope',
                'title'           => 'Mikroskop Elektron (EM)',
                'slug'            => 'mikroskop-elektron',
                'description'     => "Mikroskop elektron menggunakan berkas elektron (bukan cahaya) yang dipercepat oleh medan elektromagnetik untuk membentuk bayangan objek. Karena panjang gelombang elektron jauh lebih pendek dari cahaya tampak, resolusinya jauh lebih tinggi.\n\n**Dua Jenis Utama:**\n\n1. **TEM (Transmission Electron Microscope):** Elektron menembus spesimen sangat tipis (50-100 nm). Menghasilkan gambar irisan dalam (bagian dalam sel) dengan detail luar biasa. Pembesaran hingga 1.000.000×, resolusi ~0,1 nm.\n\n2. **SEM (Scanning Electron Microscope):** Berkas elektron memindai permukaan spesimen. Menghasilkan gambar 3D permukaan dengan kedalaman fokus yang besar. Pembesaran hingga 500.000×.\n\n**Kelebihan:** Resolusi dan pembesaran sangat tinggi, dapat melihat organel sub-seluler (ribosom, DNA, membran)\n**Kekurangan:** Mahal, spesimen harus diawetkan (sel mati), dalam ruang vakum, tidak bisa melihat sel hidup",
                'youtube_id'      => null,
                'history_founder' => "Ernst Ruska dan Max Knoll, insinyur Jerman, menciptakan prototipe pertama mikroskop elektron pada tahun 1931. Ernst Ruska akhirnya memenangkan Hadiah Nobel Fisika pada 1986 atas penemuan revolusioner ini — penantian 55 tahun yang mencerminkan betapa lambatnya kadang komunitas ilmiah mengakui kontribusi besar. Mikroskop elektron membuka 'dunia tak kasat mata' yang memungkinkan ilmuwan melihat virus, ribosom, bahkan molekul DNA.",
                'quran_verse'     => "\"Tidakkah mereka memperhatikan apa yang ada di langit dan di bumi, dan apa yang Allah ciptakan?\" (QS. Al-A'raf: 185). Mikroskop elektron adalah instrumen tercanggih yang Allah izinkan manusia ciptakan untuk mengintip keajaiban ciptaan-Nya pada skala atom. Setiap gambar dari SEM/TEM adalah 'ayat kauniyah' yang berbicara tentang keagungan Sang Pencipta.",
            ],
            [
                'type'            => 'microscope',
                'title'           => 'Komponen Mekanik Mikroskop',
                'slug'            => 'komponen-mekanik',
                'description'     => "Komponen mekanik adalah bagian-bagian fisik/struktural mikroskop yang berfungsi untuk menyangga, menggerakkan, dan memposisikan komponen optik serta spesimen.\n\n**Daftar Komponen Mekanik & Fungsinya:**\n\n**1. Kaki (Base):** Tumpuan seluruh badan mikroskop. Berat dan stabil untuk mencegah getaran.\n\n**2. Lengan (Arm):** Penghubung antara kaki dan bagian atas mikroskop. Bagian yang dipegang saat memindahkan mikroskop.\n\n**3. Meja Benda (Stage):** Tempat meletakkan preparat (kaca objek). Dilengkapi penjepit preparat.\n\n**4. Makrometer (Pengatur Kasar):** Knob besar untuk menggerakkan meja benda secara cepat, digunakan saat pertama kali mencari fokus.\n\n**5. Mikrometer (Pengatur Halus):** Knob kecil untuk penyesuaian fokus secara presisi dan halus, digunakan setelah bayangan kasar ditemukan.\n\n**6. Revolver (Nosepiece):** Piringan berputar yang menopang 3-4 lensa objektif dengan pembesaran berbeda. Diputar untuk mengganti perbesaran.\n\n**7. Penjepit Preparat (Stage Clips):** Menjepit kaca objek agar tidak bergeser saat diamati.",
                'youtube_id'      => null,
                'history_founder' => "Pengembangan komponen mekanik mikroskop merupakan kontribusi kolektif banyak pembuat instrumen presisi Eropa selama abad 17-19. Carl Zeiss (1816-1888), pendiri pabrik optik Zeiss di Jena, Jerman, berjasa besar dalam standarisasi dan industrialisasi pembuatan mikroskop berkualitas tinggi. Ernst Abbe, fisikawan yang bekerja untuk Zeiss, mengembangkan teori matematika tentang resolusi optik (kriteria Abbe) pada 1873.",
                'quran_verse'     => "\"Dan Allah mengajarkan kepada Adam nama-nama (benda-benda) seluruhnya\" (QS. Al-Baqarah: 31). Memahami nama dan fungsi setiap komponen mikroskop adalah langkah pertama menuju pengetahuan. Allah memuliakan manusia dengan kemampuan belajar dan mengembangkan teknologi — dan mikroskop adalah salah satu bukti kemuliaan akal manusia yang Allah karuniakan.",
            ],
            [
                'type'            => 'microscope',
                'title'           => 'Komponen Optik Mikroskop',
                'slug'            => 'komponen-optik',
                'description'     => "Komponen optik adalah bagian-bagian yang berkaitan langsung dengan pembentukan dan pembiasan cahaya untuk menghasilkan bayangan yang diperbesar.\n\n**Daftar Komponen Optik & Fungsinya:**\n\n**1. Lensa Okuler (Eyepiece):** Lensa di bagian atas yang langsung bersentuhan dengan mata pengamat. Umumnya berisi pembesaran 10× atau 16×.\n\n**2. Lensa Objektif:** Lensa di bagian bawah (dekat spesimen) yang memberikan pembesaran utama. Tersedia dalam 3-4 perbesaran: 4× (scanning), 10× (low power), 40× (high power), 100× (oil immersion).\n\n**3. Kondensor:** Lensa di bawah meja benda yang mengumpulkan dan memusatkan cahaya ke spesimen. Semakin tinggi kondensor, semakin terang bidang pandang.\n\n**4. Diafragma Iris:** Mekanisme seperti iris mata yang mengatur jumlah cahaya yang melewati kondensor. Digunakan untuk mengoptimalkan kontras gambar.\n\n**5. Reflektor/Cermin:** Pada mikroskop tanpa lampu internal, cermin memantulkan cahaya dari sumber eksternal ke kondensor. Cermin datar untuk cahaya artifisial, cermin cekung untuk cahaya matahari.\n\n**6. Sumber Cahaya (Illuminator):** Lampu halogen atau LED built-in pada mikroskop modern.\n\n**Formula Pembesaran Total:** Pembesaran Okuler × Pembesaran Objektif = Total Pembesaran\n(Contoh: 10× okuler × 40× objektif = 400× total pembesaran)",
                'youtube_id'      => null,
                'history_founder' => "Ernst Abbe (1840-1905) adalah fisikawan jenius yang merumuskan teori matematika tentang batas resolusi optik (Hukum Difraksi Abbe) pada 1873 — menetapkan bahwa resolusi mikroskop cahaya dibatasi oleh panjang gelombang cahaya. Otto Schott mengembangkan jenis kaca optik khusus (kaca Schott) yang meningkatkan kualitas lensa secara dramatis. Ketiganya — Zeiss, Abbe, dan Schott — mendirikan fondasi industri optik presisi modern.",
                'quran_verse'     => "\"Allah adalah cahaya langit dan bumi\" (QS. An-Nur: 35). Komponen optik mikroskop bekerja dengan memanipulasi cahaya — dan Allah menyebut diri-Nya sebagai An-Nur (Yang Maha Bercahaya). Setiap kali kita menggunakan cahaya dalam mikroskop untuk mengungkap keajaiban ciptaan Allah, kita sejatinya sedang menyaksikan tanda-tanda kebesaran-Nya.",
            ],
        ];

        // ================================================================
        // Hotspot koordinat (sesuai array hardcoded lama di show.blade.php)
        // ================================================================
        $hotspotData = [
            // --- SEL HEWAN ---
            'membran-sel-hewan'  => [['left_percent' => '9%',  'top_percent' => '72%', 'bg_color' => '#15803d', 'delay' => '1.2s']],
            'nukleus-hewan'      => [['left_percent' => '49%', 'top_percent' => '40%', 'bg_color' => '#4f46e5', 'delay' => '0s']],
            'mitokondria-hewan'  => [
                ['left_percent' => '22%', 'top_percent' => '33%', 'bg_color' => '#ea580c', 'delay' => '0.4s'],
                ['left_percent' => '77%', 'top_percent' => '65%', 'bg_color' => '#ea580c', 'delay' => '0.6s'],
            ],
            're-kasar-hewan'     => [['left_percent' => '67%', 'top_percent' => '44%', 'bg_color' => '#7c3aed', 'delay' => '0.8s']],
            'golgi-hewan'        => [['left_percent' => '35%', 'top_percent' => '66%', 'bg_color' => '#b45309', 'delay' => '0.2s']],
            'lisosom-hewan'      => [['left_percent' => '65%', 'top_percent' => '70%', 'bg_color' => '#dc2626', 'delay' => '1.0s']],
            // --- SEL TUMBUHAN ---
            'dinding-sel-tumbuhan'   => [['left_percent' => '50%', 'top_percent' => '4%',  'bg_color' => '#92400e', 'delay' => '0.8s']],
            'nukleus-tumbuhan'       => [['left_percent' => '77%', 'top_percent' => '26%', 'bg_color' => '#4f46e5', 'delay' => '0s']],
            'kloroplas-tumbuhan'     => [
                ['left_percent' => '16%', 'top_percent' => '31%', 'bg_color' => '#16a34a', 'delay' => '0.2s'],
                ['left_percent' => '26%', 'top_percent' => '71%', 'bg_color' => '#16a34a', 'delay' => '0.4s'],
                ['left_percent' => '87%', 'top_percent' => '72%', 'bg_color' => '#16a34a', 'delay' => '0.5s'],
            ],
            'vakuola-tumbuhan'       => [['left_percent' => '46%', 'top_percent' => '65%', 'bg_color' => '#0284c7', 'delay' => '0.6s']],
            'mitokondria-tumbuhan'   => [['left_percent' => '83%', 'top_percent' => '59%', 'bg_color' => '#ea580c', 'delay' => '1.0s']],
            'plasmodesmata-tumbuhan' => [['left_percent' => '4%',  'top_percent' => '51%', 'bg_color' => '#0d9488', 'delay' => '1.2s']],
        ];

        // ================================================================
        // Insert / Update semua data materi
        // ================================================================
        $this->command->info('🌱 Seeding data materi dan hotspot...');

        foreach (array_merge($animalOrganelles, $plantOrganelles, $microscopeSections) as $data) {
            $material = Material::updateOrCreate(['slug' => $data['slug']], $data);

            // Seed hotspot jika ada koordinatnya
            if (isset($hotspotData[$data['slug']])) {
                $material->hotspots()->delete(); // reset
                foreach ($hotspotData[$data['slug']] as $hs) {
                    $material->hotspots()->create($hs);
                }
            }
        }

        $this->command->info('✅ MaterialSeeder selesai! '
            . count($animalOrganelles) . ' organel sel hewan, '
            . count($plantOrganelles) . ' organel sel tumbuhan, '
            . count($microscopeSections) . ' bagian teori mikroskop. '
            . 'Hotspot dimuat untuk ' . count($hotspotData) . ' materi.');
    }
}
