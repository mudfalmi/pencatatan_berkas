# Koneksi PostgreSQL / Supabase

Dokumen ini menjelaskan perbaikan error berikut:

```text
PDOException: could not find driver
```

Error tersebut **bukan** disebabkan oleh konfigurasi Laravel atau Supabase, tetapi karena
ekstensi PHP `pdo_pgsql` belum dimuat oleh PHP yang menjalankan aplikasi.

## 1. Perbaikan di Laragon / XAMPP (Windows)

Cek PHP yang benar-benar dipakai:

```powershell
php --ini
php -m | Select-String "pdo|pgsql"
```

Pada mesin ini, PHP yang aktif adalah `C:\xampp\php\php.exe` dengan file konfigurasi
`C:\xampp\php\php.ini`. Di file tersebut, baris berikut masih dikomentari:

```ini
;extension=pdo_pgsql
;extension=pgsql
;extension=intl
```

Ubah menjadi:

```ini
extension=pdo_pgsql
extension=pgsql
extension=intl
```

Langkah selanjutnya:

1. Simpan `php.ini` (backup sudah dibuat di `C:\xampp\php\php.ini.bak-pgsql`).
2. Restart Laragon / Apache / PHP-FPM.
3. Verifikasi:

```powershell
php -m | Select-String "pdo_pgsql|pgsql|intl"
php artisan config:clear
php artisan db:show
```

Jika Laragon memakai PHP lain (misalnya `D:\laragon\bin\php\php-8.x`), lakukan perubahan
yang sama pada `php.ini` di folder PHP yang aktif tersebut.

> **Catatan versi PHP Laragon:** Apache Laragon di mesin ini memakai PHP 8.1.10
> (`D:\laragon\bin\php\php-8.1.10-Win32-vs16-x64`), sedangkan Laravel 12 membutuhkan
> PHP >= 8.2. Jika aplikasi diakses lewat virtual host Laragon, tambahkan PHP 8.2
> melalui menu **Laragon -> PHP -> Version**, atau jalankan aplikasi dengan
> `php artisan serve` (memakai PHP CLI 8.2) / Docker. Tanpa itu, Laragon Apache akan
> menampilkan error `Composer detected issues ... require a PHP version ">= 8.2.0"`.

## 2. Perbaikan di server Linux

Debian/Ubuntu:

```bash
sudo apt-get update
sudo apt-get install -y php8.2-pgsql php8.2-intl
sudo systemctl restart php8.2-fpm apache2   # sesuaikan service yang dipakai
```

RHEL/CentOS/AlmaLinux:

```bash
sudo dnf install -y php-pgsql php-intl
sudo systemctl restart php-fpm httpd
```

Verifikasi dengan `php -m | grep -E "pdo_pgsql|pgsql|intl"`.

## 3. Host Supabase: gunakan Session Pooler (IPv4)

Host direct `db.<project-ref>.supabase.co` pada project baru **hanya memiliki record
IPv6**. Jaringan yang tidak mendukung IPv6 akan menghasilkan error seperti:

```text
could not translate host name "db.qbdwrucapuktnllbqoig.supabase.co" to address
```

Gunakan connection string **Session Pooler** dari
*Supabase Dashboard -> Project Settings -> Database -> Connection string -> Session pooler*.
Untuk project ini nilainya:

```dotenv
DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.qbdwrucapuktnllbqoig
DB_PASSWORD=...
DB_SSLMODE=require
```

Catatan:

- Username pooler selalu berbentuk `postgres.<project-ref>`.
- Gunakan **Session pooler (port 5432)** untuk aplikasi Laravel biasa dan untuk migrasi.
- **Transaction pooler (port 6543)** lebih cocok untuk serverless, tetapi tidak mendukung
  semua fitur prepared statement.
- `DB_SSLMODE=require` wajib untuk koneksi Supabase.

## 4. Deploy ke Railway

Error berikut di Railway:

```text
AH00534: apache2: Configuration error: More than one MPM loaded.
```

terjadi karena container menjalankan Apache dengan lebih dari satu MPM
(`mpm_event`/`mpm_worker` bersama `mpm_prefork`), atau karena Railway masih memakai
start command lama dari Procfile/buildpack (`heroku-php-apache2`).

Perbaikan yang sudah disiapkan:

- `railway.json` memaksa Railway memakai **Dockerfile** dan start command
  `app-entrypoint.sh apache2-foreground`.
- `Dockerfile` menghapus semua symlink MPM lalu hanya mengaktifkan `mpm_prefork`,
  serta menjalankan `apache2ctl -t` saat build agar konfigurasi Apache divalidasi.
- `docker/entrypoint.sh` menyesuaikan Apache dengan variabel `PORT` dari Railway
  (default `8080`).

Langkah deploy:

1. Pastikan **Root Directory** service Railway adalah root repo (bukan `public/`).
2. Tambahkan variables berikut di Railway (jangan mengandalkan file `.env`, karena
   `.env` tidak ikut ke dalam image):

   ```dotenv
   APP_NAME=pencatatan_berkas
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=base64:...                  # samakan dengan APP_KEY lokal
   APP_URL=https://domain-railway-anda
   LOG_CHANNEL=stderr
   TRUSTED_PROXIES=*

   DB_CONNECTION=pgsql
   DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
   DB_PORT=5432
   DB_DATABASE=postgres
   DB_USERNAME=postgres.qbdwrucapuktnllbqoig
   DB_PASSWORD=...
   DB_SSLMODE=require

   SESSION_DRIVER=database
   CACHE_STORE=database
   QUEUE_CONNECTION=database
   ```

3. Deploy ulang. Log build harus menampilkan `Using detected Dockerfile!`.
4. `preDeployCommand` di `railway.json` otomatis menjalankan
   `php artisan migrate --force` sebelum container start.
5. Generate domain Railway, lalu pastikan target port-nya sama dengan `PORT`
   container (default `8080`).

Jika error MPM masih muncul:

- Buka **Service Settings -> Deploy -> Custom Start Command**, kosongkan nilai lama
  seperti `vendor/bin/heroku-php-apache2 public/`. Nilai di dashboard bisa menimpa
  konfigurasi repo.
- Pastikan builder service diarahkan ke Dockerfile. Bisa juga tambahkan variable
  `RAILWAY_DOCKERFILE_PATH=Dockerfile`.
- Hapus/abaikan `Procfile` lama karena itu untuk Heroku buildpack, bukan Docker.

## 5. Menjalankan dengan Docker

File `Dockerfile` sudah menginstal `pdo_pgsql`, `pgsql`, `intl`, `bcmath`, `zip`, dan
`opcache`, serta mengarahkan Apache ke folder `public/`.

```bash
docker compose build --no-cache
docker compose up -d
docker compose exec app php artisan migrate --force
docker compose exec app php artisan storage:link
```

Aplikasi tersedia di `http://localhost:8080` (ubah dengan `APP_PORT`).

`docker-compose.yml` membaca variabel dari `.env` saat container berjalan. File `.env`
tidak dimasukkan ke dalam image (lihat `.dockerignore`), sehingga kredensial tidak
tersimpan di layer image.

## 6. Catatan keamanan

Password database sempat ditulis di dalam `.env` yang dibagikan. Sebaiknya ganti password
database dari Supabase Dashboard, lalu perbarui `DB_PASSWORD` di `.env` server. Jangan
commit file `.env` ke repository.
