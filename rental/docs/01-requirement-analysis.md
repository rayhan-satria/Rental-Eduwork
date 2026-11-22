# Requirement Analysis: Business Discovery

## 1. Project Overview
- **Nama Aplikasi** : Rental 
- **Tujuan Aplikasi** : Membangun platform pemesanan rental motor berbasis web untuk mempermudah customer melihat ketersediaan unit secara real-time, melakukan booking secara mandiri, serta membantu admin mengelola armada dan transaksi penyewaan secara terpusat.
- **Masalah yg Ingin Diselesaikan** : 
  1. Pencatatan jadwal sewa manual yang rawan bentrok (*double booking*).
  2. Proses pengecekan ketersediaan unit dan harga yang lambat karena harus bertanya melalui chat satu per satu.
  3. Rekapitulasi laporan keuangan dan riwayat penyewaan yang sulit dilacak.
- **Siapa Target Pengguna** : 
  1. Wisatawan lokal maupun mancanegara.
  2. Mahasiswa, pekerja, atau pendatang yang membutuhkan transportasi harian sementara.
  3. Pemilik dan staf operasional usaha rental motor (sebagai Admin).

## 2. User Identification
- **Admin** : Pemilik atau staf operasional yang bertugas mengelola data armada motor, memverifikasi identitas dan pembayaran customer, mengatur status penyewaan, serta memantau laporan transaksi.
- **Customer** : Pengguna akhir yang mencari unit motor, melakukan pemesanan, mengunggah dokumen persyaratan, melakukan pembayaran, dan melihat riwayat sewa.

## 3. Functional Requirements
- **FR-01 (Autentikasi Pengguna)** : Sistem menyediakan fitur registrasi, login, dan logout untuk Admin dan Customer (menggunakan Laravel Breeze).
- **FR-02 (Manajemen Armada Motor - Admin)** : Admin dapat menambah, melihat, mengubah, dan menghapus (CRUD) data motor (merk, tipe, plat nomor, tahun, harga sewa, foto, dan status ketersediaan).
- **FR-03 (Verifikasi Booking & Pembayaran - Admin)** : Admin dapat memverifikasi dokumen identitas dan bukti pembayaran customer untuk menyetujui (*approve*) atau menolak (*reject*) pesanan.
- **FR-04 (Manajemen Status Sewa - Admin)** : Admin dapat memperbarui status siklus transaksi (Menunggu Pembayaran -> Menunggu Verifikasi -> Disetujui/Siap Diambil -> Sedang Disewa -> Selesai / Dibatalkan).
- **FR-05 (Perhitungan Denda & Pengembalian - Admin)** : Admin dapat mencatat waktu aktual pengembalian motor dan sistem otomatis menghitung denda jika terjadi keterlambatan.
- **FR-06 (Katalog & Pencarian Motor - Customer)** : Customer dapat melihat daftar motor yang tersedia dan memfilternya berdasarkan tanggal sewa, tipe motor, atau rentang harga.
- **FR-07 (Pemesanan/Booking Unit - Customer)** : Customer dapat memilih motor, menentukan tanggal & jam mulai sewa serta durasi sewa, dan menghitung estimasi total biaya secara otomatis.
- **FR-08 (Unggah Dokumen Syarat - Customer)** : Customer wajib mengunggah foto dokumen identitas (KTP dan SIM C aktif) saat pertama kali melakukan booking sebagai syarat verifikasi keamanan.
- **FR-09 (Pembayaran & Upload Bukti Transfer - Customer)** : Sistem menampilkan rincian tagihan dan nomor rekening tujuan, serta menyediakan fitur unggah bukti transfer DP atau pelunasan.
- **FR-10 (Riwayat & Laporan Transaksi)** : Customer dapat melihat riwayat penyewaan pribadi, sedangkan Admin dapat melihat dan mengekspor laporan seluruh transaksi penyewaan berdasarkan periode waktu tertentu.

## 4. Non-Functional Requirements
- **Website Responsif** : Tampilan antarmuka (*UI*) harus menyesuaikan ukuran layar desktop maupun perangkat mobile (menggunakan Tailwind CSS untuk halaman profile dan Bootstrap CSS untuk storefront).
- **Password pengguna harus di-Hash** : Seluruh kata sandi pengguna wajib dienkripsi di database menggunakan algoritma hashing standar Laravel (`Bcrypt` atau `Argon2`).
- **Halaman admin hanya bisa diakses oleh pihak berwenang** : Penerapan *Role-Based Access Control* (RBAC) menggunakan *Middleware* Laravel sehingga halaman dashboard admin tidak bisa diakses oleh role Customer.
- **Data transaksi disimpan di DB** : Seluruh data master dan transaksi disimpan secara relasional di database (MySQL) dengan integritas data yang terjaga (*foreign keys*).
- **Validasi input dilakukan di frontend dan backend** : Validasi format file, ukuran gambar, tanggal yang valid, dan kolom wajib isi diterapkan menggunakan HTML5/JS di sisi klien dan *Form Request Validation* di sisi server Laravel. 

## 5. Business Rules
- **Bagaimana menentukan harga rental?** : 
  Harga ditentukan berdasarkan jenis/kapasitas mesin motor (misal: Matic standar Rp80.000/hari, Matic Maxi/Sport Rp150.000/hari) dan dikalikan dengan jumlah hari sewa. Tarif bisa disesuaikan oleh Admin (misalnya kenaikan tarif saat *high season* atau diskon untuk sewa mingguan/bulanan).
- **Apakah rental dihitung per 24 jam atau per hari kalender?** : 
  Dihitung **per 24 jam** sejak waktu pengambilan motor yang disepakati pada formulir booking (contoh: ambil Senin pukul 10.00 WIB, maka wajib kembali Selasa pukul 10.00 WIB).
- **Apakah customer harus membayar DP?** : 
  Ya, customer wajib membayar **DP minimal 30%–50%** dari total biaya sewa maksimal 2 jam setelah booking dibuat agar jadwal terkunci. Sisa pembayaran dilunasi saat serah terima unit motor (atau customer boleh langsung membayar lunas 100% di awal).
- **Siapa yg menyetujui booking?** : 
  **Admin** menyetujui booking setelah memeriksa keaslian dokumen identitas (KTP & SIM C) dan memvalidasi dana DP yang masuk ke rekening rental.
- **Kapan motor dianggap tidak tersedia?** : 
  1. Ketika unit sudah dibooking dan DP-nya telah diverifikasi (*Approved*) oleh Admin pada rentang tanggal tersebut.
  2. Ketika unit berstatus *"Sedang Disewa"* atau *"Dalam Perawatan/Servis"* yang diatur manual oleh Admin.
  *(Catatan: Jika booking berstatus "Menunggu Pembayaran" melewati batas waktu 2 jam tanpa upload bukti bayar, sistem otomatis membatalkan booking dan mengembalikan status motor menjadi tersedia).*
- **Bagaimana aturan keterlambatan?** : 
  Diberikan toleransi keterlambatan maksimal **1 jam**. Jika melewati batas toleransi, dikenakan denda sebesar **15% dari harga sewa harian per jam** keterlambatan. Jika keterlambatan melebihi 5 jam, maka otomatis dihitung sebagai perpanjangan sewa **1 hari penuh**.
- **Bagaimana jika motor rusak?** : 
  1. **Kerusakan ringan akibat pemakaian wajar** (misal: ban bocor standar, bohlam putus) ditanggung oleh pihak rental atau diganti sesuai kesepakatan awal.
  2. **Kerusakan akibat kelalaian customer** (kecelakaan, jatuh, komponen patah/hilang) sepenuhnya menjadi tanggung jawab customer untuk mengganti biaya perbaikan di bengkel resmi.
  3. **Kehilangan unit atau STNK** mewajibkan customer mengganti sesuai nilai pasar unit atau biaya pengurusan surat kehilangan kepolisian dan penerbitan STNK baru.
