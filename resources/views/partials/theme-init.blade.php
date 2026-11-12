{{-- Inisialisasi tema sebelum halaman digambar (mencegah FOUC).
     Sengaja di-inline agar tidak ada request script yang memblokir render;
     handler tombol toggle tetap dimuat lewat bundle app.js. --}}
<script>
  (function () {
    var key = 'si-berkas-theme';
    var stored = null;

    try {
      stored = window.localStorage.getItem(key);
    } catch (e) {
      /* localStorage diblokir -> ikuti preferensi sistem */
    }

    var isDark = stored === 'dark' || (stored !== 'light'
      && window.matchMedia
      && window.matchMedia('(prefers-color-scheme: dark)').matches);

    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');
  })();
</script>
