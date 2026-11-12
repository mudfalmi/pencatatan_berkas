
<div class="form-field" style="grid-column: 1 / -1;">
  <label class="form-label fw-semibold text-slate-700 text-sm mb-2">Tanda Tangan Digital
    <span class="fw-normal text-slate-400">(Opsional)</span></label>

  
  <div class="w-full rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden dark:bg-white dark:border-slate-200">
    <canvas id="tandaTanganCanvas" class="d-block w-full h-40"
      style="touch-action: none; cursor: crosshair;"
      aria-label="Area tanda tangan digital"></canvas>
  </div>

  
  <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 mt-3">
    <small class="text-slate-500 dark:text-slate-400">
      Silakan tanda tangan langsung pada area di atas menggunakan mouse atau layar sentuh
    </small>
    <button type="button" id="clearSignature"
      class="btn bg-slate-50 border border-slate-200 text-slate-600 hover:bg-slate-100 rounded-xl px-4 py-2.5 text-sm font-medium w-full sm:w-auto justify-content-center d-inline-flex align-items-center gap-2 shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:shadow-none"
      title="Hapus ulang tanda tangan">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M4 7h16" /><path d="M10 11v6" /><path d="M14 11v6" />
        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l-1 -12" /><path d="M9 7v-3h6v3" />
      </svg>
      Bersihkan
    </button>
  </div>

  
  <input type="hidden" name="tanda_tangan" id="tandaTanganInput" value="" />

  <?php $__errorArgs = ['tanda_tangan'];
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

<script>
  (function () {
    var canvas = document.getElementById('tandaTanganCanvas');
    var input = document.getElementById('tandaTanganInput');
    var clearBtn = document.getElementById('clearSignature');
    if (!canvas || !input || !clearBtn) return;

    var ctx = canvas.getContext('2d');
    var sedangMenulis = false, sudahMenggambar = false, x0 = 0, y0 = 0;

    // Atur resolusi canvas mengikuti ukuran tampilan (retina-friendly).
    function aturKanvas(pertahankan) {
      var dpr = window.devicePixelRatio || 1;
      var w = canvas.clientWidth || 600;
      var h = canvas.clientHeight || 160;
      var snapshot = pertahankan && sudahMenggambar ? canvas.toDataURL('image/png') : null;

      canvas.width = Math.max(1, Math.round(w * dpr));
      canvas.height = Math.max(1, Math.round(h * dpr));
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

      ctx.fillStyle = '#ffffff';
      ctx.fillRect(0, 0, w, h);
      ctx.strokeStyle = '#334155';
      ctx.lineWidth = 2.25;
      ctx.lineCap = 'round';
      ctx.lineJoin = 'round';

      if (snapshot) {
        var img = new Image();
        img.onload = function () { ctx.drawImage(img, 0, 0, w, h); };
        img.src = snapshot;
      }
    }
    aturKanvas(false);

    // Pertahankan goresan saat layar di-resize.
    var resizeTimer;
    window.addEventListener('resize', function () {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function () { aturKanvas(true); }, 200);
    });

    function posisiKursor(e) {
      var rect = canvas.getBoundingClientRect();
      var titik = (e.touches && e.touches[0]) || (e.changedTouches && e.changedTouches[0]) || e;
      return { x: titik.clientX - rect.left, y: titik.clientY - rect.top };
    }

    function mulaiMenggambar(e) {
      // Hanya gambar dengan tombol kiri mouse / sentuhan jari.
      var tombol = typeof e.button === 'number' ? e.button : 0;
      if (e.pointerType !== 'touch' && tombol !== 0) return;
      if (e.cancelable) e.preventDefault();

      sedangMenulis = true;
      sudahMenggambar = true;
      if (canvas.setPointerCapture && e.pointerId !== undefined) {
        canvas.setPointerCapture(e.pointerId);
      }

      var p = posisiKursor(e);
      x0 = p.x; y0 = p.y;
      ctx.beginPath(); ctx.moveTo(x0, y0); ctx.lineTo(x0 + 0.01, y0 + 0.01); ctx.stroke();
    }

    function gerakkanKursor(e) {
      if (!sedangMenulis) return;
      if (e.cancelable) e.preventDefault();

      var p = posisiKursor(e);
      ctx.beginPath(); ctx.moveTo(x0, y0); ctx.lineTo(p.x, p.y); ctx.stroke();
      x0 = p.x; y0 = p.y;
    }

    function hentikanMenggambar(e) {
      if (e && e.cancelable) e.preventDefault();
      sedangMenulis = false;
    }

    if (window.PointerEvent) {
      // Perangkat modern: pointer events menangani mouse, stylus, dan sentuhan.
      canvas.addEventListener('pointerdown', mulaiMenggambar);
      canvas.addEventListener('pointermove', gerakkanKursor);
      ['pointerup', 'pointercancel', 'pointerleave'].forEach(function (nama) {
        canvas.addEventListener(nama, hentikanMenggambar);
      });

      // Cegah halaman ikut ter-scroll saat jari mencoret tanda tangan.
      canvas.addEventListener('touchstart', function (e) {
        if (e.cancelable) e.preventDefault();
      }, { passive: false });
      canvas.addEventListener('touchmove', function (e) {
        if (sedangMenulis && e.cancelable) e.preventDefault();
      }, { passive: false });
    } else {
      // Fallback browser lama: mouse + touch events.
      canvas.addEventListener('mousedown', mulaiMenggambar);
      canvas.addEventListener('mousemove', gerakkanKursor);
      ['mouseup', 'mouseleave'].forEach(function (nama) {
        canvas.addEventListener(nama, hentikanMenggambar);
      });

      canvas.addEventListener('touchstart', function (e) {
        mulaiMenggambar(e);
        if (e.cancelable) e.preventDefault();
      }, { passive: false });
      canvas.addEventListener('touchmove', function (e) {
        gerakkanKursor(e);
        if (e.cancelable) e.preventDefault();
      }, { passive: false });
      ['touchend', 'touchcancel'].forEach(function (nama) {
        canvas.addEventListener(nama, hentikanMenggambar, { passive: false });
      });
    }

    // Tombol Bersihkan: area kosong kembali, input akan dikosongkan saat submit.
    clearBtn.addEventListener('click', function () {
      sudahMenggambar = false;
      aturKanvas(false);
    });

    // Saat form disubmit: isi hidden input dengan data URI PNG.
    var form = canvas.closest('form');
    if (form) {
      form.addEventListener('submit', function () {
        input.value = sudahMenggambar ? canvas.toDataURL('image/png') : '';
      });
    }
  })();
</script>
<?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/partials/canvas-tanda-tangan.blade.php ENDPATH**/ ?>