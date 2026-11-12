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
  semua fitur level sesi (prepared statement, `search_path`, dan koneksi persisten).
  Bila tetap memakai port 6543, set `DB_PERSISTENT=false`.
- `DB_SSLMODE=require` wajib untuk koneksi Supabase.
- `DB_PERSISTENT=true` membuat koneksi TCP/TLS ke pooler dipakai ulang antar-request,
  sehingga handshake tidak terjadi pada setiap klik. Ini aman untuk Session pooler
  dengan jumlah worker terbatas (container ini memakai 4 worker).

## 4. Deploy ke Railway

Deployment sekarang memakai **PHP built-in server** di atas image `php:8.2-cli`,
bukan Apache. Dengan begitu error Apache seperti
`AH00534: More than one MPM loaded` tidak lagi relevan.

Yang sudah disiapkan:

- `railway.json` memaksa Railway memakai **Dockerfile** (bukan Procfile/Heroku
  buildpack) dan start command:
  `php -S 0.0.0.0:${PORT:-8080} -t public`.
- `Dockerfile` memakai `php:8.2-cli` dan menginstal ekstensi `pdo_pgsql`, `pgsql`,
  `intl`, `bcmath`, `zip`, dan `opcache`, lalu menjalankan
  `composer install --no-dev`.
- Stage `node:22-alpine` menjalankan `npm ci && npm run build`, kemudian hasil
  `public/build` disalin ke image final. Jadi manifest Vite selalu baru walaupun
  folder build tidak ikut di repository.
- `docker/entrypoint.sh` membuat folder writable, menjalankan
  `php artisan storage:link --force`, lalu menjalankan server dengan `$PORT`.
- `AppServiceProvider` memanggil `URL::forceHttps()` saat production agar URL CSS,
  JS, dan logo tidak menjadi Mixed Content.

> **Penting:** jangan memakai `public/index.php` sebagai router script
> (`php -S ... index.php`). Router tersebut membuat request file statis seperti
> `/build/...css` dan `/images/logo-kemenag.png` ikut diproses Laravel sehingga
> menghasilkan redirect/404. Gunakan `-t public` seperti konfigurasi di repo ini.

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
   DB_PERSISTENT=true

   SESSION_DRIVER=cookie
   SESSION_SECURE_COOKIE=true
   CACHE_STORE=file
   QUEUE_CONNECTION=database
   ```

   > **Penting:** nilai di dashboard Railway menimpa default di `Dockerfile`.
   > Jika service masih menyimpan `SESSION_DRIVER=database` atau
   > `CACHE_STORE=database`, ubah/hapus variabel tersebut. Setiap request halaman
   > akan melakukan beberapa round-trip tambahan ke Supabase (session read/write
   > dan cache read) bila driver database masih dipakai.

3. Deploy ulang. Log build harus menampilkan `Using detected Dockerfile!` dan log
   runtime kurang lebih `PHP 8.2.x Development Server (http://0.0.0.0:8080) started`.
4. `preDeployCommand` di `railway.json` otomatis menjalankan
   `php artisan migrate --force` sebelum container start.
5. Generate domain Railway, lalu pastikan target port service sama dengan `PORT`
   container (default `8080`).

Jika masih memakai konfigurasi lama:

- Buka **Service Settings -> Deploy -> Custom Start Command**, kosongkan nilai lama
  seperti `vendor/bin/heroku-php-apache2 public/` atau `apache2-foreground`.
  Nilai di dashboard menimpa `railway.json`.
- Pastikan builder service diarahkan ke Dockerfile. Bisa juga tambahkan variable
  `RAILWAY_DOCKERFILE_PATH=Dockerfile`.
- Abaikan/hapus `Procfile` lama karena itu untuk Heroku buildpack, bukan Docker.

Verifikasi aset setelah deploy:

```bash
curl -I https://domain-railway-anda/build/manifest.json
curl -I https://domain-railway-anda/images/logo-kemenag.png
curl -I https://domain-railway-anda/build/assets/theme-9mBLujFM.css
```

Ketiganya harus mengembalikan `200` (bukan `302`/`404`), dan URL di HTML login harus
diawali `https://`. Jika masih Mixed Content, pastikan `APP_ENV=production` dan
`APP_URL=https://...`, lalu jalankan `php artisan optimize:clear` setelah mengubah
variable.

## 5. Menjalankan dengan Docker

File `Dockerfile` memakai `php:8.2-cli` + PHP built-in server, dengan stage
`node:22-alpine` untuk membangun aset Vite. Ekstensi PHP yang tersedia:
`pdo_pgsql`, `pgsql`, `intl`, `bcmath`, `zip`, dan `opcache`.

```bash
docker compose build --no-cache
docker compose up -d
docker compose exec app php artisan migrate --force
```

`storage:link` sudah dijalankan otomatis oleh entrypoint setiap container start,
tetapi bisa juga dijalankan manual:

```bash
docker compose exec app php artisan storage:link --force
```

Aplikasi tersedia di `http://localhost:8080` (ubah dengan `APP_PORT`).

`docker-compose.yml` membaca variabel dari `.env` saat container berjalan. File `.env`
tidak dimasukkan ke dalam image (lihat `.dockerignore`), sehingga kredensial tidak
tersimpan di layer image.

## 6. Optimasi performa (navigasi terasa lambat)

Penyebab utama navigasi lambat bukan render Blade, melainkan **jumlah round-trip
ke database Supabase**. Sebelum optimasi, satu halaman bisa mengirim 6–9 query
berurutan: baca session, ambil user untuk auth, baca cache badge, query agregat,
query daftar, lalu tulis session di akhir request. Dengan RTT 150–250 ms dari
region yang berjauhan, angka itu mudah menjadi ~2 detik per klik.

Yang sudah diterapkan di repository ini:

1. **Cache framework dibuat saat container start** (`docker/entrypoint.sh`),
   bukan saat image di-build. `php artisan optimize` menjalankan
   `config:cache`, `event:cache`, `route:cache`, dan `view:cache`. Cache harus
   dibuat saat runtime karena Railway baru menyuntikkan `APP_KEY`/`DB_*` ketika
   container berjalan; bila di-cache saat build, nilai yang terbaca bisa salah.
2. **OPcache diaktifkan untuk CLI** (`Dockerfile`) karena server produksi memakai
   `php -S` (SAPI CLI). Tanpa `opcache.enable_cli=1`, OPcache tidak bekerja.
3. **Koneksi PostgreSQL persisten** (`config/database.php`) lewat
   `PDO::ATTR_PERSISTENT`, diatur dengan `DB_PERSISTENT` (default `true`).
   Handshake TCP + TLS + autentikasi hanya terjadi sekali per worker.
4. **Session & cache tidak lagi memakai database cloud** secara default.
   Session memakai `file`/`cookie`, cache memakai `file`.
5. **Agregat dashboard di-cache 60 detik** (`Surat::ringkasan()` dan
   `User::ringkasan()`) dan otomatis dihapus saat ada data yang dibuat,
   diubah, atau dihapus.

### 6.1 Variabel Railway yang disarankan

Buka **Service → Variables**, lalu pastikan nilainya seperti berikut
(atau hapus variabel lama yang menimpanya):

```dotenv
APP_ENV=production
APP_DEBUG=false

DB_CONNECTION=pgsql
DB_HOST=aws-1-ap-southeast-2.pooler.supabase.com
DB_PORT=5432                 # Session Pooler, bukan 6543
DB_DATABASE=postgres
DB_USERNAME=postgres.<project-ref>
DB_PASSWORD=...
DB_SSLMODE=require
DB_PERSISTENT=true

SESSION_DRIVER=cookie        # atau file bila hanya satu instance
SESSION_SECURE_COOKIE=true   # cookie hanya dikirim lewat HTTPS
CACHE_STORE=file
```

Pilihan driver session:

| Driver | Kelebihan | Kekurangan |
| --- | --- | --- |
| `cookie` | Tanpa penyimpanan server, tahan restart, cocok untuk container stateless | Data session dibatasi ~4 KB; cookie terenkripsi Laravel |
| `file` | Cepat, sederhana | Hilang saat redeploy; tidak dibagi antar-replica |
| `redis` | Paling cepat & dibagi antar-replica | Perlu layanan Redis + ekstensi PHP `redis` |

Redis bersifat opsional. Bila ingin memakainya, tambahkan layanan Redis di
Railway, set `REDIS_URL`, lalu tambahkan ekstensi berikut ke tahap `phpbase`
pada `Dockerfile`:

```dockerfile
RUN pecl install redis && docker-php-ext-enable redis
```

dan ubah variabel menjadi `SESSION_DRIVER=redis`, `CACHE_STORE=redis`.

### 6.2 Dekatkan region aplikasi dengan database

Host Supabase pada proyek ini berada di `ap-southeast-2` (Sydney). Pilih region
Railway yang terdekat (Southeast Asia/Singapore bila tersedia). Selisih region
bisa menambah 100–200 ms **per query**, sehingga dampaknya lebih besar daripada
optimasi kode apa pun.

### 6.3 Verifikasi setelah deploy

```bash
# Jalankan di shell container (Railway) atau `docker compose exec app ...` lokal.

# Cache framework harus terbentuk (config.php, routes-v7.php, services.php).
ls -l bootstrap/cache

# Pastikan driver yang benar-benar dipakai sudah berubah.
php artisan about --only=environment
php artisan about --only=cache
```

Di log container, `php artisan optimize` akan menampilkan
`Configuration cached successfully`, `Routes cached successfully`, dan
`Blade templates cached successfully`. Setelah mengubah variabel Railway,
redeploy/restart container agar entrypoint membangun ulang cache.

Saat men-debug (misalnya setelah mengubah config), bersihkan cache dengan:

```bash
php artisan optimize:clear
```

### 6.4 Catatan N+1 query

Saat ini model `Surat` dan `User` **tidak memiliki relasi Eloquent**, sehingga
tidak ada N+1 yang perlu di-eager-load. Controller sudah:

- memakai scope `ringkas()` yang hanya mengambil kolom yang ditampilkan
  (`tanda_tangan` base64 yang besar tidak ikut dimuat),
- menggabungkan hitungan statistik menjadi satu query agregat,
- memakai `COUNT(file_dokumen)` alih-alih pengecekan file per baris.

`Model::preventLazyLoading()` juga sudah aktif di luar production sehingga relasi
yang lupa di-eager-load akan langsung terdeteksi. Jika nanti ditambahkan relasi
(misalnya surat dimiliki seorang petugas), gunakan eager loading:

```php
Surat::with(['petugas:id,nama_lengkap'])
    ->ringkas()
    ->paginate(10);
```

Untuk mengukur jumlah query per halaman, gunakan Laravel Debugbar/Telescope di
lokal atau `DB::listen()` di `AppServiceProvider`.

Pencarian `LIKE '%kata%'` tidak dapat memakai index B-tree biasa. Bila jumlah
surat sudah sangat besar (puluhan ribu), pertimbangkan index trigram:

```sql
CREATE EXTENSION IF NOT EXISTS pg_trgm;
CREATE INDEX surat_perihal_trgm_idx ON surat USING gin (perihal gin_trgm_ops);
```

## 7. Catatan keamanan

Password database sempat ditulis di dalam `.env` yang dibagikan. Sebaiknya ganti password
database dari Supabase Dashboard, lalu perbarui `DB_PASSWORD` di `.env` server. Jangan
commit file `.env` ke repository.
