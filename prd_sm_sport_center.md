# PRD Sistem Reservasi Lapangan Olahraga SM Sport Center

## 1. Ringkasan Produk
SM Sport Center membutuhkan sistem reservasi lapangan olahraga berbasis web untuk menggantikan proses reservasi manual melalui telepon dan WhatsApp. Sistem ini ditujukan untuk pelanggan dan admin agar proses pemesanan lapangan menjadi lebih cepat, akurat, dan mudah dipantau.

SM Sport Center pada skenario memiliki 2 lapangan futsal dan 3 lapangan badminton. Masalah utama pada proses manual adalah jadwal bentrok, kesalahan pencatatan transaksi, sulit membuat laporan penggunaan lapangan, dan sulit mengetahui ketersediaan lapangan.

## 2. Latar Belakang
Proses reservasi manual menyebabkan data reservasi tersebar, rawan salah catat, dan sulit divalidasi secara real time. Kondisi ini menghambat manajemen dalam mengatur slot lapangan, memantau penggunaan, dan menyusun laporan operasional.

Sistem berbasis web dibutuhkan agar:
- pelanggan dapat melihat ketersediaan lapangan,
- pelanggan dapat melakukan reservasi secara mandiri,
- admin dapat mengelola data reservasi dan laporan dengan lebih efisien,
- sistem dapat mencegah double booking melalui validasi jadwal.

## 3. Tujuan Produk
Tujuan utama produk ini adalah membangun sistem reservasi yang:
1. memudahkan pelanggan melakukan reservasi lapangan,
2. memudahkan admin mengelola data reservasi,
3. mencegah jadwal bentrok,
4. menyimpan data secara terpusat di basis data,
5. menyediakan laporan penggunaan lapangan,
6. mendukung pengujian, debugging, profiling, dan evaluasi sistem.

## 4. Ruang Lingkup Produk

### 4.1 Fitur Dalam Ruang Lingkup
- Login pengguna
- Manajemen data reservasi
- Lihat daftar reservasi
- Tambah reservasi
- Edit reservasi
- Hapus reservasi
- Pencarian reservasi
- Validasi jadwal agar tidak bentrok
- Menampilkan ketersediaan lapangan
- Laporan penggunaan lapangan
- Koneksi ke basis data
- Penyimpanan data pelanggan, lapangan, dan reservasi

### 4.2 Fitur di Luar Ruang Lingkup
- Pembayaran online
- Notifikasi SMS/WhatsApp otomatis
- Integrasi kalender eksternal
- Aplikasi mobile native
- Multi-cabang
- Sistem keanggotaan kompleks

## 5. Pengguna (User Persona)

### 5.1 Pelanggan
Kebutuhan:
- melihat lapangan yang tersedia,
- membuat reservasi,
- menghindari jadwal yang sudah terisi,
- memeriksa status reservasi.

### 5.2 Admin
Kebutuhan:
- login ke sistem,
- memverifikasi dan mengelola reservasi,
- mengubah atau menghapus data,
- melihat laporan penggunaan lapangan,
- mengawasi bentrok jadwal.

## 6. Masalah yang Ingin Diselesaikan
Sistem ini harus menyelesaikan masalah berikut:
- double booking akibat pengecekan manual,
- kesalahan pencatatan data,
- kesulitan mencari slot lapangan yang masih kosong,
- kesulitan membuat laporan penggunaan,
- data reservasi yang tidak terstruktur.

## 7. Kebutuhan Fungsional

### 7.1 Autentikasi
- Sistem menyediakan form login.
- Sistem memvalidasi username dan password.
- Jika login benar, pengguna masuk ke dashboard.
- Jika login salah, sistem menampilkan pesan error.

### 7.2 Pengelolaan Lapangan
- Sistem menyimpan data lapangan.
- Sistem menampilkan daftar lapangan futsal dan badminton.
- Sistem menampilkan status tersedia atau terisi.

### 7.3 Pengelolaan Pelanggan
- Sistem menyimpan data pelanggan.
- Admin dapat menambah, melihat, mengubah, dan menghapus data pelanggan sesuai kebutuhan aplikasi.

### 7.4 Reservasi
- Pengguna dapat membuat reservasi berdasarkan lapangan, tanggal, jam mulai, dan jam selesai.
- Sistem memvalidasi ketersediaan jadwal sebelum menyimpan data.
- Sistem menolak reservasi yang bentrok dengan jadwal yang sudah ada.
- Sistem menyimpan reservasi yang valid ke basis data.
- Admin dapat melihat seluruh data reservasi.

### 7.5 Edit dan Hapus Reservasi
- Admin dapat mengubah data reservasi yang belum final.
- Admin dapat menghapus data reservasi jika diperlukan.
- Sistem memperbarui status data di basis data.

### 7.6 Pencarian
- Sistem menyediakan pencarian data reservasi berdasarkan nama pelanggan, tanggal, atau lapangan.

### 7.7 Laporan
- Sistem menghasilkan laporan penggunaan lapangan.
- Laporan dapat difilter berdasarkan periode waktu tertentu.
- Laporan membantu manajemen mengetahui tingkat pemakaian lapangan.

## 8. Kebutuhan Non-Fungsional

### 8.1 Kinerja
- Sistem harus mampu melakukan validasi jadwal dengan cepat.
- Pencarian data reservasi harus responsif.
- Laporan harus dapat dihasilkan tanpa jeda yang berlebihan.

### 8.2 Keandalan
- Data reservasi harus tersimpan dengan konsisten.
- Sistem harus menghindari duplikasi data dan bentrok jadwal.

### 8.3 Keamanan
- Login harus membatasi akses pengguna yang tidak berwenang.
- Data sensitif harus terlindungi di basis data.
- Sistem harus mencegah manipulasi data secara langsung dari sisi pengguna.

### 8.4 Kemudahan Penggunaan
- Tampilan harus sederhana dan mudah dipahami.
- Form reservasi harus jelas dan ringkas.
- Pesan validasi harus mudah dimengerti.

### 8.5 Skalabilitas
- Sistem harus siap menangani pertumbuhan data reservasi dari waktu ke waktu.
- Struktur basis data harus mendukung penambahan data tanpa menurunkan performa secara signifikan.
- Query utama seperti pencarian dan validasi jadwal harus dioptimalkan.

### 8.6 Maintainability
- Kode program harus terdokumentasi.
- Struktur modul harus jelas.
- Perubahan fitur harus dapat dilakukan tanpa merusak fungsi utama.

## 9. Aturan Bisnis
1. Satu jadwal hanya boleh dipakai oleh satu reservasi pada lapangan yang sama.
2. Reservasi hanya dapat disimpan jika slot waktu masih tersedia.
3. Setiap reservasi harus terkait dengan satu pelanggan dan satu lapangan.
4. Data pelanggan, lapangan, dan reservasi harus disimpan dalam basis data.
5. Admin memiliki hak untuk mengelola data reservasi dan melihat laporan.
6. Sistem harus menampilkan pesan kesalahan jika input tidak valid.
7. Data yang sudah tersimpan harus tetap konsisten saat diedit atau dihapus.

## 10. Entitas Data Utama
Berdasarkan skenario, basis data minimal terdiri dari:

### 10.1 Tabel Lapangan
Menyimpan informasi lapangan, seperti:
- id_lapangan
- nama_lapangan
- jenis_lapangan
- status

### 10.2 Tabel Pelanggan
Menyimpan informasi pelanggan, seperti:
- id_pelanggan
- nama_pelanggan
- nomor_telepon
- alamat atau identitas kontak lain

### 10.3 Tabel Reservasi
Menyimpan informasi reservasi, seperti:
- id_reservasi
- id_pelanggan
- id_lapangan
- tanggal_reservasi
- jam_mulai
- jam_selesai
- status_reservasi
- catatan

## 11. Alur Pengguna

### 11.1 Alur Pelanggan
1. Pelanggan membuka website.
2. Pelanggan melihat ketersediaan lapangan.
3. Pelanggan mengisi form reservasi.
4. Sistem memvalidasi jadwal.
5. Jika valid, reservasi disimpan.
6. Pelanggan menerima informasi bahwa reservasi berhasil.

### 11.2 Alur Admin
1. Admin login ke sistem.
2. Admin melihat daftar reservasi.
3. Admin menambah, mengubah, atau menghapus data jika diperlukan.
4. Admin mencari data tertentu.
5. Admin mencetak atau melihat laporan penggunaan lapangan.

## 12. User Story

### Pelanggan
- Sebagai pelanggan, saya ingin melihat lapangan yang tersedia agar saya bisa memilih jadwal yang sesuai.
- Sebagai pelanggan, saya ingin melakukan reservasi secara online agar lebih cepat daripada melalui WhatsApp.
- Sebagai pelanggan, saya ingin sistem menolak jadwal bentrok agar reservasi saya tidak bermasalah.

### Admin
- Sebagai admin, saya ingin login ke sistem agar data reservasi lebih aman.
- Sebagai admin, saya ingin melihat dan mengelola reservasi agar data lebih teratur.
- Sebagai admin, saya ingin mendapatkan laporan penggunaan lapangan agar bisa mengevaluasi pemakaian lapangan.

## 13. Kriteria Keberhasilan
Produk dinilai berhasil jika:
- pelanggan dapat melakukan reservasi tanpa bentrok jadwal,
- admin dapat mengelola data dengan mudah,
- data tersimpan rapi di basis data,
- laporan dapat dibuat dengan benar,
- hasil pengujian unit dan integrasi menunjukkan sistem berjalan sesuai alur login → reservasi → simpan → laporan.

## 14. Kebutuhan Pengujian
Pengujian yang diperlukan:
- Unit testing untuk form login dan form reservasi
- Integrasi testing untuk alur login → reservasi → simpan → laporan
- Validasi kasus sukses dan gagal
- Pengujian bentrok jadwal
- Pengujian pencarian data
- Pengujian perbaikan bug jika reservasi masih tersimpan saat slot sudah terisi

## 15. Kebutuhan Skalabilitas
Analisis skalabilitas perlu memperhatikan:
- pertumbuhan jumlah reservasi harian,
- potensi bottleneck pada query pengecekan jadwal,
- performa saat data reservasi bertambah,
- kebutuhan indeks pada kolom tanggal, jam, dan id_lapangan,
- optimasi pencarian dan laporan.

Rekomendasi awal:
- gunakan indeks pada kolom pencarian utama,
- pisahkan logika validasi jadwal dari tampilan,
- gunakan query yang efisien untuk mengecek bentrok,
- pastikan relasi antar tabel konsisten,
- siapkan struktur basis data yang mudah dikembangkan.

## 16. Deliverables
Dokumen ini mendukung keluaran berikut:
1. Dokumen Analisis Skalabilitas Perangkat Lunak
2. ERD dan SQL Script
3. User Interface dan Source Code
4. Dokumentasi Kode Program
5. Laporan Debugging
6. Hasil Profiling
7. Hasil Unit Testing dan Integration Testing
8. Hasil Perancangan Perangkat Lunak dan Demonstrasi Sistem

## 17. Risiko Utama
- Kesalahan validasi jadwal
- Data reservasi ganda
- Query lambat saat data bertambah
- Kesalahan input pengguna
- Inkonsistensi data saat edit/hapus reservasi

## 18. Asumsi
- Sistem berbasis web.
- Pengguna utama adalah pelanggan dan admin.
- Data lapangan sudah diketahui sejak awal: 2 futsal dan 3 badminton.
- Sistem menyimpan data pada basis data relasional.
- Fokus utama adalah reservasi, validasi jadwal, laporan, dan pengelolaan data.

## 19. Kesimpulan
PRD ini mendefinisikan kebutuhan utama sistem reservasi lapangan olahraga SM Sport Center secara jelas, mulai dari masalah bisnis, tujuan produk, ruang lingkup, kebutuhan fungsional dan non-fungsional, hingga pengujian dan skalabilitas. Dokumen ini dapat digunakan sebagai acuan untuk perancangan basis data, implementasi program, debugging, profiling, dan pengujian sistem.
