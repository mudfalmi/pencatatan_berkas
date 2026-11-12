/*
 * Mode Tampilan (Terang / Gelap)
 * ---------------------------------------------------------
 * Script ini dimuat sinkron di dalam <head> (sebelum body
 * di-render) supaya tidak ada kedipan tema (FOUC) saat
 * halaman dimuat.
 *
 * Cara kerja:
 *  - Preferensi disimpan di localStorage dengan key
 *    "si-berkas-theme" (nilai: "light" | "dark").
 *  - Class "dark" pada <html> mengaktifkan varian `dark:`
 *    ala Tailwind (lihat theme.css).
 *  - Atribut data-bs-theme pada <html> mengaktifkan dark
 *    mode native Bootstrap 5.3.
 *  - Kalau user belum pernah memilih, preferensi mengikuti
 *    pengaturan sistem operasi (prefers-color-scheme).
 */
(function () {
  'use strict';

  var STORAGE_KEY = 'si-berkas-theme';
  var root = document.documentElement;

  function readStoredTheme() {
    try {
      return window.localStorage.getItem(STORAGE_KEY);
    } catch (e) {
      return null;
    }
  }

  function storeTheme(theme) {
    try {
      window.localStorage.setItem(STORAGE_KEY, theme);
    } catch (e) {
      /* localStorage diblokir (mode privat) -> abaikan */
    }
  }

  function systemPrefersDark() {
    return !!(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
  }

  function resolveTheme() {
    var stored = readStoredTheme();
    if (stored === 'dark' || stored === 'light') {
      return stored;
    }
    return systemPrefersDark() ? 'dark' : 'light';
  }

  /**
   * Terapkan tema ke <html> + sinkronkan tombol toggle.
   */
  function applyTheme(theme) {
    var isDark = theme === 'dark';

    root.classList.toggle('dark', isDark);
    root.setAttribute('data-bs-theme', isDark ? 'dark' : 'light');

    var buttons = document.querySelectorAll('[data-theme-toggle]');
    for (var i = 0; i < buttons.length; i++) {
      var button = buttons[i];

      button.setAttribute('aria-pressed', isDark ? 'true' : 'false');
      button.setAttribute('aria-label', isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
      button.setAttribute('title', isDark ? 'Mode terang' : 'Mode gelap');

      var moon = button.querySelector('[data-theme-icon="moon"]');
      var sun = button.querySelector('[data-theme-icon="sun"]');

      if (moon) {
        moon.classList.toggle('d-none', isDark);
      }
      if (sun) {
        sun.classList.toggle('d-none', !isDark);
      }
    }
  }

  function toggleTheme() {
    var next = root.classList.contains('dark') ? 'light' : 'dark';
    storeTheme(next);
    applyTheme(next);
  }

  // Terapkan segera: script ini berada di <head>, jadi tema
  // sudah benar sebelum halaman digambar (tanpa FOUC).
  applyTheme(resolveTheme());

  // Tombol toggle belum ada saat script ini berjalan (masih di
  // dalam <head>), jadi sinkronkan ulang ikon/aria setelah DOM
  // siap agar tampilan tombol sesuai tema aktif.
  function syncWhenReady() {
    applyTheme(resolveTheme());
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', syncWhenReady);
  } else {
    syncWhenReady();
  }

  // Klik tombol toggle (delegasi supaya aman walau tombol
  // ditambahkan setelah DOM siap).
  document.addEventListener('click', function (event) {
    var target = event.target;
    var button = target && target.closest ? target.closest('[data-theme-toggle]') : null;

    if (!button) {
      return;
    }

    event.preventDefault();
    toggleTheme();
  });

  // Ikuti perubahan tema sistem selama user belum memilih manual.
  if (window.matchMedia) {
    var query = window.matchMedia('(prefers-color-scheme: dark)');
    var onSystemChange = function () {
      if (!readStoredTheme()) {
        applyTheme(query.matches ? 'dark' : 'light');
      }
    };

    if (query.addEventListener) {
      query.addEventListener('change', onSystemChange);
    } else if (query.addListener) {
      query.addListener(onSystemChange);
    }
  }
})();
