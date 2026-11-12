
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />


<?php if(file_exists(public_path('build/manifest.json'))): ?>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/css/theme.css', 'resources/css/app.css']); ?>
<?php else: ?>
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="<?php echo e(asset('assets/css/theme.css')); ?>" rel="stylesheet" />
<?php endif; ?>
<?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/partials/head-links.blade.php ENDPATH**/ ?>