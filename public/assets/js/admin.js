/*
 * Panel admin: sidebar toggle + handler modal konfirmasi hapus
 * (hapus akun pengguna & hapus surat beserta arsip digitalnya).
 */
(function () {
  'use strict';

  /* Sidebar toggle (mobile) */
  document.querySelectorAll('[data-sidebar-toggle]').forEach(function (el) {
    el.addEventListener('click', function () {
      document.body.classList.toggle('sidebar-show');
    });
  });

  var backdrop = document.querySelector('.sidebar-backdrop');
  if (backdrop) {
    backdrop.addEventListener('click', function () {
      document.body.classList.remove('sidebar-show');
    });
  }

  /* Tampilkan / sembunyikan password */
  document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var input = document.getElementById(btn.getAttribute('data-password-toggle'));
      if (!input) {
        return;
      }

      var show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
      btn.setAttribute('aria-pressed', show ? 'true' : 'false');

      var iconOpen = btn.querySelector('[data-icon-eye]');
      var iconOff = btn.querySelector('[data-icon-eye-off]');
      if (iconOpen) {
        iconOpen.classList.toggle('d-none', show);
      }
      if (iconOff) {
        iconOff.classList.toggle('d-none', !show);
      }
    });
  });

  /* ---------------------------------------------------------------
   * Modal konfirmasi hapus
   *
   * Modal dibuat manual (bukan Bootstrap Modal) supaya:
   * - box modal selalu di atas backdrop (klik tombol tidak terhalang),
   * - bisa ditutup dengan Escape maupun klik di luar area modal.
   *
   * Setiap modal pada halaman dilayout secara generik:
   *   <button data-delete-modal="<id modal>" data-url="..." data-name="...">
   *   <form data-delete-form> ... <strong data-delete-name></strong>
   * ------------------------------------------------------------- */
  var modalHandlers = new WeakMap();

  document.querySelectorAll('.confirm-modal').forEach(function (modal) {
    var form = modal.querySelector('[data-delete-form]');
    var nameEl = modal.querySelector('[data-delete-name]');
    var lastFocusedEl = null;

    function open(trigger) {
      if (form) {
        form.action = trigger.getAttribute('data-url') || '';
      }

      if (nameEl) {
        nameEl.textContent = trigger.getAttribute('data-name') || 'data ini';
      }

      lastFocusedEl = document.activeElement;
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('modal-locked');

      var confirmBtn = modal.querySelector('[data-modal-confirm]');
      if (confirmBtn) {
        setTimeout(function () {
          confirmBtn.focus();
        }, 80);
      }
    }

    function close() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('modal-locked');

      if (lastFocusedEl && typeof lastFocusedEl.focus === 'function') {
        lastFocusedEl.focus();
      }
    }

    modalHandlers.set(modal, { open: open, close: close });

    /* Tombol silang (X) dan tombol Batal */
    modal.querySelectorAll('[data-modal-close]').forEach(function (el) {
      el.addEventListener('click', function (event) {
        event.preventDefault();
        close();
      });
    });

    /* Klik di luar kotak modal (area backdrop) menutup modal */
    modal.addEventListener('mousedown', function (event) {
      if (!event.target.closest('.confirm-modal__box')) {
        close();
      }
    });

    /* Tekan Escape menutup modal */
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && modal.classList.contains('is-open')) {
        close();
      }
    });
  });

  /* Tombol pemicu hapus (akun pengguna / surat) */
  document.querySelectorAll('[data-delete-modal]').forEach(function (trigger) {
    var modal = document.getElementById(trigger.getAttribute('data-delete-modal'));
    var handlers = modal ? modalHandlers.get(modal) : null;

    if (!handlers) {
      return;
    }

    trigger.addEventListener('click', function (event) {
      event.preventDefault();
      handlers.open(trigger);
    });
  });
})();
