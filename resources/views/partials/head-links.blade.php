{{-- Font --}}
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />

{{-- Bootstrap 5 + tema aplikasi, lalu utilitas Tailwind.
     theme.css memuat Bootstrap (di-vendor lokal) + tema panel, sedangkan
     app.css memuat utilitas Tailwind (sm:px-6, lg:px-8, space-y-4, dst.)
     yang dipakai layout admin, halaman login, dan welcome. Keduanya dimuat
     di <head> agar tidak ada kedipan (FOUC).
     Fallback CDN hanya dipakai bila aset build belum tersedia. --}}
@if (file_exists(public_path('build/manifest.json')))
  @vite(['resources/css/theme.css', 'resources/css/app.css'])
@else
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" />
@endif
