## Digitalisasi Makam – Dokumentasi Proyek

### 1. Deskripsi Singkat

Proyek ini adalah aplikasi **digitalisasi makam** berbasis **Laravel**, digunakan untuk:

- Menampilkan **denah makam**, blok, dan detail makam ke publik.
- Menyajikan **laporan keuangan publik**.
- Menyediakan **panel admin** untuk mengelola:
  - Data makam dan blok makam.
  - Sejarah desa.
  - Transaksi keuangan.
  - Pengaturan website dan kontak admin.
  - Manajemen akun admin.

### 2. Fitur Utama

- **Halaman publik**
  - Denah & blok makam.
  - Pencarian makam.
  - Laporan keuangan publik.

- **Panel Admin**
  - CRUD data makam, blok, sejarah, keuangan.
  - Manajemen admin (tambah/edit/hapus admin lain – khusus superadmin).
  - Pengaturan warna blok, logo, dan konten informatif.
  - Pengaturan kontak admin.
  - **Ganti password** untuk akun admin yang sedang login.

- **Role & Akses**
  - **Superadmin**:
    - Akses penuh, termasuk hapus data, pengaturan website, kontak admin, manajemen admin, dan melihat seluruh **log aktivitas**.
  - **Admin**:
    - Bisa tambah/edit data, **tidak bisa hapus data utama**.
    - Tidak bisa mengakses Pengaturan Website, Kontak Admin, dan Manajemen Admin.
    - Bisa membuka menu **Log Aktivitas**, tetapi **hanya melihat log aktivitas miliknya sendiri**.

### 3. Teknologi

- **Framework**: Laravel (PHP ≥ 8.1).
- **Basis data**: MySQL / MariaDB.
- **Front-end**: Blade, Bootstrap 5, Bootstrap Icons.
- **Lainnya**: Chart.js untuk grafik keuangan.

---

## Instalasi & Konfigurasi

> Untuk panduan instalasi lebih detail, lihat juga `README_install.md`.  
> Di bawah ini ringkasan langkah dari nol sampai siap jalan.

### 1. Persyaratan

- PHP ≥ 8.1 (projek ini dikonfigurasi untuk berjalan di PHP **8.3.27**).
- Composer.
- MySQL / MariaDB.
- Web server (Apache/Nginx) dengan document root diarahkan ke folder `public/`.

### 2. Clone / Salin Proyek

```bash
git clone <repo-url> makam_laravel
cd makam_laravel
```

Atau upload folder proyek ke server (mis. `/var/www/makam_laravel`).

### 3. Install Dependency

```bash
composer install
```

> Catatan: `composer.json` sudah mengatur platform PHP untuk kompatibel dengan server 8.3.27.

### 4. Konfigurasi `.env`

Salin file contoh:

```bash
cp .env.example .env
```

Lalu sesuaikan nilai penting:

```env
APP_NAME="Digitalisasi Makam"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_user
DB_PASSWORD=password

SESSION_DRIVER=database   # atau file
SESSION_LIFETIME=120
```

### 5. Generate APP_KEY

```bash
php artisan key:generate
```

### 6. Migrasi & Seeder

```bash
php artisan migrate --force
php artisan db:seed   # jika DatabaseSeeder sudah mengatur seeder yang dibutuhkan
```

**Admin awal** (dari `AdminSeeder` saat ini):

- Email: `admin@makam.com`
- Password: `admin123`  
  → **wajib diganti** setelah login pertama lewat menu **Ganti Password**.

### 7. Storage Link

```bash
php artisan storage:link
```

### 8. Konfigurasi Web Server

Pastikan document root mengarah ke `public/`, contoh Apache:

```apache
DocumentRoot "/var/www/makam_laravel/public"
<Directory "/var/www/makam_laravel/public">
    AllowOverride All
    Require all granted
</Directory>
```

---

## Penggunaan Aplikasi

### 1. Login Admin

- URL login admin: `https://domain-anda.com/admin/login`
- Login dengan akun admin yang sudah tersedia / disediakan seeder.

### 2. Menu Utama Admin

- **Dashboard**: ringkasan data.
- **Data Makam / Blok / Sejarah / Keuangan**: CRUD data sesuai hak akses role.
- **Pengaturan Website** (superadmin):
  - Warna blok, meta SEO, logo, dsb.
- **Kontak Admin** (superadmin):
  - Telepon, email, alamat, jam layanan.
- **Manajemen Admin** (superadmin):
  - Tambah, edit, hapus admin lain, atur role (`admin` / `superadmin`).
- **Ganti Password**:
  - Ganti password akun yang sedang login (superadmin maupun admin).
- **Log Aktivitas**:
  - Superadmin: melihat semua log.
  - Admin biasa: hanya log miliknya sendiri.

---

## Keamanan & Sekuriti

### 1. Autentikasi & Session

- Menggunakan **guard `admin`** (Laravel Auth) untuk admin panel.
- **Session**:
  - Driver default `database` (atau `file` jika diubah di `.env`).
  - `SESSION_LIFETIME` mengatur durasi idle (menit).
  - Session diregenerasi saat login (`regenerate()`) dan logout (`invalidate()`, `regenerateToken()`), untuk mencegah **session fixation**.
  - Session ID disimpan di cookie, **tidak di URL**.

### 2. Role & Izin

- Tabel `admins` memiliki kolom `role` (`superadmin` / `admin`).
- **Superadmin**:
  - Bisa hapus data, mengakses pengaturan, kontak, manajemen admin, melihat seluruh log.
- **Admin**:
  - Tidak bisa menghapus data utama (dicegah di middleware `forbid_admin_delete` + pengecekan di controller).
  - Tidak bisa mengakses menu superadmin-only (disembunyikan di UI dan dibatasi di route/controller).

### 3. Kebijakan Password

Di form:

- **Tambah admin**, **edit admin** (jika mengubah password), dan **Ganti Password** menggunakan aturan:
  - Minimal **8 karakter**.
  - Wajib mengandung:
    - **Huruf besar**,
    - **Huruf kecil**,
    - **Simbol** (mis. `@#$%^&*`).
- Password di-hash menggunakan **`Hash::make()`** (bcrypt, sesuai konfigurasi Laravel).

### 4. Proteksi Login

- **Captcha manual**: penjumlahan angka sederhana pada form login admin.
- **Rate limiting login**:
  - Maksimal percobaan login per IP dalam jangka waktu tertentu (menggunakan `RateLimiter` Laravel).
  - Jika terlampaui, user mendapat pesan menunggu sebelum mencoba lagi.

### 5. Audit Log Aktivitas

- Tabel `activity_logs` menyimpan:
  - `admin_id`, `event`, `model_type`, `model_id`, `route`, `method`, `ip`, `user_agent`, `payload`, timestamps.
- Dicatat untuk:
  - CRUD data makam, blok, keuangan, sejarah, pengaturan, kontak, admin.
  - Login sukses/gagal, logout, rate-limit login, ganti password.
- **Akses:**
  - **Superadmin**: melihat seluruh log.
  - **Admin**: hanya log miliknya sendiri.

### 6. Praktik Tambahan yang Disarankan

- **Ganti password default** admin segera setelah instalasi.
- Gunakan **HTTPS** (SSL/TLS) di server produksi.
- Atur **backup database** rutin.
- Batasi akses ke server (SSH key, firewall, dsb.).
- Nonaktifkan `APP_DEBUG` di produksi (`APP_DEBUG=false`).

---

## Perintah Artisan Berguna

```bash
# Optimasi konfigurasi & route
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Bersihkan seluruh cache & file hasil optimasi
php artisan optimize:clear
```
