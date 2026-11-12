<?php $__env->startSection('page-title', 'Tambah Surat'); ?>

<?php $__env->startSection('content'); ?>
  
  <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mb-6">
    <a href="<?php echo e(route('petugas.surat.index')); ?>"
      class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-4 py-2 text-sm font-medium shadow-sm d-inline-flex align-items-center gap-2 shrink-0 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M19 12H5" /><path d="M12 19l-7 -7 7 -7" />
      </svg>
      Kembali
    </a>
    <div class="min-w-0">
      <h4 class="mb-1 fw-bold">Tambah Surat</h4>
      <p class="mb-0 text-slate-500 text-sm dark:text-slate-400">Isi formulir di bawah untuk mencatat surat dan mengunggah berkas arsip digital.</p>
    </div>
  </div>

  
  <div class="mx-auto mt-6 w-full" style="max-width: 56rem;">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-4 sm:p-6 md:p-8 dark:bg-slate-800 dark:border-slate-700">

      
      <div class="d-flex align-items-start align-items-sm-center gap-3 mb-6">
        <div class="bg-blue-50 text-blue-600 p-3 rounded-xl d-inline-flex align-items-center justify-content-center dark:bg-blue-500/15 dark:text-blue-300">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 4h16v16h-16z" /><path d="M4 10h16" /><path d="M10 4v6" />
          </svg>
        </div>
        <div>
          <h5 class="mb-0 fw-bold">Data Surat</h5>
          <p class="mb-0 text-slate-500 text-sm dark:text-slate-400">Isi formulir berikut dan unggah arsip digitalnya</p>
        </div>
      </div>

      <?php if($errors->any()): ?>
        <div class="alert alert-danger py-2 small mb-6" role="alert">
          <ul class="mb-0 ps-3">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        </div>
      <?php endif; ?>

      
      <?php echo $__env->make('partials.surat-form', [
          'action' => route('petugas.surat.store'),
          'cancelUrl' => route('petugas.surat.index'),
      ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/petugas/surat/create.blade.php ENDPATH**/ ?>