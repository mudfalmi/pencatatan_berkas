
<?php if(file_exists(public_path('build/manifest.json'))): ?>
  <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
<?php else: ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="<?php echo e(asset('assets/js/theme.js')); ?>" defer></script>
  <script src="<?php echo e(asset('assets/js/admin.js')); ?>" defer></script>
<?php endif; ?>
<?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/partials/scripts.blade.php ENDPATH**/ ?>