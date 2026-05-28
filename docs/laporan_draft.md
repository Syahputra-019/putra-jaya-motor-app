RANCANG BANGUN SISTEM INFORMASI PEMESANAN SERVIS SEPEDA MOTOR PADA BENGKEL PUTRA JAYA MOTOR BERBASIS WEBSITE
TUGAS AKHIR
Diajukan untuk memenuhi sebagian syarat-syarat memperoleh gelar Ahli Madya
Oleh :
SYAHPUTRA TIRTA WIJAYA
233140707111019









PROGRAM STUDI TEKNOLOGI INFORMASI
DEPARTEMEN INDUSTRI KREATIF DAN DIGITAL
FAKULTAS VOKASI
UNIVERSITAS BRAWIJAYA 
MALANG
2024RANCANG BANGUN SISTEM INFORMASI PEMESANAN SERVIS SEPEDA MOTOR PADA BENGKEL PUTRA JAYA MOTOR BERBASIS WEBSITE
Diajukan untuk memenuhi sebagian syarat-syarat memperoleh gelar Ahli Madya
TUGAS AKHR
Oleh :
SYAHPUTRA TIRTA WIJAYA
233140707111019








PROGRAM STUDI TEKNOLOGI INFORMASI
DEPARTEMEN INDUSTRI KREATIF DAN DIGITAL
FAKULTAS VOKASI
UNIVERSITAS BRAWIJAYA 
MALANG
2024
LEMBAR PERSETUJUAN

Judul
:
Rancang Bangun Sistem Informasi Pemesanan Servis Sepeda Motor Pada Bengkel Putra Jaya Motor Berbasis Web
Nama
:
Syahputra Tirta Wijaya
NIM
:
233140707111019
Program Studi
:
Teknologi Informasi





        
Malang, Tanggal
  	Dosen Pembimbing,


Bayu Sutawijaya, S. Kom., M.Kom

LEMBAR PENGESAHAN TUGAS AKHIR

RANCANG BANGUN SISTEM INFORMASI PEMESANAN SERVIS SEPEDA MOTOR PADA BENGKEL PUTRA JAYA MOTOR BERBASIS WEBSITE
Oleh :
Syahputra Tirta Wijaya
233140707111019

Telah dipertahankan di depan Majelis Penguji 
Pada Tanggal (masukkan tanggal sidang) dan dinyatakan memenuhi syarat untuk memperoleh gelar Ahli Madya pada Program Diploma III Teknologi Informasi

Pembimbing



Bayu Sutawijaya, S. Kom ., M. Kom
 				NIK. ………………………….
Ketua Departemen			Ketua Program Studi



Moh.Arief Nazaruddin			Salnan Ratih A., ST.,MT
NIK.2016079009041001		NIK. 2021118803042001

LEMBAR PERNYATAAN TUGAS AKHIR

Saya yang bertanda tangan di bawah ini :
Nama
:
Syahputra Tirta Wijaya
NIM
:
233140707111019
Program Studi
:
Teknologi Informasi
Judul
:
Rancang Bangun Sistem Informasi Pemesanan Servis Sepeda Motor Pada Bengkel Putra Jaya Motor Berbasis Web

Dengan ini saya menyatakan bahwa :
Isi Tugas Akhir yang saya buat adalah benar-benar karya sendiri dan tidak menjiplak karya orang lain, selain nama-nama yang termaktub di isi dan tertulis di daftar pustaka dalam Tugas Akhir ini.
Apabila ditemukan hasil jiplakan, maka saya akan bersedia menanggung segala risiko yang akan saya terima.
Demikian pernyataan ini dibuat dengan segala kesadaran.
  Malang, Tanggal
  Yang Menyatakan

							Materai 10.000

Syahputra Tirta Wijaya
233140707111019

RINGKASAN
Bengkel Putra Jaya Motor saat ini masih mengelola proses pemesanan servis sepeda motor secara manual, seperti melalui pesan singkat atau kedatangan langsung. Hal ini sering mengakibatkan antrean yang tidak teratur, waktu tunggu yang lama bagi pelanggan, serta menyulitkan pihak bengkel dalam manajemen jadwal mekanik. Tujuan dari Tugas Akhir ini adalah merancang dan membangun sebuah Sistem Informasi Pemesanan Servis Sepeda Motor berbasis website untuk mengatasi permasalahan antrean dan pencatatan manual tersebut.
Sistem ini dikembangkan menggunakan bahasa pemrograman PHP dengan framework Laravel yang mengadopsi arsitektur MVC (Model-View-Controller). Antarmuka sistem dibangun menggunakan Tailwind CSS dan Alpine.js, sedangkan pengelolaan basis data menggunakan MySQL. Metode perancangan sistem menggunakan pemodelan Unified Modeling Language (UML) dan basis data dirancang menggunakan Entity Relationship Diagram (ERD). Pengujian fungsionalitas sistem dilakukan menggunakan metode Black Box Testing. Hasil dari pembuatan proyek ini adalah terwujudnya sistem informasi berbasis web yang mempermudah pelanggan dalam melakukan booking servis dari mana saja, serta membantu admin atau pemilik bengkel dalam merekap data pemesanan dan mengatur jadwal kerja mekanik dengan lebih efisien dan terstruktur.

Kata kunci : Sistem Informasi, Pemesanan Servis, Website, Laravel, Bengkel Putra Jaya Motor.

SUMMARY
Putra Jaya Motor Repair Shop currently still manages the motorcycle service booking process manually, such as through short messages or direct arrivals. This often results in unorganized queues, long waiting times for customers, and makes it difficult for the repair shop to manage mechanic schedules. The purpose of this Final Project is to design and build a website-based Motorcycle Service Booking Information System to overcome the problems of queuing and manual recording.
This system was developed using the PHP programming language with the Laravel framework which adopts the MVC (Model-View-Controller) architecture. The system interface was built using Tailwind CSS and Alpine.js, while database management uses MySQL. The system design method uses Unified Modeling Language (UML) modeling and the database is designed using an Entity Relationship Diagram (ERD). System functionality testing is carried out using the Black Box Testing method. The result of this project is a web-based information system that makes it easier for customers to book services from anywhere, and helps the admin or repair shop owner in recording booking data and arranging mechanic work schedules more efficiently and structuredly.

Keyword : Information System, Service Booking, Website, Laravel, Putra Jaya Motor Repair Shop.



KATA PENGANTAR
Assalamu’alaikum Warahmatullahi Wabarakatuh
Segala puji dan syukur atas kehadirat Allah SWT atas segala berkat limpahan rahmat dan hidayah-Nya, nikmat kesehatan dan nikmat berupa ilmu pengetahuan sehingga penulis dapat diberikan kemudahan untuk menyelesaikan tugas akhir ini dengan judul “Rancang Bangun Sistem Informasi Management Berbasis Web pada Kolam Galatama Lele Telaga Maju Bersama” yang merupakan syarat dalam menyelesaikan Pendidikan Ahli Madya pada Program Diploma III Teknologi Informasi, Universitas Brawijaya.
Salam dan shalawat kepada Nabi Muhammad SAW, yang menjadi suri tauladan bagi umat manusia. Semoga apa yang penulis lakukan dalam penulisan tugas akhir ini dapat bernilai ibadah. Dalam penulisan tugas akhir ini tentunya masih banyak kekurangan dan jauh dari kata sempurna. Maka dalam hal ini penulis berharap adanya saran dan kritik sehingga dapat menjadi tulisan yang lebih baik dari sebelumnya.
Pada kesempatan ini, penulis dengan kerendahan hati menyampaikan ucapan terima kasih sebesar-besarnya kepada seluruh pihak yang telah memberikan dukungannya selama proses penyusunan tugas akhir. Maka penulis menyampaikan rasa terima kasih kepada :
Bapak Prof. Widodo, S.Si, M.Si, Ph.D. selaku rektor Universitas Brawijaya
Bapak Mukhammad Kholid Mawardi, S.Sos., M.AB., Ph.D. selaku Dekan Fakultas Vokasi Universitas Brawijaya.
Ibu Salnan Ratih Asriningtias, ST.,MT, MCF selaku Ketua Program Studi Diploma III Teknologi Informasi.
Bapak Bayu Sutawijaya, S.Kom., M.Kom selaku Dosen Pembimbing yang telah memberikan banyak arahan, waktu, dan ilmu yang sangat berharga dalam penyusunan Tugas Akhir ini.
Seluruh Bapak dan Ibu Dosen Program Studi Teknologi Informasi yang telah memberikan bekal ilmu pengetahuan dan pengalaman selama masa perkuliahan.
Seluruh Staf Akademik dan Staf Perpustakaan yang telah banyak membantu penulis dalam melayani proses administrasi dan bantuan lainnya selama melaksanakan kuliah di Fakultas Vokasi, Universitas Brawijaya.

DAFTAR ISI 

DAFTAR TABEL
Tabel 2.1 Simbol-simbol usecase diagram 13



DAFTAR GAMBAR
Gambar 4.50 Tampilan halaman testimoni102 

DAFTAR LAMPIRAN
Lampiran 1. Panduan Pengguna Sistem Pemesanan Tiket Rollab Berbasis Website 118


BAB I
 PENDAHULUAN

Latar Belakang
Perkembangan teknologi informasi saat ini telah memberikan kemudahan bagi masyarakat dalam berbagai aspek operasional, termasuk di bidang pelayanan jasa bengkel. Pemesanan atau booking servis kendaraan secara terjadwal menjadi sangat penting untuk mengatur ritme waktu operasional harian, menghindari penumpukan antrian yang panjang, dan memberikan kepastian layanan bagi pelanggan. Sistem booking yang dikelola dengan baik dapat meningkatkan efisiensi kerja mekanik sekaligus meningkatkan kepuasan pelanggan secara keseluruhan.
Bengkel Putra Jaya Motor merupakan salah satu penyedia layanan jasa servis sepeda motor yang memiliki jumlah pelanggan yang cukup tinggi. Namun, dalam kegiatan operasionalnya saat ini, proses pemesanan atau booking servis sepeda motor masih dilakukan secara manual seperti melalui pesan singkat atau datang langsung ke lokasi, yang seringkali tidak terdata dengan sistematis. Pelanggan kerap kali kesulitan untuk mengetahui informasi ketersediaan slot waktu servis yang kosong, sehingga terpaksa harus datang langsung dan mengalami antrean yang panjang. Selain itu, sistem pencatatan manual ini juga rentan terhadap kesalahan pendataan jadwal dan menyulitkan pihak bengkel dalam manajemen pekerjaan harian mekanik.
Jika permasalahan ini dibiarkan terus-menerus, hal ini dapat berdampak negatif terhadap tingkat pelayanan operasional Bengkel Putra Jaya Motor. Antrean pelanggan yang tidak teratur dan waktu tunggu servis yang lama akan menurunkan tingkat kepercayaan dan kepuasan pelanggan, yang berpotensi hilangnya pelanggan setia bengkel. Di sisi lain, pihak manajemen bengkel juga akan kesulitan dalam mengevaluasi efisiensi kerja dan merencanakan kebutuhan suku cadang maupun alokasi tenaga kerja secara optimal setiap harinya.
Untuk mengatasi permasalahan tersebut, diperlukan adanya penerapan suatu sistem informasi manajemen pemesanan terkomputerisasi yang terintegrasi. Menurut penelitian terdahulu yang dilakukan oleh (Ardiansyah, 2023), penerapan sistem informasi penjadwalan dan booking servis kenderaan bermotor berasaskan teknologi maklumat terbukti mampu memudahkan pelanggan dalam melakukan pemesanan waktu servis tanpa perlu datang dan mengantri lama di lokasi. Selain itu, sistem ini juga sangat membantu pihak pentadbir (admin) bengkel dalam menguruskan jadual mekanik secara lebih bersistematik dan teratur. 
Salah satu platform sistem informasi yang dapat digunakan untuk mengatasi permasalahan di atas adalah berbasis website. Platform website sangat ideal karena memiliki keunggulan dalam hal fleksibilitas dan aksesibilitas yang tinggi. Melalui platform ini, pelanggan dapat dengan mudah mengakses layanan pemesanan melalui browser dari berbagai perangkat, baik smartphone maupun komputer, tanpa perlu mengunduh atau menginstal aplikasi tambahan. 
Solusi yang ditawarkan untuk menyelesaikan masalah ini adalah merancang dan membangun sebuah Sistem Informasi Pemesanan Servis Sepeda Motor berbasis website. Pemilihan platform website didasarkan pada keunggulannya dalam hal aksesibilitas, di mana pelanggan dapat dengan mudah mengakses sistem melalui browser dari berbagai perangkat tanpa perlu menginstal aplikasi tambahan. Sistem ini akan dirancang dengan fitur-fitur seperti melihat ketersediaan jadwal mekanik, formulir pemesanan (booking) waktu servis, serta konfirmasi status pemesanan secara langsung. Manfaat dan kelebihan dari sistem ini adalah mempermudah pelanggan dalam melakukan booking dari mana saja, mengurangi penumpukan fisik di bengkel, serta membantu pengelola bengkel dalam merekap data pemesanan secara lebih cepat, tepat, dan efisien.
Berdasarkan uraian permasalahan dan solusi di atas, maka penelitian ini mengambil judul Rancang Bangun Sistem Informasi Pemesanan Servis Sepeda Motor Pada Bengkel Putra Jaya Motor Berbasis Website. Sistem ini akan dirancang dengan beberapa fitur utama, di antaranya fitur manajemen jadwal mekanik, formulir pemesanan (booking) servis secara online, fitur tracking atau pemantauan progres pengerjaan servis secara real-time, serta fitur kasir dan manajemen transaksi pembayaran. Melalui kelengkapan fitur tersebut, diharapkan aplikasi ini mampu memberikan kemudahan bagi pelanggan dalam melakukan booking tanpa harus mengantre lama, serta membantu pihak pengelola Bengkel Putra Jaya Motor dalam meningkatkan efisiensi operasional, pengelolaan data servis, dan kualitas pelayanan secara keseluruhan. 
1.2 Rumusan Masalah
Bagaimana merancang Sistem Informasi Pemesanan Servis Sepeda Motor pada Bengkel Putra Jaya Motor berbasis website?
Bagaimana membangun Sistem Informasi Pemesanan Servis Sepeda Motor pada Bengkel Putra Jaya Motor berbasis website?
Bagaimana melakukan pengujian Sistem Informasi Pemesanan Servis Sepeda Motor pada Bengkel Putra Jaya Motor berbasis website? 
        
1.3 Tujuan 
Merancang Sistem Informasi Pemesanan Servis Sepeda Motor pada Bengkel Putra Jaya Motor berbasis website.
Membangun Sistem Informasi Pemesanan Servis Sepeda Motor pada Bengkel Putra Jaya Motor berbasis website.
Melakukan pengujian Sistem Informasi Pemesanan Servis Sepeda Motor pada Bengkel Putra Jaya Motor berbasis website.
1.4 Manfaat 
Bagi Pelanggan
Pelanggan menjadi lebih mudah dalam melakukan pemesanan jadwal servis kapan pun dan di mana pun, serta tidak perlu lagi mengantri lama secara fisik di bengkel.
Bagi Pihak Bengkel (Admin/Pemilik)
Pihak bengkel lebih mudah dalam mengelola dan merekap data pemesanan secara harian, serta mengatur pembagian jadwal kerja mekanik dengan lebih terstruktur.
Bagi Penulis
Sebagai sarana untuk mengaplikasikan ilmu pengembangan perangkat lunak berbasis web yang telah dipelajari selama masa perkuliahan ke dalam studi kasus nyata di dunia industri.

1.5 Batasan Masalah 
Proses pembayaran biaya servis tidak dilakukan di dalam sistem ini, melainkan dibayarkan secara langsung (tunai/non-tunai) di bengkel setelah proses servis selesai.
Sistem difokuskan pada pelayanan servis rutin dan ketersediaan slot jadwal mekanik, tidak mencakup modul penggajian atau akuntansi keuangan yang kompleks.



BAB II
KAJIAN PUSTAKA
Konsep Dasar dan Objek Penelitian 
2.1.1	Sistem Informasi
Sistem informasi adalah gabungan antara prosedur kerja, informasi, orang, dan teknologi informasi yang diorganisasikan secara terpadu untuk mencapai tujuan tertentu di dalam sebuah organisasi. Menurut Wahyuddin et al. (2023), sistem informasi tidak hanya berfokus pada pengumpulan data, tetapi juga menyaring, mengatur, dan memberikan informasi yang relevan kepada pemangku kepentingan untuk mendukung proses pengambilan keputusan operasional maupun manajerial. Keberadaan sistem informasi yang terkomputerisasi pada suatu bisnis jasa, seperti bengkel, sangat krusial guna memastikan transparansi dan kecepatan pelayanan. 
2.1.2 Pemesanan (Booking) Servis
Pemesanan atau booking merupakan proses kesepakatan awal untuk memesan waktu dan tempat guna mendapatkan suatu layanan jasa. Menurut Xaverius et al. (2022), sistem informasi booking servis motor adalah solusi inovatif yang memudahkan pengguna untuk melakukan reservasi layanan perawatan kendaraan tanpa harus mengantre secara fisik di lokasi. Sejalan dengan hal tersebut, Akbar et al. (2023) dalam penelitiannya menegaskan bahwa penerapan sistem booking terbukti mempermudah pengelola bengkel dalam mengatur jadwal mekanik dan ketersediaan suku cadang, sekaligus menyelamatkan pelanggan dari waktu tunggu yang terbuang sia-sia.
2.1.3 Website
Website atau situs web adalah kumpulan halaman web yang saling terkait, dapat diakses melalui internet menggunakan peramban (browser), dan berada di bawah satu nama domain. Menurut Pratama dan Hidayat (2024), kegunaan website dalam konteks sistem informasi adalah sebagai media antarmuka yang memungkinkan pengguna berinteraksi dengan basis data sistem dari mana saja. Kelebihan utama dari sistem berbasis website adalah fleksibilitasnya yang tinggi, tidak memerlukan instalasi perangkat lunak khusus pada perangkat klien, dan kompatibel dengan berbagai sistem operasi (Rahmawati et al., 2025). 
Bahasa Pemrograman dan Teknologi Web
2.2.1 HTML
HTML (Hypertext Markup Language) merupakan bahasa markah standar yang digunakan untuk membangun struktur dasar dan kerangka dari sebuah halaman web. Menurut Rahmawati et al. (2022), HTML berfungsi sebagai fondasi utama yang mendeskripsikan letak dan struktur konten teks maupun media pada sebuah situs.
2.2.2 CSS
CSS (Cascading Style Sheets) merupakan bahasa yang digunakan untuk mengatur tata letak, warna, tipografi, dan elemen visual lainnya. Menurut Fadilah dan Nofitri (2023), penggunaan CSS sangat penting agar tampilan antarmuka web menjadi lebih menarik, terstruktur, dan responsif ketika diakses melalui berbagai ukuran layar perangkat.
2.2.3 JavaScript
JavaScript merupakan bahasa pemrograman tingkat tinggi yang berjalan pada sisi klien (client-side). Berdasarkan penjelasan Kurniawan dan Syahputra (2024), JavaScript memungkinkan halaman web menjadi jauh lebih interaktif, seperti memberikan validasi formulir secara real-time maupun memproses animasi antarmuka tanpa harus memuat ulang (reload) halaman secara penuh.
2.2.4 PHP
PHP (Hypertext Preprocessor) adalah bahasa pemrograman skrip (scripting language) yang berjalan pada sisi peladen (server-side) dan dirancang khusus untuk pengembangan web dinamis. Menurut Setiawan dan Purnomo (2023), PHP memiliki keunggulan yang kuat dalam berinteraksi dengan berbagai sistem manajemen basis data, mengelola sesi pengguna, serta memproses data yang dikirimkan melalui formulir web. Penggunaan PHP menjadi fondasi utama berjalannya kerangka kerja Laravel dalam sistem informasi bengkel ini.
2.3 Kerangka Kerja (Framework) dan Library 
2.3.1 Framework Laravel
Laravel adalah salah satu kerangka kerja (framework) bahasa pemrograman PHP yang bersifat open-source dan menggunakan arsitektur MVC (Model-View-Controller). Berdasarkan penelitian dari Pratama & Susanto (2023), Laravel menjadi pilihan utama dalam pengembangan website modern karena memiliki tingkat keamanan yang tinggi, struktur direktori yang rapi, serta fitur bawaan seperti Eloquent ORM yang sangat memudahkan pengembang dalam memanipulasi data tanpa harus menulis query SQL secara manual. 
2.3.2 Konsep Arsitektur MVC (Model-View-Controller)
Dalam struktur pengembangannya, Laravel mengadopsi pola arsitektur perangkat lunak yang dikenal dengan nama MVC (Model-View-Controller). Menurut Ramadhani dan Widaningsih (2022), konsep MVC bertujuan untuk memisahkan antara logika penanganan data, antarmuka pengguna, dan alur kontrol aplikasi agar proses pengembangan menjadi lebih terstruktur. Pembagian tugas dalam arsitektur MVC adalah sebagai berikut:
Model: Merupakan komponen yang merepresentasikan struktur data dari aplikasi. Model bertanggung jawab penuh atas interaksi sistem dengan basis data, seperti proses menambah (Create), membaca (Read), mengubah (Update), dan menghapus (Delete) data. Dalam Laravel, pengelolaan Model sangat dimudahkan oleh kehadiran fitur Eloquent ORM (Pratama & Susanto, 2023).
View: Merupakan komponen yang berhubungan langsung dengan antarmuka pengguna (User Interface). View bertugas untuk menampilkan informasi ke layar pengguna akhir secara visual. Pada Laravel, pembuatan antarmuka ini didukung oleh template engine bawaan bernama Blade yang dinamis dan fleksibel.
Controller: Merupakan komponen yang bertindak sebagai jembatan penghubung antara Model dan View. Controller bertugas menerima masukan (request) dari pengguna, memproses logika bisnis atau aturan sistem, mengambil data yang diperlukan dari Model, dan kemudian mengirimkan data tersebut ke View untuk ditampilkan kepada pengguna.
Pemilihan arsitektur MVC membuat pengembangan sistem informasi menjadi lebih terorganisasi. Pemisahan kode (separation of concerns) ini memungkinkan pengembang untuk memodifikasi tampilan (frontend) tanpa harus mengganggu logika bisnis di sisi peladen (backend), sehingga proses pemeliharaan sistem di masa mendatang menjadi lebih efisien (Halim et al., 2023).
2.3.3 Tailwind CSS
Tailwind CSS adalah sebuah kerangka kerja CSS berbasis utilitas (utility-first). Menurut Mahendra dan Wijaya (2023), berbeda dengan kerangka kerja CSS tradisional yang menyediakan komponen desain siap pakai, Tailwind berfokus pada penyediaan kelas-kelas utilitas tingkat rendah yang memungkinkan pengembang untuk membangun desain antarmuka kustom secara langsung di dalam berkas HTML. Pendekatan ini terbukti mampu mempercepat proses penataan gaya (styling) dan mengurangi ukuran berkas CSS secara keseluruhan pada sistem bengkel, sehingga berdampak positif pada kecepatan muat (loading) halaman (Yulianto et al., 2024).
2.3.4 Alpine.js
Alpine.js adalah sebuah pustaka (library) JavaScript ringan (lightweight) yang menawarkan fungsionalitas reaktivitas layaknya kerangka kerja besar (seperti Vue atau React) namun dengan ukuran yang jauh lebih kecil. Berdasarkan penelitian Nugroho dan Santoso (2024), Alpine.js sangat ideal digunakan pada aplikasi modern karena mampu menangani manipulasi Document Object Model (DOM) tanpa membebani kinerja peramban (browser). Dalam pengembangan antarmuka sistem informasi bengkel ini, Alpine.js difungsikan untuk menangani interaksi elemen antarmuka yang dinamis secara efisien, seperti menu dropdown, modal, dan tab, tanpa perlu menulis banyak baris kode JavaScript eksternal. 
2.4 Basis Data
2.4.1 Pengertian Basis Data (Database)
Basis data (database) merupakan sekumpulan informasi yang saling berelasi, terorganisasi, dan disimpan secara terpusat di dalam media penyimpanan elektronik sehingga dapat diakses, dikelola, dan diperbarui dengan mudah (Hidayat & Mulyani, 2024). Dalam konteks sistem informasi bengkel, basis data berfungsi sebagai media penyimpanan yang krusial untuk mencatat rekam jejak pelanggan, ketersediaan inventaris suku cadang, hingga riwayat transaksi pemesanan servis secara sistematis (Saputra et al., 2023).
2.4.2 Entity Relationship Diagram (ERD)

(Gambar 2.5 ERD)
Sumber: Belajar Studi. (2017).
Entity Relationship Diagram (ERD) adalah sebuah model data yang menggunakan notasi grafis untuk menggambarkan hubungan antar entitas di dalam sebuah basis data relasional. Menurut Susanti et al. (2024), ERD berguna untuk memodelkan struktur logis basis data sehingga pengembang dapat dengan mudah melihat relasi antar tabel sebelum diimplementasikan ke dalam MySQL.
2.4.3 MySQL
MySQL adalah sebuah Sistem Manajemen Basis Data Relasional (Relational Database Management System / RDBMS) bersifat open-source yang beroperasi menggunakan bahasa standar SQL (Structured Query Language). Menurut Wijaya dan Susanto (2025), MySQL sangat populer dalam pengembangan sistem informasi karena keandalannya dalam menangani volume data yang besar, kecepatan pemrosesan kueri yang tinggi, serta tingkat keamanan yang mumpuni. Pada pengembangan sistem bengkel ini, MySQL diimplementasikan untuk menyimpan tabel-tabel data master dan transaksi secara terstruktur dengan memanfaatkan relasi antar tabel, seperti penggunaan kunci tamu (Foreign Key), guna memastikan integritas dan konsistensi data (Susanti et al., 2024).
2.5 Metode dan Perancangan Sistem 
2.5.1 Metode Pengembangan Perangkat Lunak (Waterfall)
Penelitian ini menggunakan metode pengembangan perangkat lunak model Waterfall. Menurut Meilinaeka (2023), metode Waterfall adalah pendekatan pengembangan sistem yang bersifat sistematis dan sekuensial (berurutan) yang alurnya mengalir ke bawah seperti air terjun. Penggunaan metode ini sangat efektif untuk perancangan sistem informasi yang kebutuhan fungsionalitas awalnya sudah didefinisikan dengan sangat jelas sejak awal (Mustakim et al., 2024).
Tahapan-tahapan dalam metode Waterfall meliputi:
Analisis Kebutuhan (Requirement Analysis): Tahap pengumpulan kebutuhan perangkat lunak melalui observasi dan wawancara untuk menentukan fitur-fitur yang dibutuhkan, seperti fitur booking dan pemantauan (tracking) progres servis.
Desain Sistem (System Design): Penerjemahan syarat kebutuhan ke dalam bentuk perancangan sistem, termasuk perancangan antarmuka pengguna (User Interface) dan arsitektur basis data.
Implementasi (Implementation): Tahap penulisan kode program (coding) untuk mengubah desain menjadi aplikasi yang nyata. Pada tahap ini, pengembang menggunakan bahasa pemrograman PHP dan framework Laravel.
Pengujian (Testing): Program yang telah selesai dibuat akan diuji menggunakan metode Black Box Testing untuk memastikan setiap unit fungsionalitas berjalan dengan baik dan bebas dari kesalahan (bug).
Pemeliharaan (Maintenance): Tahap akhir berupa pengoperasian sistem oleh pengguna serta perbaikan jika ditemukan kendala pada saat sistem diimplementasikan di lingkungan nyata.
2.5.2 Unified Modeling Language (UML)
Dalam pengembangan sistem dengan pola MVC, perancangan direpresentasikan menggunakan UML. UML merupakan bahasa standar untuk menspesifikasikan, memvisualisasikan, dan mendokumentasikan sistem perangkat lunak (Kurniawan & Saputra, 2024). Beberapa diagram UML yang digunakan beserta simbolnya adalah sebagai berikut:
1. Use Case Diagram 

(Gambar 2.1 Use Case Diagram)
Sumber: Dumet School. (2015). Mengenal Use Case Diagram.
Use Case Diagram menggambarkan interaksi antara aktor (pengguna) dengan sistem. Diagram ini berguna untuk mendefinisikan fungsionalitas sistem dan hak akses (Yulianti et al., 2025).
2. Activity Diagram 

(Gambar 2.2 Activity Diagram)
Sumber: Dicoding. (2021). Apa itu Activity Diagram.
Menggambarkan aliran kerja (workflow) atau aktivitas berurutan dari sebuah sistem. Kegunaannya adalah untuk memperlihatkan logika proses dari awal hingga akhir (Nugraha & Wibowo, 2026).
3. Sequence Diagram 

(Gambar 2.3 Sequence Diagram)
Sumber: Andrawati, P. (2018). Sequence Diagram 
Menggambarkan interaksi antar objek di dalam dan di sekitar sistem (termasuk pengguna, view, controller, dan basis data) berupa pengiriman pesan terhadap waktu. Diagram ini berguna untuk melihat detail teknis alur logika sistem (Wijaya & Susanti, 2024).
4. Class Diagram 

(Gambar 2.4 Class Diagram)
Sumber: Kantinit. (2016).
Menggambarkan struktur sistem dari segi pendefinisian kelas-kelas yang akan dibuat untuk membangun sistem, termasuk atribut, operasi, dan relasinya (Santoso et al., 2025). Diagram ini sangat berguna dalam arsitektur MVC untuk merancang Model dan basis data.
2.6 Perangkat Lunak Pendukung (Tools)
2.6.1 XAMPP
Merupakan perangkat lunak kompilasi yang mengintegrasikan peladen web Apache, modul interpreter PHP, dan sistem basis data MariaDB/MySQL. Menurut Riyanto dan Hidayat (2023), XAMPP sangat ideal digunakan sebagai peladen lokal (localhost) untuk menjalankan, merancang, dan menguji coba aplikasi berbasis web secara offline selama tahap pengembangan sebelum sistem diimplementasikan ke peladen publik (hosting).
2.6.2 Visual Studio Code (VS Code)
Merupakan penyunting kode sumber (source code editor) ringan dan tangguh yang dikembangkan oleh Microsoft. Berdasarkan pemaparan Syahputra dan Wibowo (2024), VS Code menjadi pilihan utama para pengembang perangkat lunak karena menyediakan fitur penyelesaian kode otomatis (IntelliSense), integrasi terminal bawaan, serta dukungan ekstensi yang sangat luas. Fitur-fitur tersebut terbukti mampu mempermudah proses penulisan kode dan mempercepat penelusuran kesalahan (debugging) pada program secara efisien (Gunawan et al., 2025).
2.6.3 Draw.io
Draw.io atau Diagrams.net adalah perangkat lunak berbasis web dan desktop yang berfungsi sebagai editor grafik vektor untuk membuat berbagai jenis diagram. Menurut Ramadhan dan Lestari (2025), perangkat lunak ini digunakan untuk menggambar perancangan sistem seperti UML dan ERD. Kelebihan Draw.io adalah sifatnya yang gratis (open-source), menyediakan palet simbol UML yang sangat lengkap, serta memiliki fitur integrasi langsung dengan layanan penyimpanan awan (cloud storage) seperti Google Drive, sehingga memudahkan kolaborasi dan penyimpanan dokumen perancangan secara aman.
2.7 Pengujian Sistem
2.7.1 Black Box Testing
Pengujian merupakan tahapan krusial dalam siklus hidup pengembangan perangkat lunak untuk memastikan bahwa sistem yang dibangun telah memenuhi spesifikasi kebutuhan. Pengujian pada sistem informasi bengkel ini menggunakan metode Black Box Testing.
Menurut Lestari dan Hidayat (2025), Black Box Testing adalah metode pengujian perangkat lunak yang berfokus pada spesifikasi fungsional dari sebuah aplikasi tanpa perlu mengetahui struktur internal atau kode program yang ada di baliknya. Pengujian ini dilakukan dengan cara memberikan berbagai kondisi masukan (input) untuk melihat apakah sistem menghasilkan keluaran (output) yang sesuai dengan yang diharapkan (Ramadhan et al., 2026). Penggunaan metode ini dinilai paling efektif untuk menemukan kesalahan antarmuka, kegagalan inisialisasi sistem, maupun kesalahan dalam mengakses fungsi basis data sebelum aplikasi dirilis ke publik.
2.8 Teknologi Pendukung Tambahan 
2.8.1 Payment Gateway dan Midtrans
Payment gateway merupakan sebuah sistem layanan e-niaga yang mengotorisasi proses pembayaran untuk bisnis elektronik, peritel online, hingga bisnis konvensional. Menurut Pratama dan Susanto (2024), layanan ini bertindak sebagai perantara yang aman antara aplikasi website milik pedagang dan berbagai institusi keuangan.
Dalam pengembangan sistem informasi pemesanan servis ini, diimplementasikan layanan payment gateway dari Midtrans. Midtrans adalah salah satu penyedia layanan gerbang pembayaran terkemuka di Indonesia yang memfasilitasi berbagai metode pembayaran digital, seperti transfer bank (Virtual Account), dompet digital (e-Wallet), hingga QRIS. Berdasarkan penelitian Setiawan et al. (2025), integrasi Midtrans menggunakan Application Programming Interface (API) terbukti sangat efektif dalam mempercepat proses verifikasi transaksi secara otomatis, transparan, dan terjamin keamanannya tanpa perlu konfirmasi manual dari pihak admin.
2.8.2 Ngrok (Local Tunneling)
Ngrok adalah sebuah aplikasi perangkat lunak yang menyediakan layanan tunneling aman dari jaringan internet publik ke peladen lokal (localhost) yang berada di balik NAT (Network Address Translation) atau dinding api (firewall). Menurut Mahendra dan Saputra (2025), Ngrok memungkinkan pengembang untuk mengekspos peladen pengembangan lokal mereka ke internet tanpa harus melakukan konfigurasi port forwarding pada router.
Dalam konteks pengembangan sistem bengkel ini, Ngrok digunakan secara spesifik untuk tahap pengujian (testing) integrasi pembayaran Midtrans. Karena sistem masih berjalan di peladen lokal (XAMPP), Ngrok bertugas menyediakan URL publik sementara agar webhook (notifikasi transaksi berhasil/gagal) dari server Midtrans dapat ditangkap dan diproses secara real-time oleh sistem aplikasi lokal (Wahyudi & Hidayat, 2024).
2.8.3 Sistem Pelacakan (Tracking System)
Sistem pelacakan atau tracking system adalah sebuah mekanisme perangkat lunak yang dirancang untuk memantau dan melaporkan status, posisi, atau progres dari suatu entitas (barang, dokumen, atau layanan) secara berkala. Menurut Wijaya dan Pratama (2025), penerapan fitur tracking pada sistem informasi pemesanan layanan jasa sangat penting untuk meningkatkan transparansi antara penyedia jasa dan pelanggan. Dalam sistem bengkel ini, fitur tracking diimplementasikan dalam bentuk visualisasi langkah demi langkah (stepper) untuk memberikan informasi terkini secara real-time kepada pelanggan mengenai sejauh mana progres perbaikan kendaraan mereka dikerjakan oleh mekanik.
2.8.4 Role-Based Access Control (RBAC)
Role-Based Access Control (RBAC) adalah sebuah mekanisme keamanan sistem informasi yang membatasi aksesibilitas pengguna berdasarkan peran (role) atau tingkat wewenang yang mereka miliki di dalam organisasi. Berdasarkan penelitian Santoso et al. (2026), RBAC memungkinkan pengelola sistem untuk memisahkan antarmuka, fitur, dan data yang dapat diakses oleh masing-masing tipe pengguna. Pada sistem informasi bengkel ini, RBAC diimplementasikan untuk memisahkan hak akses antara Pelanggan (yang hanya dapat melakukan pemesanan dan melihat progres servis), Mekanik (yang mengelola status pengerjaan), dan Admin (yang memiliki hak penuh untuk mengelola master data, jadwal, dan laporan keuangan).

BAB III
METODE PENYELESAIAN TUGAS AKHIR

3.1 Tempat dan Waktu Penelitian 
Tempat Penelitian
Penelitian dan proses pengambilan data dalam penyusunan Tugas Akhir ini dilaksanakan di bengkel mitra, yaitu Putra Jaya Motor, yang berlokasi di Jl. Ikan Gurami No.14, Tunjungsekar, Kec. Lowokwaru, Kota Malang, Jawa Timur 65142. Pemilihan lokasi ini didasarkan pada kebutuhan mitra akan sistem informasi yang mampu mendigitalisasi proses bisnis mereka, seperti manajemen antrean servis, pencatatan transaksi, integrasi pembayaran digital, serta fitur pelacakan status kendaraan oleh pelanggan. Seluruh proses observasi, wawancara dengan pihak bengkel, hingga uji coba implementasi sistem dilakukan pada lokasi tersebut.
Waktu Penelitian
Pelaksanaan penelitian Tugas Akhir ini, yang mencakup tahap observasi awal, identifikasi masalah, pengumpulan data, perancangan perangkat lunak, hingga tahap pengujian dan implementasi sistem, dilaksanakan dalam kurun waktu kurang lebih selama 4 bulan.
Tabel 3.1 Jadwal Kegiatan Penelitian 

No 
Kegiatan 
Mar 
Apr 
Mei
Jun 
1
Observasi & Wawancara (Putra Jaya Motor) 
✅






2
Analisis Kebutuhan & Identifikasi Masalah 
✅
✅





3
Perancangan Sistem & Database (UML) 


✅
✅


4
Pembuatan Aplikasi (Coding & Midtrans) 


✅
✅


5
Testing (Black Box) & Perbaikan Bug 








6
Implementasi & Penyusunan Laporan TA 










3.2 Sistematika Perancangan
Dalam menyelesaikan pengembangan sistem informasi pada Bengkel Putra Jaya Motor, metode perancangan yang digunakan adalah metode Waterfall. Menurut Weriza dkk. (2025), metode pengembangan Waterfall dilakukan secara bertahap, di mana setiap tahapan (mulai dari analisis hingga implementasi) harus diselesaikan sepenuhnya sebelum melangkah ke tahapan berikutnya. Pendekatan yang sistematis dan sekuensial ini memastikan setiap proses terekam dengan baik. Adapun langkah-langkah dalam sistematika perancangan ini adalah sebagai berikut:
3.2.1. Analisa Permasalahan 
Tahap ini bertujuan untuk memahami kendala yang dihadapi oleh Bengkel Putra Jaya Motor. Langkah-langkah yang dilakukan antara lain:
Identifikasi Masalah: Menganalisis kelemahan sistem manual, seperti antrean yang tidak teratur, sulitnya pelanggan memantau status servis, dan proses pembayaran yang konvensional.
Wawancara & Observasi: Melakukan pengumpulan data primer dengan cara tanya jawab dan pengamatan langsung terhadap alur kerja bengkel.
Studi Literatur: Mencari referensi jurnal terbaru mengenai penggunaan Framework Laravel dan integrasi API Midtrans.
3.2.2. Perencanaan Aplikasi 
Tahap ini merupakan proses pemodelan sistem sebelum dilakukan pengodean. Pemodelan sistem dilakukan menggunakan standar Unified Modeling Language (UML). Menurut Dewi dan Yulina (2026), UML adalah suatu bahasa visual standar yang berfungsi sebagai teknik pengembangan sistem, mulai dari mendefinisikan kebutuhan, melakukan analisis, hingga merancang desain sistem secara terstruktur. Langkah-langkah yang dilakukan antara lain: 
Perancangan Use-Case Diagram: Menentukan aktor (Admin, Mekanik, dan Pelanggan) serta batasan hak akses masing-masing dalam sistem.
Perancangan Use-Case Scenario: Menyusun alur detail setiap interaksi aktor terhadap sistem.
Perancangan Sequence Diagram: Menggambarkan urutan perilaku objek dalam sistem secara kronologis untuk setiap fitur utama.
Perancangan Class Diagram: Mendefinisikan struktur kelas, atribut, dan hubungan antar tabel.
Perancangan Database: Merancang Entity Relationship Diagram (ERD) dan struktur tabel (users, pelanggan, bookings) beserta primary key masing-masing.
3.2.3. Implementasi 
Pada tahap ini, rancangan UML dan database diubah menjadi bahasa pemrograman. Langkah-langkahnya meliputi:
Pembuatan Database: Mengimplementasikan rancangan ERD ke dalam MySQL menggunakan fitur Migration pada Laravel.
Coding & Integrasi: Membangun antarmuka dengan Tailwind CSS, memprogram logika back-end menggunakan PHP (Laravel), serta mengintegrasikan Payment Gateway Midtrans.
3.2.4. Uji Coba Aplikasi 
Sistem yang telah dibangun kemudian diuji untuk memastikan kualitas dan fungsinya. Metode pengujian yang digunakan adalah Black Box Testing. Menurut Rohendi dan Setiawati (2026), Black Box Testing merupakan metode pengujian yang berfokus pada fungsionalitas sistem tanpa memperhatikan struktur kode program di dalamnya. Pengujian pada sistem ini mencakup validasi form, logika stepper status, dan callback pembayaran. 
3.2.5. Pengaplikasian dan Perawatan 
Setelah sistem dinyatakan bebas bug, tahap terakhir adalah:
Pengisian Konten: Menginput data awal bengkel seperti mekanik, layanan, dan sparepart.
Backup Data: Melakukan pencadangan database berkala.
Maintenance: Melakukan pemeliharaan server dan penyesuaian fitur jika dibutuhkan pada masa mendatang.
3.3 Teknik Pengambilan Data
Teknik pengambilan data dilakukan untuk memperoleh informasi yang akurat dan relevan guna mendukung pengembangan sistem. Adapun teknik yang digunakan dalam penelitian ini adalah:
3.3.1. Observasi 
Menurut Weriza dkk. (2025), observasi adalah teknik pengumpulan data dengan cara melakukan pengamatan langsung terhadap objek penelitian untuk memahami fenomena yang terjadi. Penulis melakukan observasi langsung pada Bengkel Putra Jaya Motor untuk melihat proses bisnis yang berjalan, mulai dari pelanggan datang hingga kendaraan selesai diperbaiki. Berdasarkan hasil observasi tersebut, ditemukan beberapa permasalahan utama, yaitu:
Pencatatan Masih Manual: Seluruh data servis dan antrean masih dicatat menggunakan buku besar, sehingga berisiko terjadi kehilangan data atau duplikasi.
Kurangnya Transparansi Status Servis: Pelanggan tidak memiliki akses untuk mengetahui progres pengerjaan kendaraan mereka secara real-time, sehingga pelanggan seringkali harus bertanya berulang kali kepada admin atau mekanik.
Pengelolaan Transaksi: Proses pembayaran masih terbatas pada tunai atau transfer manual yang sulit untuk direkapitulasi secara otomatis di akhir bulan.
3.3.2. Wawancara 
Menurut Dewi dan Yulina (2026), wawancara merupakan metode tanya jawab secara lisan untuk memperoleh informasi mendalam dari narasumber terkait kebutuhan sistem. Penulis melakukan wawancara dengan pemilik sekaligus pengelola Bengkel Putra Jaya Motor mengenai kebutuhan pengembangan sistem. Dari hasil wawancara tersebut, diperoleh poin-poin kebutuhan fitur utama sebagai berikut:
Fitur Tracking Status (Stepper): Perusahaan membutuhkan sistem yang dapat menampilkan progres pengerjaan (seperti: Menunggu, Diproses, Selesai) yang dapat diakses langsung oleh pelanggan melalui aplikasi.
Integrasi Pembayaran Digital: Perusahaan membutuhkan sistem yang terintegrasi dengan payment gateway (Midtrans) untuk mempermudah pelanggan melakukan pembayaran non-tunai secara otomatis.
Manajemen Hak Akses (RBAC): Perusahaan membutuhkan pembagian akses yang jelas antara Admin (pengelola data), Mekanik (pengupdate status servis), dan Pelanggan (pemesan jasa).
Laporan Transaksi Otomatis: Perusahaan membutuhkan rekapitulasi data transaksi yang tersusun rapi untuk memudahkan evaluasi pendapatan bengkel.
3.4 Teknologi yang Digunakan
Perancangan sistem merupakan tahapan untuk memberikan gambaran secara umum mengenai alur kerja sistem yang akan dibangun. Perancangan ini menggunakan alat bantu Unified Modeling Language (UML) untuk mendokumentasikan kebutuhan fungsional sistem.

BAB IV
HASIL DAN PEMBAHASAN

 Deskripsi Project 
Proyek yang dikembangkan dalam penelitian ini adalah Sistem Informasi Pemesanan Servis Sepeda Motor pada Bengkel Putra Jaya Motor Berbasis Website. Sistem ini dirancang untuk mendigitalisasi dan mengotomatisasi pengelolaan operasional bengkel yang meliputi pemesanan jadwal servis (booking) secara online, manajemen data pelanggan dan mekanik, pengelolaan inventaris suku cadang (sparepart), pencatatan transaksi pembayaran, hingga pelacakan (tracking) status pengerjaan servis secara real-time. 
Fungsi utama dari pembangunan sistem ini adalah untuk mengalihkan proses pencatatan dan pemesanan konvensional yang sebelumnya masih mengandalkan kedatangan langsung dan buku catatan manual menjadi sistem yang terkomputerisasi. Dengan adanya sistem ini, pelanggan dapat memesan slot waktu perbaikan dari mana saja sehingga terhindar dari antrean panjang. Selain itu, seluruh riwayat data transaksi dan status perbaikan dapat disimpan secara digital di dalam basis data untuk meminimalisasi human error serta mempermudah pemilik usaha dalam menyusun laporan pendapatan di akhir periode.
Selain sebagai alat pemesanan, sistem ini juga difungsikan sebagai media pemantauan kondisi usaha. Melalui antarmuka dashboard yang interaktif, admin dapat memantau metrik-metrik penting seperti total booking harian, akumulasi pendapatan, dan kondisi ketersediaan stok barang. Di sisi pelayanan pelanggan, sistem ini telah mendukung integrasi Payment Gateway (Midtrans) untuk memfasilitasi metode pembayaran digital (QRIS, Transfer Bank/VA), sehingga memberikan pengalaman bertransaksi yang lebih cepat, aman, dan praktis.
Dalam pengoperasiannya, sistem ini membagi hak akses ke dalam tiga peran (Role-Based Access Control) pengguna utama, yaitu Admin, Mekanik, dan Pelanggan. Admin memiliki kendali penuh atas manajemen bengkel dan transaksi, Mekanik berfokus pada eksekusi tugas servis harian, sedangkan Pelanggan berperan sebagai pengguna layanan yang melakukan booking dan pembayaran.

4.2  Identifikasi Masalah
Tahap identifikasi masalah dilakukan melalui pendekatan kualitatif yang melibatkan observasi langsung dan wawancara mendalam dengan pemilik Bengkel Putra Jaya Motor. Berdasarkan hasil studi lapangan tersebut, ditemukan bahwa mekanisme pengelolaan operasional dan pemesanan yang berjalan saat ini masih bergantung pada metode konvensional.
Permasalahan paling mendasar yang dihadapi adalah proses pendaftaran servis yang mengharuskan pelanggan datang langsung atau melalui pesan singkat, yang seringkali menyebabkan antrean yang tidak teratur dan waktu tunggu yang sangat lama. Selain itu, pelanggan sering merasa kesulitan untuk mengetahui sejauh mana progres perbaikan motor mereka karena tidak ada sistem pelacakan (tracking) yang transparan.
Dari sisi manajemen bengkel, pencatatan data pelanggan, jadwal kerja mekanik, penggunaan suku cadang, hingga perhitungan total transaksi kasir masih ditulis dalam buku besar. Hal ini sering menimbulkan ketidakakuratan data, hilangnya riwayat servis, serta sangat menyulitkan dan memperlambat proses rekapitulasi laporan keuangan di akhir bulan. Proses pembayaran pun masih terbatas pada uang tunai, yang kurang praktis bagi pelanggan modern.
Oleh karena itu, disimpulkan bahwa Bengkel Putra Jaya Motor sangat membutuhkan implementasi sistem informasi berbasis web yang terintegrasi untuk menangani booking otomatis, membagi jadwal mekanik, melacak progres servis, serta menangani pembayaran digital secara sistematis.
4.3  Perancangan
Pada tahapan perancangan, dilakukan pemodelan sistem untuk memberikan gambaran yang jelas mengenai arsitektur, alur kerja, dan interaksi yang akan terjadi di dalam aplikasi yang dibangun. Pemodelan sistem ini dilakukan menggunakan pendekatan Unified Modeling Language (UML) guna mendefinisikan rancangan perangkat lunak secara visual dan terstruktur sebelum masuk ke tahap implementasi kode program.
Identifikasi user (pengguna) merupakan tahapan untuk mendefinisikan siapa saja aktor yang akan berinteraksi langsung dengan sistem dan batasan hak akses yang mereka miliki. Pada sistem ini, aktor diklasifikasikan menjadi tiga peran utama, yaitu Admin, Mekanik, dan Pelanggan. Pendefinisian aktor ini sangat penting untuk memastikan bahwa setiap pengguna hanya dapat mengakses fitur dan data yang relevan dengan tugas pokok dan fungsi mereka masing-masing, sekaligus menjaga keamanan data di dalam aplikasi. 

Tabel 4.1 Deskripsi Pengguna
Pengguna
Deskripsi Pengguna
Admin
Pengguna yang memiliki hak akses penuh (Superuser) dan bertindak sebagai pengelola utama sistem bengkel. Admin bertugas untuk mengelola seluruh Data Master (Pelanggan, Mekanik, Sparepart, dan Jasa Servis), menyetujui pesanan Booking yang masuk, memproses transaksi kasir pembayaran, merespon komplain pelanggan, serta melihat dan mengekspor laporan pendapatan bengkel.
Mekanik
Pengguna yang bertindak sebagai pelaksana teknis perbaikan sepeda motor. Mekanik memiliki akses terbatas ke dalam sistem, yaitu hanya untuk memperbarui data profilnya dan melihat daftar Jadwal Servis harian (beserta detail motor dan keluhannya) yang telah ditugaskan oleh Admin kepadanya. 
Pelanggan
Pengguna umum (End-User) yang merupakan konsumen dari jasa bengkel. Pelanggan dapat mengakses halaman utama (Landing Page), mendaftarkan akun (Registrasi), melakukan pemesanan jadwal servis (Booking) secara online tanpa harus antre di bengkel, serta mengajukan komplain jika ada masalah setelah motor selesai diperbaiki.


4.3.2 Daftar Kebutuhan
Daftar kebutuhan (Requirements) menjabarkan rincian spesifikasi mengenai apa saja yang harus dapat dilakukan oleh sistem untuk menyelesaikan permasalahan yang telah diidentifikasi. Daftar ini difokuskan pada kebutuhan fungsional sistem, yang meliputi kemampuan sistem dalam menangani proses autentikasi (login/logout), operasi Create, Read, Update, Delete (CRUD) pada master data barang dan kas, pemrosesan transaksi penjualan (POS), integrasi layanan payment gateway, hingga otomatisasi kalkulasi laporan keuangan dan penyajian grafik analitik.
Tabel 4.2 Daftar Pengguna
No.
Kebutuhan
Aktor
1.
User harus login terlebih dahulu untuk bisa mengakses sistem 
Admin, Kasir
2.
User bisa menampilkan, mengubah, dan menghapus data profil akun 
Admin, Kasir
3.
User bisa menampilkan, menambah, mengubah, dan menghapus data barang (inventaris toko) 
Admin
4.
User bisa menampilkan, menambah, mengubah, dan menghapus data pengeluaran operasional 
Admin
5.
User bisa menampilkan, menambah, dan menghapus data pemasukan tambahan (contoh: pemasukan lomba) 
Admin, Kasir
6.
User bisa membuat atau memproses transaksi penjualan baru kepada pelanggan 
Admin, Kasir
7.
Sistem dapat memproses pembayaran secara otomatis melalui integrasi Payment Gateway 
Kasir
8.
User bisa menampilkan rekapitulasi data dan laporan riwayat transaksi 
Admin
9.
User bisa menampilkan laporan keuangan berupa laporan laba bersih dan laba rugi 
Admin
10.
User bisa menampilkan grafik data penjualan pada halaman dashboard 
Admin



4.3.3 Usecase Diagram 
Use Case Diagram digunakan untuk memetakan fungsionalitas sistem dari sudut pandang interaksi antara pengguna luar (aktor) dengan sistem aplikasi. Penjelasan pada bagian ini mendeskripsikan secara fungsional fitur-fitur apa saja yang disediakan oleh aplikasi bagi Admin dan Kasir. Diagram ini juga menjelaskan adanya hubungan khusus antar proses, seperti relasi include di mana proses pembuatan transaksi wajib memicu proses integrasi payment gateway, serta relasi extend di mana pencetakan struk menjadi proses opsional setelah transaksi berhasil diselesaikan.

Gambar 4.1 Usecase diagram 
4.3.4 Scenario Use Case Diagram
Skenario Use Case merupakan penjelasan tekstual dan naratif yang merinci langkah-langkah spesifik yang terjadi di dalam setiap use case yang telah digambarkan pada diagram sebelumnya. Penjelasan ini mencakup kondisi awal sebelum suatu fitur dijalankan (pre-condition), kondisi akhir yang diharapkan (post-condition), alur kerja normal atau sukses (main flow), serta skenario alur alternatif apabila terjadi kegagalan atau kesalahan input oleh pengguna (alternative flow).

Skenario Use Case: Login
Tabel 4.3 Use case scenario Login
Skenario Use Case: Login
Nama Use Case
Melakukan Login
Actor
Admin, Mekanik, Pelanggan
Deskripsi
Masuk ke dalam sistem sesuai dengan hak akses (role) masing-masing.
Kondisi Awal
Aktor berada di halaman form Login dan belum memiliki sesi akses (session).
Kondisi Akhir
Aktor berhasil mendapatkan sesi akses dan diarahkan ke halaman utama masing-masing.
Alur Normal
1. Aktor memasukkan email dan password.
2. Aktor menekan tombol "Login".
3. Sistem memvalidasi kredensial (mencocokkan dengan database).
4. Sistem mengarahkan Admin ke Dashboard, Mekanik ke Jadwal Service, dan Pelanggan ke Landing Page.
Alur Alternatif
Jika email tidak terdaftar atau password salah, sistem menolak akses, memunculkan pesan error "Email atau password salah!", dan aktor tetap berada di halaman Login.


Skenario Use Case: Register Akun
Tabel 4.4 Use case scenario Register Akun
Skenario Use Case: Register Akunl
Nama Use Case
Mendaftar Akun Baru
Actor
Pelanggan
Deskripsi
Mendaftarkan data diri agar memiliki akun untuk melakukan booking servis.
Kondisi Awal
Pelanggan berada di halaman form Registrasi.
Kondisi Akhir
Data akun pelanggan berhasil tersimpan di tabel users dan pelanggans.
Alur Normal
1. Pelanggan mengisi form (Nama, Email, Password, Konfirmasi Password, Alamat, No Telp).
2. Pelanggan menekan tombol "Daftar".
3. Sistem memvalidasi form data.
4. Sistem menyimpan data ke database.
5. Sistem menampilkan pesan sukses dan mengarahkan Pelanggan ke halaman Login.
Alur Alternatif
Jika email sudah pernah digunakan sebelumnya, sistem menampilkan pesan error "Email sudah terdaftar" dan meminta Pelanggan memakai email lain.


Skenario Use Case: Landing Page
Tabel 4.5 Use case scenario Landing Page
Skenario Use Case: Landing Page
Nama Use Case
Melihat Halaman Utama (Publik)
Actor
Pelanggan
Deskripsi
Melihat informasi umum mengenai bengkel Putra Jaya Motor.
Kondisi Awal
Pelanggan mengakses URL utama aplikasi (website).
Kondisi Akhir
Halaman utama bengkel ditampilkan dengan baik.
Alur Normal
1. Pelanggan mengetikkan alamat URL sistem.
2. Sistem memuat komponen antarmuka Landing Page.
3. Pelanggan melihat informasi jasa, profil bengkel, dan tombol navigasi.
Alur Alternatif
Jika koneksi/server bermasalah, sistem akan menampilkan error 500 atau halaman tidak dapat dijangkau.


Skenario Use Case: Mengelola Profil
Tabel 4.6 Use case scenario Mengelola Profil
Skenario Use Case: Mengelola Profil
Nama Use Case
Mengubah Data Profil
Actor
Admin, Mekanik, Pelanggan
Deskripsi
Memperbarui informasi data diri atau mengganti password akun.
Kondisi Awal
Aktor telah berhasil Login dan berada di menu Pengaturan Profil.
Kondisi Akhir
Perubahan data diri berhasil diperbarui di dalam database.
Alur Normal
1. Aktor memilih menu Profil.
2. Aktor mengubah data (Nama, Foto, No HP, atau Password baru).
3. Aktor menekan tombol "Simpan".
4. Sistem menyimpan pembaruan tersebut dan memunculkan notifikasi "Profil berhasil diubah".
Alur Alternatif
Jika Aktor ingin mengganti password namun salah memasukkan password lama, sistem membatalkan proses dan menampilkan pesan error.



Skenario Use Case: Sistem Booking Service
Tabel 4.7 Use case scenario Sistem Booking Service
Skenario Use Case: Sistem Booking Service
Nama Use Case
Mengelola Pemesanan Servis (Booking)
Actor
Pelanggan, Admin
Deskripsi
Melakukan dan memvalidasi penjadwalan servis motor secara online.
Kondisi Awal
Pelanggan telah login dan membuka menu Booking.
Kondisi Akhir
Jadwal servis terekam dan disetujui untuk dikerjakan.
Alur Normal
1. Pelanggan mengisi form tanggal servis, jenis motor, dan keluhan.
2. Pelanggan mensubmit form.
3. Sistem menyimpan data dengan status Pending.
4. Admin melihat booking masuk, lalu merubah statusnya menjadi Disetujui (dan menugaskan Mekanik).
Alur Alternatif
- Sebelum Admin menyetujui, Pelanggan memilih "Batalkan Booking", maka sistem mengubah statusnya menjadi Dibatalkan.
- Jika slot servis bengkel penuh, Admin mengubah status menjadi Ditolak beserta alasan penolakan.



Skenario Use Case: Sistem Komplain
Tabel 4.8 Use case scenario Sistem Komplain
Skenario Use Case: Sistem Komplain
Nama Use Case
Mengajukan dan Merespon Komplain
Actor
Pelanggan, Admin
Deskripsi
Menyampaikan keluhan atas hasil servis dan memberikan tanggapan resmi dari bengkel.
Kondisi Awal
Pelanggan telah memiliki riwayat transaksi servis yang sudah Selesai.
Kondisi Akhir
Komplain pelanggan berhasil dikirim dan dijawab oleh Admin.
Alur Normal
1. Pelanggan memilih transaksi yang bermasalah dan mengisi teks komplain.
2. Sistem menyimpan data ke tabel komplains.
3. Admin membaca komplain di panel kontrol.
4. Admin memberikan balasan atas komplain tersebut dan menandai status komplain menjadi Selesai.
Alur Alternatif
Pelanggan mengklik tombol ajukan komplain, namun batal mengisi form dan menekan "Kembali", sehingga data tidak tersimpan.


Skenario Use Case: Melihat Jadwal Service
Tabel 4.9 Use case scenario Melihat Jadwal Service
Skenario Use Case: Melihat Jadwal Service
Nama Use Case
Melihat Daftar Pekerjaan Harian Mekanik
Actor
Mekanik
Deskripsi
Mengetahui data motor pelanggan yang harus diperbaiki pada hari tersebut.
Kondisi Awal
Mekanik berhasil login ke dalam sistem.
Kondisi Akhir
Mekanik melihat daftar tugas booking servis yang ditugaskan kepadanya.
Alur Normal
1. Mekanik membuka menu Jadwal Service.
2. Sistem melakukan query filter dari tabel bookings khusus untuk Mekanik tersebut.
3. Sistem menampilkan daftar motor, keluhan awal, dan jam kedatangan.
Alur Alternatif
Jika Admin belum memberikan penugasan apapun, sistem menampilkan halaman kosong dengan teks "Belum ada jadwal perbaikan hari ini."


Skenario Use Case: Kelola Data Pengguna (Pelanggan & Mekanik)
Tabel 4.10 Use case scenario Kelola Data Pengguna (Pelanggan & Mekanik)
Skenario Use Case: Kelola Data Pengguna (Pelanggan & Mekanik)
Nama Use Case
Mengelola Data Pengguna Master (CRUD)
Actor
Admin
Deskripsi
Menambah, melihat, mengedit, atau menghapus identitas Pelanggan dan Mekanik secara manual.
Kondisi Awal
Admin berada di halaman tabel Data Pelanggan atau Mekanik.
Kondisi Akhir
Data identitas tersimpan, diperbarui, atau terhapus secara permanen dari sistem.
Alur Normal
1. Admin menekan tombol "Tambah Data".
2. Admin mengetikkan form identitas Mekanik/Pelanggan.
3. Admin menekan "Simpan".
4. Sistem merekam ke database dan memperbarui tampilan tabel.
Alur Alternatif
Admin menekan tombol "Hapus", sistem menampilkan pop-up "Apakah Anda yakin?". Jika Admin mengklik "Batal", maka data gagal dihapus dan tetap aman.



Skenario Use Case: Kelola Data Jasa & Sparepart
Tabel 4.11 Use case scenario Kelola Data Jasa & Sparepart
Skenario Use Case: Kelola Data Jasa & Sparepart
Nama Use Case
Mengelola Inventaris dan Harga Servis
Actor
Admin
Deskripsi
Memperbarui daftar stok barang (sparepart) dan biaya jasa servis.
Kondisi Awal
Admin membuka menu Data Master Sparepart/Service.
Kondisi Akhir
Stok dan harga berhasil diperbarui agar bisa digunakan saat kasir transaksi.
Alur Normal
1. Admin melihat sebuah sparepart yang stoknya hampir habis.
2. Admin mengklik tombol "Edit" dan menambahkan angka stok baru.
3. Admin menyimpan data.
4. Sistem sukses mencatat stok terbaru.
Alur Alternatif
Saat Admin keliru memasukkan nominal harga menggunakan huruf (bukan angka), sistem menolak perintah dan menampilkan error "Harap masukkan format angka yang benar!".


Skenario Use Case: Kelola Data Transaksi
Tabel 4.8 Use case scenario Kelola Data Transaksi
Skenario Use Case: Kelola Data Transaksi
Nama Use Case
Memproses Kasir dan Pembayaran
Actor
Admin
Deskripsi
Menginput rincian biaya servis, mencatat pemasukan, dan menyelesaikan pesanan.
Kondisi Awal
Servis motor telah selesai dikerjakan oleh Mekanik.
Kondisi Akhir
Status pembayaran menjadi lunas, dan total pendapatan bengkel bertambah.
Alur Normal
1. Admin memilih booking yang selesai.
2. Admin membuka form Transaksi.
3. Admin menambahkan data sparepart yang dibeli dan biaya jasa.
4. Sistem otomatis menghitung total harga tagihan.
5. Admin menginput nominal uang bayar dan menekan "Selesaikan Transaksi".
Alur Alternatif
- Admin menekan fitur tambahan "Cetak Struk/Nota" setelah transaksi sukses, dan sistem mencetak file PDF nota.
- Jika nominal uang yang diinput lebih kecil dari tagihan, sistem menolak dan menampilkan alert "Uang pembayaran kurang!".




Skenario Use Case: Melihat Dashboard & Laporan
Tabel 4.8 Use case scenario Melihat Dashboard & Laporan
Skenario Use Case: Melihat Dashboard & Laporan
Nama Use Case
Mengecek Rekapitulasi Data Keuangan
Actor
Admin
Deskripsi
Melihat ringkasan grafik performa bengkel, dan omset pendapatan per bulan.
Kondisi Awal
Admin membuka menu Dashboard atau Laporan.
Kondisi Akhir
Data agregasi (total) keuangan ditampilkan dengan rapi di layar.
Alur Normal
1. Admin menekan menu Laporan.
2. Sistem memanggil data seluruh Transaksi lunas, menjumlahkannya secara otomatis berdasarkan periode bulan.
3. Admin melihat tabel rincian pendapatan bengkel.
Alur Alternatif
- Admin menekan tombol "Export Excel/PDF", sistem akan mengubah tabel website menjadi file dokumen.
- Jika pada bulan tersebut belum ada pelanggan yang menservis, sistem menampilkan layar kosong dengan teks "Belum ada transaksi di bulan ini".



4.3.5   Activity Diagram
Activity Diagram mendeskripsikan logika prosedural, proses bisnis, dan alur kerja (workflow) internal dari komponen-komponen sistem. Bagian ini menjelaskan secara rinci bagaimana urutan aktivitas dijalankan langkah demi langkah dalam sebuah proses tertentu, mulai dari titik awal (start state) hingga mencapai titik penyelesaian (end state). Sebagai contoh, penjelasan alur aktivitas pada saat Kasir memasukkan barang ke keranjang, sistem menghitung subtotal, hingga proses validasi pembayaran diselesaikan.

4.3.6   Sequence Diagram (tidak wajib)
Sequence Diagram menjelaskan secara detail mengenai komunikasi antar objek di dalam sistem berdasarkan urutan waktu. Penjelasan pada bagian ini menitikberatkan pada interaksi antara pengguna (aktor) dengan antarmuka aplikasi (view), pengontrol proses (controller), dan eksekusi pertukaran data ke basis data (model). Diagram ini sangat penting untuk memperlihatkan bagaimana flow pertukaran data secara teknis terjadi di balik layar (latar belakang aplikasi) saat suatu fungsi dijalankan.

4.3.7 	 Class Diagram		
Class Diagram berfungsi untuk memvisualisasikan struktur statis dari sistem yang dirancang dengan menunjukkan kelas-kelas penyusun sistem beserta atribut, operasi (method), dan relasi logika antar kelas tersebut. Penjelasan di bagian ini memaparkan struktur model database dalam bentuk kelas berorientasi objek (seperti kelas User, Barang, Transaksi, dan Pengeluaran), serta bagaimana objek-objek tersebut saling mewarisi atau terhubung di dalam kode program aplikasi (Framework Laravel).

4.3.8    Perancangan Interface 
Perancangan antarmuka (Interface Design) membahas mengenai konsep tata letak (layout) dan navigasi visual dari aplikasi yang bertujuan untuk memaksimalkan User Experience (UX). Penjelasan pada bagian ini berfokus pada perancangan desain layar yang responsif dan mudah digunakan. Antarmuka dirancang dengan pendekatan yang berbeda untuk setiap aktor; misalnya, halaman POS untuk Kasir didesain sesederhana dan secepat mungkin untuk mempercepat pelayanan, sementara halaman Dashboard untuk Admin difokuskan pada penyajian informasi analitik visual yang komprehensif.

Gambar 4.23 Perancangan halaman homepage 
4.3.7 Relasi Database
	Relasi Database (yang sering direpresentasikan dalam bentuk Entity Relationship Diagram / ERD) menjelaskan desain fisik tempat penyimpanan data. Bagian ini mendeskripsikan secara teknis skema tabel-tabel di dalam basis data (seperti tabel users, barang, transaksi, detail_transaksi, dan pengeluaran), atribut masing-masing tabel (seperti Primary Key), serta bagaimana tabel-tabel tersebut saling terhubung satu sama lain melalui Foreign Key (misalnya hubungan One-to-Many antara transaksi dan detail transaksi) guna memastikan integritas data.


Gambar 4.27 ER diagram  

4.4 Implemetasi
Menampilkan screeeshot halaman sistem/aplikasi dan memberikan penjelasan Contoh :
Halaman ini dikases oleh… halaman ini digunakan untuk … tampilan halaman login dapat dilihat pada Gambar 4.28…. 

Gambar 4.28 Tampilan halaman homepage 

4.5 	Pengujian

Metode pengujian yang digunakan adalah dengan menggunakan teknik black box testing dan usability testing. 
Pengujian Black Box Testing

Pengujian ini berfokus pada persyaratan fungsional perangkat lunak. Hasil pengujian dari sistem monitoring dan evaluasi pelaksanaan proyek dapat dilihat pada Tabel 4.3 

Tabel 4.3 Hasil pengujian pada halaman admin cafe 
No.
Deskripsi
Prosedur Pengujian
Masukkan
Keluaran yang diharapkan
Hasil yang didapatkan
Kesimpulan
1.
Login admin cafe
Ketik URL login
Ketik username dan password
Username : cafe
Password : cafe
Berhasil masuk ke halaman dashboard admin panel  
Berhasil masuk ke halaman dashboard admin panel  
Valid


…










2.
Halaman tentang kami
Klik menu tentang kami




Tampil data singkat profil dan tempat pemutaran
Tampil data singkat dan tempat pemutaran
Valid
3.
dst












Berdasarkan hasil pengujian yang dilakukan didapatkan, sistem ini sudah berjalan dengan benar sesuai permintaan pengguna. Sistem dapat melakukan fungsi-fungsi yang dijalankan pengguna dengan baik dan benar. 


Pengujian usability Testing bisa cek Referensi Jurnal (tidak wajib)

BAB V 
PENUTUP

5.1 Kesimpulan 
  (Kesimpulan menjawab rumusan masalah lebih detail tetapi singkat)
Sistem/aplikasi…. Dirancang melalui tahapan…. Peracangan menggunakan UML/DFD yang meliputi use case diagram…..
Sistem/aplikasi… dibangun dengan menggunakan PHP/CI/Laravel dan database MySQL. Sistem dibangun dengan memanfaatkan software……….
Sistem/aplikasi.. diuji dengan metode… hasil yang diperoleh..

5.2 Saran
(Saran berisi saran mengenai pengembangan TA yang bisa dilakuakan)
Contoh : Untuk pengembangan lebih lanjut maka perlu ditambahkan fitur pembayaran secara virtual…



DAFTAR PUSTAKA


List Daftar pustaka yang digunakan di Bab 1, Bab 2, Bab 3, Bab 4. Dan urutkan abjad contoh :
Abdulloh, R. 2017. Membuat Toko Online Dengan Teknik OOP, MVC, Dan Ajax. PT. Elex Media Komputindo : Jakarta
Fakung R. 2018. Evaluasi Penerapan Enterprise Resources Planning (ERP) Terhadap Penyajian Laporan Keuangan (Studi Kasus Di PT. Surya Citra Televisi). Fakultas ekonomi Universitas Pamulang. Jurnal KREATIF : Pemasaran, Sumberdaya Manusia Dan Keuangan, 6 (3) : 1-12
Hakim , N.I, and Tonggiroh, M. 2017. Sistem Informasi Kepegawaian Pada Kantor Dewan Teknologi Informasi dan Komunikasi Provinsi Papua
Berbasis Web. Jurnal Ilmiah Teknik dan Informatika. 2 (1) : 7-8
Format Penulisan Daftar Pustaka
Penulisan daftar pustaka ditulis sesuai tata tulis menurut acuan Publication
Manual of the American Psychological Association (2001, 5th ed.) dan disusun secara alfabetis dari nama akhir penulis utama. Penyusunan daftar pustaka dianjurkan menggunakan Mendeley. Pustaka yang tercantum di Daftar Pustaka adalah pustaka yang dikutip dan dituliskan dalam Kajian Pustaka. Tata cara penulisan sumber yang diambil dari buku, jurnal, website, laporan karya ilmiah/Tugas Akhir/tesis, surat kabar, dan sumber informasi lain akan berbeda
seperti contoh-contoh berikut.
1. Buku
Nama Belakang Pengarang, Inisial. Tahun penerbitan. Judul buku (Edisi jika edisinya lebih dari satu). Tempat diterbitkan: Penerbit.
Contoh:
a. Satu pengarang
Guyton, A.C. (2001). Textbook of Medical Physiology. 5th ed. Philadelphia: WB Saunders.
b. Dua pengarang
Anna, N dan Santoso, CL. (2007). Pendidikan Anak, Ed. 5. Jakarta: Family Press.
c. Tiga pengarang
Kotler, P, Adam, S, Brown, L dan Armstrong, G. (2003). Principles of Marketing. 2nd ed. Melbourne: Pearson Education Australia.15
d. Empat pengarang atau lebih: gunakan et al setelah pengarang pertama
Sukanto R. et al. (2002). Business Frocasting. Yogyakarta: Bagian penerbitan Fakultas Ekonomi UGM.
2. Buku terbitan Lembaga/Badan/Organisasi
Nama lembaga/badan/organisasi. Tahun penerbitan. Judul buku (cetak miring). Edisi/cetakan. Nama penerbit. Kota penerbit.
Contoh:
Kementerian Pendidikan dan Kebudayaan RI. (2011). Pendidikan Anti Korupsi untuk Perguruan Tinggi. Cetakan 1. Direktorat Jenderal Pendidikan Tinggi. Bagian Hukum Kepegawaian. Jakarta.
3. Peraturan, Undang-undang, dan sejenisnya
Nomor dan tahun peraturan/UU. Judul peraturan/UU yang dirujuk (cetak miring). Tanggal pengesahan/penerbitan (jika ada). Nomor lembaran negara (jika ada). Organisasi penerbit (jika ada) .Kota tempat pengesahan/penerbitan.
Contoh:
Undang-Undang Republik Indonesia Nomor 20 Tahun 2003. Sistem Pendidikan Nasional. 8 Juli 2003. Lembaran Negara Republik Indonesia Tahun 2003 Nomor 4301. Jakarta.
4. Artikel Jurnal
Nama belakang, singkatan (inisial) nama depan dan nama tengah (jika ada). Tahun penerbitan. Judul artikel. Nama jurnal (cetak miring). Volume dan nomor jurnal (nomor jurnal dalam tanda kurung). Nomor halaman artikel dalam jurnal. Jika ada dua penulis atau lebih, cara penulisannya sama seperti cara menulis daftar pustaka untuk buku.
Contoh:
Riduwan, A. (2010). Etika dan Perilaku Koruptif dalam Praktik Manajemen Laba. Jurnal Akuntansi & Auditing Indonesia 14(2): 121-141.
5. Tugas Akhir/Tesis/Disertasi/Laporan Karya Ilmiah16
Nama belakang, singkatan (inisial) nama depan dan nama tengah (jika ada). Tahun. Judul Tugas Akhir/tesis/disertasi/Laporan Karya Ilmiah (cetak miring). Nama program studi dan/atau perguruan tinggi. Kota tempat perguruan tinggi.
Contoh:
Verdanasari, E. F. (2012). Pengaruh Penerapan Corporate Governance terhadap Nilai Perusahaan dengan Kualitas Laba sebagai Variabel Intervening. Tugas Akhir. Sekolah Tinggi Ilmu Ekonomi Indonesia (STIESIA). Surabaya.
6. Artikel internet
Nama belakang, singkatan (inisial) nama depan dan nama tengah (jika ada).
Tahun. Judul. Alamat web (cetak miring). Tanggal dan jam unduh.
Contoh:
Himman, L.M. (2002). A Moral Change: Business Ethics after Enron. San Diego University Publication. http:ethics.sandiego.edu/LMH/oped/Enron/index.asp. 27
Januari 2008 (15:23).
7. Artikel dari majalah/surat kabar
Nama belakang, singkatan (inisial) nama depan dan nama tengah (jika ada).
Tahun. Judul artikel (cetak miring). Nama majalah/surat kabar. Tanggal.
Halaman. Kota penerbit.
Contoh:
Mangunwijaya, Y.B. (1992). Pendidikan Manusia Merdeka. Harian Kompas. 11 Agustus. Halaman 15. Jakarta








LAMPIRAN
(jika ada)

Surat ijin pengambilan data
Konsep atau hasil wawancara/kuesioner
Dokumentasi
Rumus-rumus dan perhitungan yang akan digunakan dalam analisis masalah






