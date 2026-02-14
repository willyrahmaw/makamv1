## Ringkasan Aplikasi

Aplikasi ini adalah sistem **digitalisasi makam** berbasis **Laravel** dengan fitur:

- **Halaman publik**: denah makam, blok, detail makam, laporan keuangan publik.
- **Panel admin**:
  - Manajemen data **makam**, **blok**, **sejarah**, dan **keuangan**.
  - Manajemen **admin** dengan role: **superadmin** (akses penuh) dan **admin** (tanpa hapus & tanpa pengaturan).
  - **Keamanan login**: captcha penjumlahan, rate limiting, ganti password dengan aturan kuat.
  - **Audit log** untuk CRUD & aktivitas login.

## Persyaratan Sistem

- **PHP** ≥ 8.1 (server Anda saat ini 8.3.27).
- **Composer**.
- **Database**: MySQL / MariaDB (atau kompatibel).
- **Ekstensi PHP** umum Laravel: `pdo_mysql`, `mbstring`, `openssl`, `json`, `curl`, `fileinfo`, dll.
- Web server: Apache / Nginx (atau yang kompatibel dengan PHP-FPM / mod_php).

## Langkah Instalasi

### 1. Clone / salin source code

Jika menggunakan git:

```bash
git clone <repo-url> makam_laravel
cd makam_laravel
```

Atau upload seluruh folder projek ini ke server (mis. `/var/www/makam_laravel`).

### 2. Install dependency PHP (Composer)

Di direktori root aplikasi:

**Linux / macOS (bash):**

```bash
composer install --no-dev --optimize-autoloader
```

**Windows PowerShell:**

```powershell
Set-Location "C:\LiveEnv\www\makam_laravel"
composer install
```

> Catatan: `composer.json` sudah di-set `platform.php` ke versi 8.3.27 agar kompatibel dengan server Anda.

### 3. Konfigurasi environment (`.env`)

Salin file contoh:

```bash
cp .env.example .env
```

Lalu edit `.env`:

- **Aplikasi**

```env
APP_NAME="Digitalisasi Makam"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID
APP_TIMEZONE=Asia/Jakarta
```

- **Database**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=nama_user
DB_PASSWORD=password
```

- **Session & keamanan (opsional)**

```env
SESSION_DRIVER=database   # atau file
SESSION_LIFETIME=120
```

### 4. Generate `APP_KEY`

```bash
php artisan key:generate
```

### 5. Migrasi database & seeder

Jalankan migrasi:

```bash
php artisan migrate --force
```

Jika Anda memakai PowerShell lokal:

```powershell
Set-Location "C:\LiveEnv\www\makam_laravel"
php artisan migrate --force
```

Jalankan seeder (opsional, untuk data awal admin, blok, dll. jika sudah disiapkan di `DatabaseSeeder`):

```bash
php artisan db:seed
```

**Admin default** (sesuai `AdminSeeder` saat ini):

- Email: `admin@makam.com`
- Password: `admin123`  
  (Sebaiknya segera diubah lewat menu **Ganti Password** setelah login pertama.)

### 6. Storage link

Untuk menampilkan file upload (logo situs, foto makam, dll.):

```bash
php artisan storage:link
```

### 7. Konfigurasi Virtual Host / Web Server

Arahkan **document root** ke folder `public`:

- Contoh Apache:

```apache
DocumentRoot "/var/www/makam_laravel/public"
<Directory "/var/www/makam_laravel/public">
    AllowOverride All
    Require all granted
</Directory>
```

Pastikan `.htaccess` di `public/` aktif (mod_rewrite enable).

### 8. Login Admin & Role

- Login admin: `https://domain-anda.com/admin/login`
- Setelah login sebagai **superadmin** (`admin@makam.com`):
  - Atur **Pengaturan Website** (warna blok, meta, logo).
  - Atur **Kontak Admin**.
  - Kelola **Admin lain** via menu **Manajemen Admin**.

**Role:**

- `superadmin`:
  - Semua menu & aksi (termasuk hapus, pengaturan, kontak, manajemen admin, log).
- `admin`:
  - Bisa tambah/edit data, **tidak bisa hapus** data utama.
  - Tidak bisa akses Pengaturan Website, Kontak Admin, Manajemen Admin, dan Log Aktivitas.

### 9. Keamanan Tambahan

- **Captcha**: login admin menggunakan captcha penjumlahan angka.
- **Password policy**:
  - Minimal 8 karakter.
  - Wajib ada **huruf besar**, **huruf kecil**, dan **simbol** (mis. `@#$%^&*`).
- **Session**:
  - Session diregenerasi saat login/logout.
  - Durasi habis sesi diatur lewat `SESSION_LIFETIME`.
- **Audit Log**:
  - Log aktivitas CRUD & auth dicatat di database.
  - Menu **Log Aktivitas**:
    - Superadmin: melihat semua log.
    - Admin biasa: hanya melihat log aktivitas akun sendiri.

## Perintah Bermanfaat

- Cek cache & optimasi:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

- Membersihkan cache:

```bash
php artisan optimize:clear
```

