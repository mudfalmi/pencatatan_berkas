<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
  <?php echo $__env->make('partials.head-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <title>Login - Kemenag Karawang</title>
  <?php echo $__env->make('partials.head-links', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  
  <?php echo $__env->make('partials.theme-init', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  <style>
    body {
      min-height: 100vh;
      display: grid;
      place-items: center;
      padding: 1.5rem 1rem;
      background-image: linear-gradient(120deg, #e0e7ff 0%, #ece2ff 35%, #fbe2ef 70%, #ffefdd 100%);
      background-attachment: fixed;
    }

    .login-card {
      width: 100%;
      max-width: 26rem;
      padding: 2rem;
      background-color: #ffffff;
      border: 1px solid #eef1f6;
      border-radius: 1rem;
      box-shadow: 0 1.25rem 3rem rgba(15, 23, 42, 0.12);
      animation: login-enter 0.35s ease both;
    }

    @keyframes login-enter {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: none;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .login-card {
        animation: none;
      }
    }
  </style>
</head>

<body class="login-page">
  <div class="login-card">
    
    <div class="text-center mb-6">
      <img src="<?php echo e(asset('images/logo-kemenag.png')); ?>" alt="Logo Kemenag" class="w-16 h-16 mx-auto mb-3 object-contain" />
      <h4 class="mb-1 fw-bold">Selamat Datang</h4>
      <p class="text-secondary small mb-0">
        Sistem Informasi Manajemen Surat &amp; Arsip<br />PD Pontren
        <br /><span class="fw-semibold">Kemenag Kabupaten Karawang</span>
      </p>
    </div>

    <?php if($errors->any()): ?>
      <div class="alert alert-danger py-2 small mb-6" role="alert">
        <?php echo e($errors->first()); ?>

      </div>
    <?php endif; ?>

    
    <form method="POST" action="<?php echo e(route('login')); ?>" id="loginForm" class="space-y-4">
      <?php echo csrf_field(); ?>

      
      <div class="form-field">
        <label for="username" class="form-label fw-semibold text-slate-700 text-sm mb-2 dark:text-slate-200">Username</label>
        <div class="form-field__control">
          <span class="form-field__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <circle cx="12" cy="12" r="4" /><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8" />
            </svg>
          </span>
          <input type="text" name="username" id="username"
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            value="<?php echo e(old('username')); ?>" placeholder="Masukkan username" autocomplete="username" autofocus required />
        </div>
        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback d-block mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <div class="form-field">
        <label for="password" class="form-label fw-semibold text-slate-700 text-sm mb-2 dark:text-slate-200">Password</label>
        <div class="form-field__control">
          <span class="form-field__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />
            </svg>
          </span>
          <input type="password" name="password" id="password"
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 pr-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="Masukkan password" autocomplete="current-password" required />
          <button type="button" class="form-field__action" data-password-toggle="password"
            aria-label="Tampilkan password" aria-pressed="false">
            <svg data-icon-eye xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M2 12s3.5 -7 10 -7 10 7 10 7 -3.5 7 -10 7 -10 -7 -10 -7Z" />
              <circle cx="12" cy="12" r="3" />
            </svg>
            <svg data-icon-eye-off class="d-none" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
              stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M3 3l18 18" /><path d="M10.6 10.6a3 3 0 0 0 4.2 4.2" />
              <path d="M9.9 5.2A9.9 9.9 0 0 1 12 5c6.5 0 10 7 10 7a17.7 17.7 0 0 1 -2.7 3.6" />
              <path d="M6.6 6.6A17.6 17.6 0 0 0 2 12s3.5 7 10 7a9.8 9.8 0 0 0 4.2 -.9" />
            </svg>
          </button>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="invalid-feedback d-block mt-2"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
      <button type="submit" data-submit-button
        class="btn btn-primary w-100 rounded-xl font-medium py-2.5 d-inline-flex align-items-center justify-content-center gap-2 hover:bg-blue-700 transition shadow-md shadow-blue-500/20">
        <span data-submit-label>Masuk</span>
      </button>
    </form>

    <p class="text-center text-secondary small mt-6 mb-0">
      &copy; <?php echo e(date('Y')); ?> PD Pontren Kemenag Kabupaten Karawang
    </p>
  </div>

  <script>
    (function () {
      'use strict';

      /* Tampilkan / sembunyikan password ---------------------------------
       * Mengubah type input antara "password" dan "text" serta mengganti
       * ikon mata (eye <-> eye-off) sesuai status terbaru. */
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

          input.focus();
        });
      });

      /* Status tombol saat form dikirim --------------------------------
       * Event "submit" baru terjadi setelah validasi browser lolos,
       * sehingga form tetap terkirim normal ke server. Tidak ada
       * preventDefault() di sini (tidak memakai AJAX/fetch). */
      var loginForm = document.getElementById('loginForm');
      var submitButton = document.querySelector('[data-submit-button]');
      var submitLabel = submitButton ? submitButton.querySelector('[data-submit-label]') : null;

      function resetSubmitButton() {
        if (!submitButton) {
          return;
        }
        submitButton.disabled = false;
        submitButton.classList.remove('disabled');
        if (submitLabel) {
          submitLabel.textContent = 'Masuk';
        }
      }

      if (loginForm && submitButton) {
        loginForm.addEventListener('submit', function () {
          /* Timeout 0ms: dijalankan setelah browser memproses aksi default
             dari event submit (form dikirim ke server). Dengan begitu
             tombol dinonaktifkan tanpa mengganggu navigasi, dan tidak ada
             preventDefault() -> form tetap submit normal (non-AJAX). */
          window.setTimeout(function () {
            submitButton.disabled = true;
            submitButton.classList.add('disabled');
            if (submitLabel) {
              submitLabel.textContent = 'Memproses...';
            }
          }, 0);
        });
      }

      /* Halaman dimuat ulang dari cache browser (bfcache) -> kembalikan
         tombol ke kondisi normal supaya tidak terjebak di "Memproses...". */
      window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
          resetSubmitButton();
        }
      });
    })();
  </script>
</body>

</html>
<?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/auth/login.blade.php ENDPATH**/ ?>