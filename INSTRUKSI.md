```markdown
# Instruksi Pembuatan Aplikasi Sistem Pemesanan Lapangan Jakabaring (SPLJ)

Anda adalah seorang AI Web Developer ahli. Tugas Anda adalah mengimplementasikan rancangan perangkat lunak "Sistem Pemesanan Lapangan Jakabaring (SPLJ)" menjadi sebuah aplikasi web fungsional. 

Gunakan tumpukan teknologi (Tech Stack) berikut:
- **Backend:** PHP Native (Tanpa Framework)
- **Database:** MySQL
- **Frontend/UI:** HTML5, CSS3, dan **Bootstrap 5** (atau Tailwind CSS)
- **Arsitektur:** Adaptasi dari 4-Layer Architecture (Presentation, Business Logic, Data Access, Database) ke pola MVC sederhana di PHP Native.

## 1. Desain Basis Data (MySQL)
Buat script SQL untuk meng-generate database `db_splj` dengan tabel-tabel utama yang merepresentasikan "Basis Classes". Berikut adalah atribut-atribut yang harus ada:

*   **Tabel `users`:** 
    *   `user_id` (INT, PK, Auto Increment)
    *   `nama` (VARCHAR)
    *   `email` (VARCHAR, Unique)
    *   `password` (VARCHAR, Hashed)
    *   `nomor_whatsapp` (VARCHAR)
    *   `role_type` (ENUM: 'User', 'Admin', 'Koordinator')
    *   `membership_status` (VARCHAR)
    *   `total_point` (INT)
*   **Tabel `lapangan`:**
    *   `lapangan_id` (INT, PK, Auto Increment)
    *   `nama_lapangan` (VARCHAR)
    *   `jenis_lapangan` (VARCHAR) (Contoh: Futsal, Tenis, Badminton)
    *   `harga_per_jam` (DECIMAL)
    *   `status_lapangan` (ENUM: 'Tersedia', 'Maintenance')
*   **Tabel `booking`:**
    *   `booking_id` (INT, PK, Auto Increment)
    *   `user_id` (INT, FK ke `users`)
    *   `lapangan_id` (INT, FK ke `lapangan`)
    *   `tanggal` (DATE)
    *   `jam_mulai` (TIME)
    *   `durasi` (INT) // dalam jam
    *   `status_booking` (ENUM: 'Pending DP', 'Menunggu Verifikasi', 'Confirmed', 'Rejected', 'Canceled')
    *   `total_biaya` (DECIMAL)
*   **Tabel `pembayaran`:**
    *   `pembayaran_id` (INT, PK, Auto Increment)
    *   `booking_id` (INT, FK ke `booking`)
    *   `payment_type` (ENUM: 'DP', 'Pelunasan', 'Refund')
    *   `jumlah_bayar` (DECIMAL)
    *   `bukti_transfer` (VARCHAR) // path file gambar
    *   `status_pembayaran` (ENUM: 'Pending', 'Verified', 'Rejected')

## 2. Struktur Direktori Proyek
Buat file dan folder dengan struktur berikut:
```text
/splj-app
│
├── /config
│   └── koneksi.php           # File koneksi PDO/MySQLi ke db_splj
│
├── /models                   # Data Access Layer
│   ├── User.php
│   ├── Booking.php
│   └── Lapangan.php
│
├── /controllers              # Business Logic Layer
│   ├── AuthController.php    # Handle Login/Register
│   ├── BookingController.php # Handle validasi jadwal & biaya
│   └── PaymentController.php # Handle upload bukti DP & verifikasi
│
├── /views                    # Presentation Layer (UI dengan Bootstrap 5)
│   ├── layout/
│   │   ├── header.php
│   │   └── footer.php
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── user/
│   │   ├── dashboard.php
│   │   ├── booking_form.php
│   │   └── upload_dp.php
│   └── admin/
│       ├── dashboard.php
│       └── verifikasi_pembayaran.php
│
├── /uploads                  # Folder menyimpan bukti transfer (bukti_transfer)
├── index.php                 # Entry point (Routing sederhana)
└── style.css                 # Custom CSS
```

## 3. Instruksi Langkah-demi-Langkah untuk Agent

Tolong buatkan kode PHP untuk langkah-langkah berikut secara berurutan:

**Langkah 1: `koneksi.php`**
Buat file `config/koneksi.php` menggunakan `mysqli` atau `PDO` untuk terhubung ke `db_splj`. Pastikan penanganan error (error handling) disertakan.

**Langkah 2: Autentikasi (Registrasi & Login)**
*   Buat `views/auth/register.php` dengan form: Nama, Email, WA, Password.
*   Buat `views/auth/login.php` dengan form: Email, Password.
*   Buat logika di `AuthController.php` untuk menyimpan user baru (default role 'User'), mengenkripsi password, memvalidasi login, menyimpan sesi (`$_SESSION['user_id']`, `$_SESSION['role_type']`), dan me-redirect ke dashboard yang sesuai (Admin/User).

**Langkah 3: Pemesanan Lapangan (Booking)**
*   Buat UI `views/user/booking_form.php` menggunakan Bootstrap 5 yang menampilkan daftar lapangan dan form input (Tanggal, Jam, Durasi).
*   Di `BookingController.php`, buat metode (method) `createBooking()` yang menghitung `total_biaya` (`harga_per_jam` * `durasi`) dan memastikan tidak ada bentrok jadwal (Schedule Validator).
*   Set status awal booking menjadi "Pending DP".

**Langkah 4: Upload Bukti DP (User)**
*   Buat UI `views/user/upload_dp.php` dengan form upload file gambar.
*   Buat logika PHP untuk memindahkan file ke folder `/uploads`, mencatat record di tabel `pembayaran`, dan mengubah `status_booking` menjadi "Menunggu Verifikasi".

**Langkah 5: Verifikasi Pembayaran (Admin)**
*   Buat UI `views/admin/verifikasi_pembayaran.php` berupa tabel yang menampilkan daftar booking dengan status "Menunggu Verifikasi" beserta tombol/link untuk melihat gambar `bukti_transfer`.
*   Berikan tombol "Approve" dan "Reject". Jika di-approve, ubah `status_booking` menjadi "Confirmed". Jika di-reject, ubah menjadi "Rejected".

**Catatan Tambahan untuk AI Agent:**
*   Setiap file UI (`.php` di dalam folder `views`) harus menggunakan komponen Card, Table, Form, dan Navbar dari Bootstrap 5 (via CDN).
*   Desain antarmuka harus *mobile responsive*.
*   Tuliskan kode PHP dengan komentar yang jelas untuk menjelaskan setiap alur logika.
```