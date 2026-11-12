
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
<?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/partials/theme-init.blade.php ENDPATH**/ ?>