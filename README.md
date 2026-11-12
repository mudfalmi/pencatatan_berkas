# pencatatan_berkas
PD ponten Kemenag

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Optimasi Performa (Produksi)

Aplikasi ini sudah memakai query agregat, proyeksi kolom (`ringkas()`),
pagination 10 baris, index database, dan aset yang dibundel Vite. Agar
eksekusi backend dan pemuatan halaman di server produksi tetap cepat,
jalankan langkah berikut setiap kali deploy:

```bash
# 1. Build aset frontend (Bootstrap + tema + JS dibundel & diminifikasi,
#    tanpa request ke CDN). Folder public/build dipakai oleh @vite.
npm ci
npm run build

# 2. Cache konfigurasi, route, view, dan event dalam satu perintah.
php artisan optimize
#    Setara dengan:
#    php artisan config:cache
#    php artisan route:cache
#    php artisan view:cache
#    php artisan event:cache
```

Atau lewat script Composer yang sudah disediakan:

```bash
composer optimize          # php artisan optimize
composer optimize:clear    # bersihkan semua cache di atas
```

Catatan:

- Setiap kali mengubah `.env`, route, config, atau view di server, jalankan
  `php artisan optimize:clear` lalu `php artisan optimize` kembali.
- `php artisan optimize` **tidak** menjalankan `npm run build`; pastikan
  langkah build dijalankan lebih dulu agar manifest Vite terbaru.
- Untuk produksi, set `APP_ENV=production` dan `APP_DEBUG=false`, lalu
  gunakan `composer install --no-dev --optimize-autoloader`.

---

## Database PostgreSQL / Supabase

Aplikasi membutuhkan ekstensi PHP `pdo_pgsql` dan koneksi ke **Supabase Session
Pooler** (IPv4), bukan host `db.<project-ref>.supabase.co` yang hanya IPv6.
Panduan lengkap untuk Laragon, server Linux, dan Docker ada di
[DEPLOYMENT.md](DEPLOYMENT.md).

Verifikasi cepat:

```bash
php -m | grep pdo_pgsql
php artisan config:clear
php artisan db:show
```

### Deploy ke Railway

Railway harus memakai `Dockerfile` (dipaksa lewat `railway.json`), bukan Procfile
Heroku. Apache di image hanya mengaktifkan `mpm_prefork`, dan port mengikuti
variabel `PORT` dari Railway. Variabel `APP_KEY`, `APP_URL`, dan `DB_*` wajib diisi
di dashboard Railway. Langkah lengkapnya ada di
[DEPLOYMENT.md](DEPLOYMENT.md#4-deploy-ke-railway).

