<?php $__env->startSection('page-title', 'Manajemen Surat & Arsip'); ?>

<?php $__env->startSection('content'); ?>
  
  <div class="row row-cols-1 row-cols-sm-2 mb-6 g-6">
    <div class="col">
      <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 shadow-sm d-flex flex-column gap-3 h-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-blue-500/15 dark:border-blue-500/30">
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
          <div class="stat-value text-2xl font-bold text-blue-600 dark:text-blue-300"><?php echo e($statistik['total_surat']); ?></div>
          <span class="text-secondary small">terdaftar di sistem</span>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-4 shadow-sm d-flex flex-column gap-3 h-100 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-emerald-500/15 dark:border-emerald-500/30">
        <div class="d-flex align-items-center gap-3">
          <div class="text-emerald-600 dark:text-emerald-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><path d="M14 2v6h6" />
              <path d="M12 18v-6" /><path d="M9 15l3 3 3-3" />
            </svg>
          </div>
          <div class="stat-label">Arsip Digital Terunggah</div>
        </div>
        <div class="d-flex justify-content-between align-items-end lh-1 mt-auto">
          <div class="stat-value text-2xl font-bold text-emerald-600 dark:text-emerald-300"><?php echo e($statistik['total_arsip']); ?></div>
          <span class="text-secondary small">dokumen tersimpan</span>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-6">
    <div class="col-12">
      <div class="card card-lg">
        <div class="card-header border-bottom-0 p-6 d-flex flex-wrap align-items-center justify-content-between gap-3">
          <div>
            <h5 class="mb-0 fw-bold">Daftar Arsip Surat</h5>
            <small class="text-secondary">Total <?php echo e($surats->total()); ?> surat tercatat</small>
          </div>
          <a href="<?php echo e(route('petugas.surat.create')); ?>"
            class="btn btn-primary btn-lift d-inline-flex align-items-center justify-content-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 5l0 14" /><path d="M5 12l14 0" />
            </svg>
            <span>Tambah Surat</span>
          </a>
        </div>

        
        <div class="px-6 pb-6">
          <form method="GET" action="<?php echo e(route('petugas.surat.index')); ?>"
            class="bg-slate-50/80 border rounded-xl p-4 dark:bg-slate-50/80">
            <div class="row g-3 align-items-end">
              <div class="col-lg-6 col-md-5">
                <label for="q" class="form-label text-xs fw-semibold text-slate-500 tracking-wider text-uppercase dark:text-slate-400">Cari</label>
                <div class="input-group">
                  <span class="input-group-text bg-transparent border-end-0 pe-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                      class="text-slate-400 dark:text-slate-300">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" /><circle cx="11" cy="11" r="7" /><path d="M20 20l-3.6 -3.6" />
                    </svg>
                  </span>
                  <input type="search" name="q" id="q" class="form-control border-start-0 ps-2"
                    placeholder="Nomor surat, perihal, atau penerima..." value="<?php echo e(request('q')); ?>" />
                </div>
              </div>
              <div class="col-lg-4 col-md-4">
                <label for="jenis_surat" class="form-label text-xs fw-semibold text-slate-500 tracking-wider text-uppercase dark:text-slate-400">Jenis
                  Surat</label>
                <select name="jenis_surat" id="jenis_surat" class="form-select">
                  <option value="">Semua jenis surat</option>
                  <?php $__currentLoopData = $jenisSurat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nilai => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($nilai); ?>" <?php echo e(request('jenis_surat') === $nilai ? 'selected' : ''); ?>>
                      <?php echo e($label); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
              </div>
              <div class="col-lg-2 col-md-3 d-grid">
                <button type="submit"
                  class="btn btn-primary rounded-lg shadow-sm d-inline-flex align-items-center justify-content-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 6h16" /><path d="M7 12h10" /><path d="M10 18h4" />
                  </svg>
                  <span>Filter</span>
                </button>
              </div>
            </div>
          </form>
        </div>

        <div class="table-responsive">
          <table class="table text-nowrap mb-0 table-centered table-hover table-soft">
            <thead>
              <tr>
                <th style="width: 3rem;">No.</th>
                <th>Nomor Surat</th>
                <th>Tanggal</th>
                <th>Perihal</th>
                <th>Penerima / Lembaga</th>
                <th>Jenis</th>
                <th>File Dokumen</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $surats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $surat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                  <td class="text-secondary py-2.5"><?php echo e($surats->firstItem() + $index); ?></td>
                  <td class="py-2.5">
                    <span class="fw-semibold"><?php echo e($surat->nomor_surat); ?></span>
                  </td>
                  <td class="text-secondary py-2.5"><?php echo e($surat->tanggal_surat->format('d M Y')); ?></td>
                  <td class="py-2.5" style="max-width: 22rem; white-space: normal;"><?php echo e($surat->perihal); ?></td>
                  <td class="py-2.5"><?php echo e($surat->nama_penerima_lembaga); ?></td>
                  <td class="py-2.5">
                    <span class="badge text-info-emphasis bg-info-subtle"><?php echo e($surat->jenis_surat); ?></span>
                  </td>
                  <td class="py-2.5">
                    <?php if($surat->hasDokumen()): ?>
                      <div class="d-inline-flex align-items-center justify-content-center gap-1.5">
                        <span class="badge badge-soft badge-soft-success">
                          <span class="badge-dot"></span><?php echo e(strtoupper($surat->ekstensiDokumen() ?: 'file')); ?>

                        </span>
                        <a href="<?php echo e(route('petugas.surat.file', $surat)); ?>" target="_blank" rel="noopener"
                          class="btn p-0 w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-slate-200 hover:bg-slate-50 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center"
                          title="Lihat Arsip" aria-label="Lihat arsip surat <?php echo e($surat->nomor_surat); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                            class="w-3.5 h-3.5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M2 12s3.5 -7 10 -7 10 7 10 7 -3.5 7 -10 7 -10 -7 -10 -7Z" />
                            <circle cx="12" cy="12" r="3" />
                          </svg>
                        </a>
                        <a href="<?php echo e(route('petugas.surat.download', $surat)); ?>"
                          class="btn p-0 w-8 h-8 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-50 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center"
                          title="Unduh Arsip" aria-label="Unduh arsip surat <?php echo e($surat->nomor_surat); ?>">
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                            class="w-3.5 h-3.5">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M21 15v4a2 2 0 0 1 -2 2H5a2 2 0 0 1 -2 -2v-4" /><path d="M7 10l5 5 5 -5" /><path d="M12 15V3" />
                          </svg>
                        </a>
                      </div>
                    <?php else: ?>
                      <span class="badge text-secondary bg-secondary-subtle">Belum ada arsip</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center py-2.5">
                    <div class="d-inline-flex justify-content-center align-items-center gap-1.5">
                      <a href="<?php echo e(route('petugas.surat.edit', $surat)); ?>"
                        class="btn p-0 w-8 h-8 rounded-lg bg-amber-50 text-amber-600 border border-amber-200/60 hover:bg-amber-100 hover:text-amber-600 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center dark:bg-amber-50 dark:text-amber-600 dark:border-amber-200/60 dark:hover:bg-amber-100"
                        title="Edit Surat" aria-label="Edit surat <?php echo e($surat->nomor_surat); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                          class="w-3.5 h-3.5">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1 -4L16.5 3.5z" />
                        </svg>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                  <td colspan="8" class="text-center text-secondary py-5">
                    Belum ada data surat yang cocok dengan pencarian Anda.
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="card-body border-top">
          <?php echo e($surats->links()); ?>

        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/petugas/surat/index.blade.php ENDPATH**/ ?>