<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
  
  <div class="welcome-banner welcome-banner-sm mb-4">
    <span class="banner-blob banner-blob-1"></span>
    <span class="banner-blob banner-blob-2"></span>

    <div class="position-relative">
      <span class="banner-eyebrow mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 3l2 5l5 2l-5 2l-2 5l-2 -5l-5 -2l5 -2z" />
        </svg>
        Panel Admin
      </span>
      <h1 class="fs-4 fw-bold mb-1">👋 Selamat Datang, Admin!</h1>
      <p class="text-secondary mb-3">Kelola Surat &amp; Arsip — PD Pontren Kemenag Kabupaten Karawang.</p>

      
      <a href="<?php echo e(route('admin.surat.create')); ?>" class="inline-flex items-center justify-center btn btn-primary btn-lift">
       + Input Surat
      </a>
    </div>

    
    <div class="banner-art-slot d-none d-md-block" style="right: 2.5rem;">
      <div class="banner-art" style="width: 7rem; height: 7rem;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 4rem; height: 4rem;">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />
          <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
        </svg>
      </div>
    </div>
  </div>

  
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
    
    <div class="bg-blue-50 border border-blue-100/80 rounded-2xl p-4 sm:p-5 shadow-sm d-flex flex-column gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-blue-500/15 dark:border-blue-500/30">
      <div class="d-flex align-items-center gap-3">
        <div class="text-blue-600 dark:text-blue-300">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M4 4h16v16h-16z" /><path d="M4 10h16" /><path d="M10 4v6" />
          </svg>
        </div>
        <div class="stat-label">Total Surat &amp; Arsip</div>
      </div>
      <div class="d-flex justify-content-between align-items-end lh-1 mt-auto">
        <div class="stat-value text-2xl font-bold text-blue-600 dark:text-blue-300"><?php echo e($stats['total_surat']); ?></div>
        <a href="<?php echo e(route('admin.surat.index')); ?>" class="text-secondary small fw-medium text-decoration-none">Kelola →</a>
      </div>
    </div>

    
    <div class="bg-amber-50 border border-amber-100/80 rounded-2xl p-4 sm:p-5 shadow-sm d-flex flex-column gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-amber-500/15 dark:border-amber-500/30">
      <div class="d-flex align-items-center gap-3">
        <div class="text-amber-600 dark:text-amber-300">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12 9v4" /><path d="M12 17h.01" />
            <path d="M10.3 3.9L1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" />
          </svg>
        </div>
        <div class="stat-label">Belum Berarsip</div>
      </div>
      <div class="d-flex justify-content-between align-items-end lh-1 mt-auto">
        <div class="stat-value text-2xl font-bold text-amber-600 dark:text-amber-300"><?php echo e($stats['total_surat'] - $stats['total_arsip']); ?></div>
        <a href="<?php echo e(route('admin.surat.create')); ?>" class="text-secondary small fw-medium text-decoration-none">Unggah →</a>
      </div>
    </div>

    
    <div class="bg-indigo-50 border border-indigo-100/80 rounded-2xl p-4 sm:p-5 shadow-sm d-flex flex-column gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-indigo-500/15 dark:border-indigo-500/30">
      <div class="d-flex align-items-center gap-3">
        <div class="text-indigo-600 dark:text-indigo-300">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
            <path d="M16 11h4" /><path d="M18 9v4" />
          </svg>
        </div>
        <div>
          <div class="stat-label">Total Pengguna</div>
          <div class="text-secondary small"><?php echo e($stats['total_admin']); ?> Admin · <?php echo e($stats['total_petugas']); ?> Petugas</div>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-end lh-1 mt-auto">
        <div class="stat-value text-2xl font-bold text-indigo-600 dark:text-indigo-300"><?php echo e($stats['total_pengguna']); ?></div>
        <a href="<?php echo e(route('admin.users.index')); ?>" class="text-secondary small fw-medium text-decoration-none">Lihat →</a>
      </div>
    </div>
  </div>

  
  <div class="card card-lg mb-6">
    <div class="card-header border-bottom-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div>
        <h5 class="mb-0 fw-bold">Ringkasan Akun Pengguna</h5>
        <small class="text-secondary">5 akun pengguna terbaru</small>
      </div>
      <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-white btn-sm">Kelola Pengguna</a>
    </div>
    <div class="table-responsive overflow-x-auto">
      <table class="table table-roomy text-nowrap mb-0 table-centered table-hover table-soft">
        <thead>
          <tr>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Hak Akses</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $penggunaTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pengguna): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <span class="avatar"><?php echo e(strtoupper(substr($pengguna->nama_lengkap, 0, 1))); ?></span>
                  <span class="fw-semibold"><?php echo e($pengguna->nama_lengkap); ?></span>
                </div>
              </td>
              <td class="text-secondary"><?php echo e($pengguna->username); ?></td>
              <td>
                <?php if($pengguna->isAdmin()): ?>
                  <span class="badge text-warning-emphasis bg-warning-subtle">Admin</span>
                <?php else: ?>
                  <span class="badge text-info-emphasis bg-info-subtle">Petugas</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if($pengguna->isActive()): ?>
                  <span class="bg-emerald-100/80 text-emerald-700 font-semibold px-3 py-1 rounded-full text-xs inline-flex items-center gap-1.5 dark:bg-emerald-500/15 dark:text-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                  </span>
                <?php else: ?>
                  <span class="badge text-danger-emphasis bg-danger-subtle">Nonaktif</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="4" class="text-center text-secondary py-5">Belum ada akun pengguna terdaftar.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  
  <div class="card card-lg">
    <div class="card-header border-bottom-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div>
        <h5 class="mb-0 fw-bold">Arsip Surat Terbaru</h5>
        <small class="text-secondary">5 surat terakhir berdasarkan tanggal surat</small>
      </div>
      <a href="<?php echo e(route('admin.surat.index')); ?>" class="btn btn-white btn-sm">Kelola Semua</a>
    </div>
    <div class="table-responsive overflow-x-auto">
      <table class="table table-roomy text-nowrap mb-0 table-centered table-hover table-soft">
        <thead>
          <tr>
            <th>Nomor Surat</th>
            <th>Tanggal</th>
            <th>Perihal</th>
            <th>Penerima / Lembaga</th>
            <th>Jenis</th>
            <th>Arsip</th>
          </tr>
        </thead>
        <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $suratTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td>
                <a href="<?php echo e(route('admin.surat.edit', $surat)); ?>" class="fw-semibold text-decoration-none">
                  <?php echo e($surat->nomor_surat); ?>

                </a>
              </td>
              <td class="text-secondary"><?php echo e($surat->tanggal_surat->format('d M Y')); ?></td>
              <td style="max-width: 22rem; white-space: normal;"><?php echo e($surat->perihal); ?></td>
              <td><?php echo e($surat->nama_penerima_lembaga); ?></td>
              <td><span class="badge text-info-emphasis bg-info-subtle"><?php echo e($surat->jenis_surat); ?></span></td>
              <td>
                <?php if($surat->hasDokumen()): ?>
                  <a href="<?php echo e(route('admin.surat.file', $surat)); ?>" target="_blank" rel="noopener"
                    class="badge badge-soft badge-soft-success text-decoration-none">
                    <span class="badge-dot"></span><?php echo e(strtoupper($surat->ekstensiDokumen() ?: 'file')); ?>

                  </a>
                <?php else: ?>
                  <span class="badge text-secondary bg-secondary-subtle">Belum ada arsip</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="6" class="text-center text-secondary py-5">Belum ada data surat tercatat.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>