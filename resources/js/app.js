/*
 * Entry point JavaScript (Vite).
 *
 * Bootstrap 5 (dropdown/modal) di-vendor lokal dan ikut di-bundle,
 * sehingga tidak ada request JS tambahan ke CDN. Script tema dan
 * panel admin juga diimpor di sini agar ikut diminifikasi dan diberi
 * hash oleh Vite saat `npm run build`.
 * axios sengaja tidak dipakai.
 */
import '../vendor/bootstrap/bootstrap.bundle.min.js';
import '../../public/assets/js/theme.js';
import '../../public/assets/js/admin.js';
