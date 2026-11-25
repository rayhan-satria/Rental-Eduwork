# Database Design

## 1. Daftar Tabel yg Dibutuhkan
- *users* : Menyimpan data akun pengguna (Admin dan Customer) beserta dokumen verifikasi.
- *motorcycles* : Menyimpan data master/katalog jenis motor (misal: Honda Vario 160, Yamaha NMAX) beserta harga sewa hariannya.
- *motorcycle_units* : Menyimpan data fisik tiap unit motor yang dimiliki (berdasarkan plat nomor) agar satu jenis motor bisa memiliki banyak stok unit.
- *bookings* : Menyimpan data transaksi penyewaan motor, jadwal sewa, status transaksi, hingga catatan denda keterlambatan.
- *payments* : Menyimpan riwayat pembayaran (DP maupun pelunasan) beserta bukti transfer dan status verifikasinya.
- *booking_inspections* : Menyimpan hasil pemeriksaan kondisi motor sebelum dan sesudah penyewaan
- *damage_reports* : Menyimpan laporan kerusakan yang ditemukan saat pemeriksaan

---

## 2. Kolom Setiap Tabel dg Tipe Data

### A. Tabel `users`

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | PK, Auto Increment |
| `name` | `VARCHAR(255)` | Nama lengkap pengguna |
| `email` | `VARCHAR(255)` | Unique, untuk login |
| `password` | `VARCHAR(255)` | Password yg sudah di-Hash
| `phone_number` | `VARCHAR(20)` | Nomor WhatsApp/HP aktif |
| `address` | `TEXT` | Alamat domisili |
| `role` | `ENUM('admin', 'customer')` | Default: `'customer'` |
| `ktp_image` | `VARCHAR(255)` | Nullable, path foto KTP |
| `sim_image` | `VARCHAR(255)` | Nullable, path foto SIM C |
| `is_verified` | `BOOLEAN` | Default: `false` (status verifikasi dokumen oleh Admin) |
| `created_at` | `TIMESTAMP` | Waktu pembuatan data |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan data |

#### Catatan
- Admin dan customer menggunakan tabel akun yg sama
- Dokumen KTP dan SIM C bersifat *nullable* karena customer belum tentu langsung mengunggah dokumen saat registrasi
- *is_verified* menunjukkan status verifikasi dokumen customer oleh admin

### B. Tabel `motorcycles` (Master Jenis Motor)

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | PK, Auto Increment |
| `brand` | `VARCHAR(100)` | Merk motor (Honda, Yamaha, dll) |
| `model` | `VARCHAR(100)` | Tipe/model (Vario 160, NMAX, dll) |
| `category` | `ENUM('matic', 'manual', 'sport')` | Kategori transmisi/jenis |
| `cc` | `INT` | Kapasitas mesin (misal: 125, 150, 160) |
| `daily_price` | `DECIMAL(12, 2)` | Harga sewa per 24 jam |
| `description` | `TEXT` | Nullable, spesifikasi atau fasilitas tambahan (helm, jas hujan) |
| `image` | `VARCHAR(255)` | Path foto utama katalog motor |
| `created_at` | `TIMESTAMP` | Waktu pembuatan data |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan data |

#### Catatan
- Tabel ini menyimpan karakteristik umum suatu model motor
- Tahun produksi dan plat nomor tidak disimpan disini karena setiap unit fisik dapat memiliki informasi berbeda

### C. Tabel `motorcycle_units` (Stok Fisik Per Plat Nomor)

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | PK, Auto Increment |
| `motorcycle_id` | `BIGINT UNSIGNED` | FK, Referensi ke tabel `motorcycles` |
| `license_plate` | `VARCHAR(20)` | Unique, nomor polisi / plat nomor |
| `year` | `YEAR` | Tahun Produksi Unit|
| `color` | `VARCHAR(50)` | Warna fisik unit |
| `unit_condition` | `VARCHAR(50)` | Kondisi fisik unit |
| `status` | `ENUM('available', 'rented', 'maintenance')` | Default: `'available'` |
| `created_at` | `TIMESTAMP` | Waktu pembuatan data |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan data |

#### Catatan
- Satu jenis motor dapat mempunyai banyak unit fisik
- Setiap unit memiliki plat nomor yg berbeda
- Status *Available*, *Rented*, dan *Maintenance* digunakan untuk pengelolaan unit
- Ketersediaan berdasarkan jadwal booking tetap harus diperiksa dari tabel transaksi, bukan hanya dari kolom status

### D. Tabel `bookings` (Transaksi Penyewaan)

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | PK, Auto Increment |
| `booking_code` | `VARCHAR(50)` | Unique, kode booking (misal: `RNT-20261122-001`) |
| `user_id` | `BIGINT UNSIGNED` | FK, Referensi ke `users` (Customer yang menyewa) |
| `motorcycle_unit_id` | `BIGINT UNSIGNED` | FK,Referensi ke `motorcycle_units` (Unit spesifik) |
| `start_date` | `DATETIME` | Tanggal & jam mulai sewa |
| `end_date` | `DATETIME` | Tanggal & jam selesai sewa (kelipatan 24 jam) |
| `actual_return_date` | `DATETIME` | Nullable, diisi Admin saat motor dikembalikan |
| `hold_expires_at` | `DATETIME` | Batas waktu penahanan unit selama 2 jam
| `duration_days` | `INT` | Durasi sewa dalam hari |
| `daily_price` | `DECIMAL(12, 2)` | Harga per hari saat booking dibuat
| `total_price` | `DECIMAL(12, 2)` | Total biaya sewa (`duration_days * daily_price`) |
| `late_fee` | `DECIMAL(12, 2)` | Default: `0`, denda keterlambatan |
| `damage_fee` | `DECIMAL(12, 2)` | Default: `0`, biaya ganti rugi kerusakan (jika ada) |
| `status` | `ENUM('pending_payment', 'waiting_verification', 'approved', 'ongoing', 'completed', 'cancelled')` | Status siklus transaksi |
| `notes` | `TEXT` | Nullable, catatan tambahan atau kondisi motor |
| `created_at` | `TIMESTAMP` | Waktu booking dibuat |
| `updated_at` | `TIMESTAMP` | Waktu status diperbarui |

#### Catatan
- *daily_price* menyimpan harga saat transaksi dibuat agar perubahan harga katalog tidak memengaruhi booking lama
- *hold_expires_at* menyimpan batas waktu penahanan unit
- *Booking* yg belum kedaluwarsa dan masih berstatus *pending_payment* tetap dianggap menahan unit
- Perhitungan biaya keterlambatan dan kerusakan disimpan pada kolom masing-masing
- Penghitungan dan perubahan status dilakukan melalui logika aplikasi laravel

### E. Tabel `payments` (Pembayaran & Bukti Transfer)

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | PK, Auto Increment |
| `booking_id` | `BIGINT UNSIGNED` | FK, Referensi ke tabel `bookings` |
| `payment_type` | `ENUM('dp', 'full_payment', 'balance_payment', 'late_fee', 'damage_fee')` | Jenis pembayaran (DP, Lunas Awal, Pelunasan, Denda terlambat, Denda kerusakan) |
| `amount` | `DECIMAL(12, 2)` | Nominal uang yang dibayarkan |
| `payment_method` | `VARCHAR(50)` | Metode bayar (Transfer BCA, QRIS, Tunai) |
| `proof_image` | `VARCHAR(255)` | Nullable, path foto bukti transfer |
| `status` | `ENUM('pending', 'verified', 'rejected')` | Default: `'pending'` |
| `verified_by` | `BIGINT UNSIGNED` | Nullable, referensi ke `users` (Admin yang memverifikasi) |
| `paid_at` | `DATETIME` | Waktu customer melakukan pembayaran |
| `submitted_at` | `DATETIME` | Waktu bukti pembayaran diunggah |
| `verified_at` | `DATETIME` | Nullable, waktu verifikasi admin |
| `created_at` | `TIMESTAMP` | Waktu pembuatan data |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan data |

#### Catatan
- Satu booking dapat mempunyai beberapa catatan pembayaran
- Pembayaran dapat berupa DP, pelunasan, atau pembayaran biaya tambahan
- *verified_by* menunjukkan Admin yg melakukan verifikasi
- *paid_at*, *submitted_at*, dan *verified_at* memiliki fungsi berbeda

### F. Tabel `booking_inspections` (Pemeriksaan kondisi motor sebelum dan sesudah penyewaan)

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | PK, Auto Increment |
| `booking_id` | `BIGINT UNSIGNED` | FK, Referensi ke tabel `bookings` | 
| `inspection_type` | `ENUM('before', 'after')` | Pemeriksaan sebelum atau sesudah sewa |
| `inspected_by` | `BIGINT UNSIGNED` | FK, Referensi ke tabel `users` |
| `condition_notes` | `TEXT` | Catatan kondisi motor |
| `odometer` | `INT` | Kilometer pada odometer |
| `fuel_level` | `ENUM('low', 'medium', 'high')` | `Default('medium')` Kondisi bahan bakar |
| `photo_path`| `VARCHAR(255)` | Nullable, dokumentasi foto pemeriksaan |
| `inspection_date` | `DATETIME` | Waktu pemeriksaan |
| `created_at` | `TIMESTAMP` | Waktu pembuatan |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan |

#### Catatan
- Setiap booking idealnya memiliki dua pemeriksaan: *before* dan *after*
- Kombinasi *booking_id* dan *inspection_type* harus unik
- Pemeriksaan awal maksimal 1x dilakukan sebelum motor diserahkan kepada customer
- Pemeriksaan akhir maksimal 1x dilakukan setelah motor dikembalikan
- Foto dan catatan digunakan sebagai bukti kondisi kendaraan

### G. Tabel `damage_reports` (Menyimpan laporan kerusakan)

| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | PK, Auto Increment |
| `booking_inspection_id` | `BIGINT UNSIGNED` | FK, Relasi ke tabel `booking_inspections` |
| `description` | `TEXT` | Deskripsi kerusakan |
| `estimated_cost` | `DECIMAL(12, 2)` | Estimasi biaya perbaikan |
| `final_cost` | `DECIMAL(12, 2)` | Nullable, biaya akhir |
| `created_at` | `TIMESTAMP` | Waktu pembuatan |
| `updated_at` | `TIMESTAMP` | Waktu pembaruan |

#### Catatan
- Laporan kerusakan terhubung dengan pemeriksaan tertentu
- Kerusakan yg ditemukan pada pemeriksaan akhir dapat digunakan sebagai dasar penentuan biaya ganti rugi
- Satu pemeriksaan dapat memiliki beberapa laporan kerusakan
---

## 3. Primary Key dan Foreign Key
- **Tabel `users`**
  - Primary Key: `id`
  - Foreign Key: *- (Tidak ada)*
- **Tabel `motorcycles`**
  - Primary Key: `id`
  - Foreign Key: *- (Tidak ada)*
- **Tabel `motorcycle_units`**
  - Primary Key: `id`
  - Foreign Key: `motorcycle_id` merujuk ke `motorcycles(id)` (`ON DELETE RESTRICT`)
- **Tabel `bookings`**
  - Primary Key: `id`
  - Foreign Key 1: `user_id` merujuk ke `users(id)` (`ON DELETE RESTRICT`)
  - Foreign Key 2: `motorcycle_unit_id` merujuk ke `motorcycle_units(id)` (`ON DELETE RESTRICT`)
- **Tabel `payments`**
  - Primary Key: `id`
  - Foreign Key 1: `booking_id` merujuk ke `bookings(id)` (`ON DELETE RESTRICT`)
  - Foreign Key 2: `verified_by` merujuk ke `users(id)` (`ON DELETE SET NULL`)
- **Tabel `booking_inspections`**
  - Primary Key: `id`
  - Foreign Key 1: `booking_id` merujuk ke `bookings(id)` (`ON DELETE RESTRICT`)
  - Foreign Key 2: `inspected_by` merujuk ke `users(id)` (`ON DELETE RESTRICT`)
- **Tabel `damage_reports`**
  - Primary Key: `id`
  - Foreign Key 1: `booking_inspection_id` merujuk ke `booking_inspections(id)` (`ON DELETE RESTRICT`)

#### Catatan
- Semua tabel menggunakan id sebagai Primary Key
- Foreign Key menjaga integritas hubungan antartabel
- Data transaksi dan riwayat pembayaran tidak boleh dihapus sembarangan
- Booking yg dibatalkan tetap disimpan dengan status *cancelled*, bukan dihapus
---

## 4. Relasi Antar Tabel
1. **`motorcycles` ke `motorcycle_units` (One-to-Many / `1:N`)**
   - Satu jenis motor (misal: Honda Beat) dapat memiliki banyak unit fisik dengan plat nomor yang berbeda.
2. **`users` ke `bookings` (One-to-Many / `1:N`)**
   - Satu customer dapat melakukan banyak transaksi booking di waktu yang berbeda.
3. **`motorcycle_units` ke `bookings` (One-to-Many / `1:N`)**
   - Satu unit motor fisik dapat disewa berkali-kali pada jadwal/tanggal yang berbeda.
4. **`bookings` ke `payments` (One-to-Many / `1:N`)**
   - Satu transaksi booking dapat memiliki lebih dari satu catatan pembayaran (misalnya: pembayaran pertama untuk DP 50%, pembayaran kedua untuk pelunasan 50%, dan pembayaran ketiga jika ada denda).
5. **`users` (Admin) ke `payments` (One-to-Many / `1:N`)**
   - Satu admin dapat memverifikasi banyak transaksi pembayaran dari customer.
6. **`bookings` ke `booking_inspections` (One-to-many / `1:N`)**
   - Satu booking dapat mempunyai pemeriksaan sebelum dan sesudah penyewaan.
7. **`users` (Admin) ke `booking_inspections` (One-to-many / `1:N`)**
   - Satu admin dapat melakukan banyak pemeriksaan kendaraan
8. **`booking_inspections` ke `damage_reports` (One-to-Many / `1:N`)**
   - Satu pemeriksaan dapat mencatat beberapa kerusakan

---
