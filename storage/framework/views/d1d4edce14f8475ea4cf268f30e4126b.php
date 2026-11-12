

<?php $__env->startSection('page-title', 'Ubah Data Surat'); ?>

<?php $__env->startSection('content'); ?>
  
  <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 mb-6">
    <a href="<?php echo e(route('admin.surat.index')); ?>"
      class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-4 py-2 text-sm font-medium shadow-sm d-inline-flex align-items-center gap-2 shrink-0 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M19 12H5" /><path d="M12 19l-7 -7 7 -7" />
      </svg>
      Kembali
    </a>
    <div class="min-w-0">
      <h4 class="mb-1 fw-bold">Ubah Data Surat</h4>
      <p class="mb-0 text-slate-500 text-sm dark:text-slate-400">Perbarui data surat dan arsip digitalnya.</p>
    </div>
  </div>

  <div class="row g-6">
    
    <div class="col-12">
      <div class="mx-auto bg-white border border-slate-100 rounded-2xl shadow-sm p-6 md:p-8 dark:bg-slate-800 dark:border-slate-700"
        style="max-width: 56rem;">

        
        <div class="d-flex align-items-center gap-3 mb-6">
          <div class="bg-amber-50 text-amber-600 p-3 rounded-xl d-inline-flex align-items-center justify-content-center dark:bg-amber-50 dark:text-amber-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1 -4L16.5 3.5z" />
            </svg>
          </div>
          <div>
            <h5 class="mb-0 fw-bold">Ubah Data Surat &amp; Arsip</h5>
            <p class="mb-0 text-slate-500 text-sm dark:text-slate-400">Perbarui data atau ganti arsip digital surat</p>
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

        <form method="POST" action="<?php echo e(route('admin.surat.update', $surat)); ?>" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <?php echo method_field('PUT'); ?>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            
            <div class="form-field">
              <label for="nomor_surat" class="form-label fw-semibold text-slate-700 text-sm mb-2">Nomor Surat
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 4h16v16h-16z" /><path d="M4 10h16" /><path d="M10 4v6" />
                  </svg>
                </span>
                <input type="text" name="nomor_surat" id="nomor_surat"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['nomor_surat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e(old('nomor_surat', $surat->nomor_surat)); ?>" required />
              </div>
              <?php $__errorArgs = ['nomor_surat'];
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
              <label for="tanggal_surat" class="form-label fw-semibold text-slate-700 text-sm mb-2">Tanggal Surat
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <rect x="3" y="4" width="18" height="18" rx="2" /><path d="M16 2v4" /><path d="M8 2v4" />
                    <path d="M3 10h18" />
                  </svg>
                </span>
                <input type="date" name="tanggal_surat" id="tanggal_surat"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['tanggal_surat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e(old('tanggal_surat', optional($surat->tanggal_surat)->format('Y-m-d'))); ?>" required />
              </div>
              <?php $__errorArgs = ['tanggal_surat'];
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
              <label for="tanggal_penerimaan" class="form-label fw-semibold text-slate-700 text-sm mb-2">Tanggal
                Penerimaan</label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M9 11l3 3l8 -8" />
                    <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
                  </svg>
                </span>
                <input type="date" name="tanggal_penerimaan" id="tanggal_penerimaan"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['tanggal_penerimaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e(old('tanggal_penerimaan', optional($surat->tanggal_penerimaan)->format('Y-m-d'))); ?>" />
              </div>
              <small class="text-slate-500 dark:text-slate-400">Tanggal fisik surat diterima (tanda serah terima).</small>
              <?php $__errorArgs = ['tanggal_penerimaan'];
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
              <label for="jenis_surat" class="form-label fw-semibold text-slate-700 text-sm mb-2">Jenis Surat
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6h16" /><path d="M4 12h16" /><path d="M4 18h16" /><path d="M4 6v12" />
                  </svg>
                </span>
                <select name="jenis_surat" id="jenis_surat"
                  class="form-select w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 pr-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['jenis_surat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  required>
                  <?php $__currentLoopData = $jenisSurat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nilai => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($nilai); ?>" <?php echo e(old('jenis_surat', $surat->jenis_surat) === $nilai ? 'selected' : ''); ?>>
                      <?php echo e($label); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <span class="form-field__chevron">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M6 9l6 6 6 -6" />
                  </svg>
                </span>
              </div>
              <?php $__errorArgs = ['jenis_surat'];
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

            
            <div class="form-field" style="grid-column: 1 / -1;">
              <label for="perihal" class="form-label fw-semibold text-slate-700 text-sm mb-2">Perihal
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6h16" /><path d="M4 12h16" /><path d="M4 18h10" />
                  </svg>
                </span>
                <textarea name="perihal" id="perihal" rows="3"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['perihal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  required><?php echo e(old('perihal', $surat->perihal)); ?></textarea>
              </div>
              <?php $__errorArgs = ['perihal'];
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
              <label for="nama_penerima_lembaga" class="form-label fw-semibold text-slate-700 text-sm mb-2">Penerima /
                Lembaga <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 21h18" /><path d="M5 21V7l7 -4l7 4v14" /><path d="M9 21v-6h6v6" />
                  </svg>
                </span>
                <input type="text" name="nama_penerima_lembaga" id="nama_penerima_lembaga"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['nama_penerima_lembaga'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e(old('nama_penerima_lembaga', $surat->nama_penerima_lembaga)); ?>" required />
              </div>
              <?php $__errorArgs = ['nama_penerima_lembaga'];
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
              <label for="bukti_penerimaan" class="form-label fw-semibold text-slate-700 text-sm mb-2">Catatan Penerimaan /
                TTD Ekspedisi</label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1 -4L16.5 3.5z" />
                  </svg>
                </span>
                <input type="text" name="bukti_penerimaan" id="bukti_penerimaan"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['bukti_penerimaan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  value="<?php echo e(old('bukti_penerimaan', $surat->bukti_penerimaan)); ?>"
                  placeholder="Contoh: Diterima oleh Budi (ttd) — Register 05/2026" />
              </div>
              <small class="text-slate-500 dark:text-slate-400">Catatan tanda serah terima surat (nama pihak penerima,
                nomor register, dsb).</small>
              <?php $__errorArgs = ['bukti_penerimaan'];
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

            
            <?php if($surat->hasDokumen()): ?>
              <div class="form-field" style="grid-column: 1 / -1;">
                <label class="form-label fw-semibold text-slate-700 text-sm mb-2">Arsip Digital Saat Ini</label>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-4 rounded-xl border border-slate-200 bg-slate-50/80 dark:bg-slate-900 dark:border-slate-700">
                  <div class="d-flex align-items-center gap-3">
                    <div class="bg-blue-50 text-blue-600 p-2.5 rounded-xl d-inline-flex align-items-center justify-content-center dark:bg-blue-500/15 dark:text-blue-300">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><path d="M14 2v6h6" />
                      </svg>
                    </div>
                    <div>
                      <div class="fw-semibold text-sm"><?php echo e($surat->nama_dokumen); ?></div>
                      <div class="text-slate-500 text-xs dark:text-slate-400">Format: <?php echo e(strtoupper($surat->ekstensiDokumen())); ?></div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-2">
                    <a href="<?php echo e(route('admin.surat.file', $surat)); ?>" target="_blank" rel="noopener"
                      class="btn btn-white btn-sm rounded-xl">Lihat</a>
                    <a href="<?php echo e(route('admin.surat.download', $surat)); ?>" class="btn btn-white btn-sm rounded-xl">Unduh</a>
                  </div>
                </div>
                <div class="form-check mt-3">
                  <input class="form-check-input" type="checkbox" name="hapus_dokumen" id="hapus_dokumen" value="1">
                  <label class="form-check-label small text-slate-600 dark:text-slate-400" for="hapus_dokumen">
                    Hapus arsip digital ini (gunakan jika ingin menggantinya dengan berkas baru atau menghilangkan arsip)
                  </label>
                </div>
              </div>
            <?php endif; ?>

            
            <div class="form-field" style="grid-column: 1 / -1;">
              <label for="file_dokumen" class="form-label fw-semibold text-slate-700 text-sm mb-2">
                <?php echo e($surat->hasDokumen() ? 'Ganti Arsip Digital' : 'Unggah Arsip Digital (Scan Surat)'); ?>

              </label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M21 15v4a2 2 0 0 1 -2 2H5a2 2 0 0 1 -2 -2v-4" /><path d="M7 10l5 5 5 -5" /><path d="M12 15V3" />
                  </svg>
                </span>
                <input type="file" name="file_dokumen" id="file_dokumen"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 <?php $__errorArgs = ['file_dokumen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                  data-file-input accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx" />
              </div>
              <small class="text-slate-500 dark:text-slate-400">
                Biarkan kosong jika tidak ingin mengubah arsip digital. Format PDF/gambar/dokumen (Word, Excel), maksimal 10 MB.
              </small>
              <?php $__errorArgs = ['file_dokumen'];
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

          </div>

          
          <div class="d-flex align-items-center justify-content-end gap-3 mt-6 pt-6 border-top border-slate-100 dark:border-slate-700">
            <a href="<?php echo e(route('admin.surat.index')); ?>"
              class="btn btn-white rounded-xl px-4 py-2.5 text-sm font-medium d-inline-flex align-items-center gap-2 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
              Batal
            </a>
            <button type="submit"
              class="btn btn-primary rounded-xl px-4 py-2.5 text-sm font-medium shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M20 6L9 17l-5 -5" />
              </svg>
              Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/admin/surat/edit.blade.php ENDPATH**/ ?>