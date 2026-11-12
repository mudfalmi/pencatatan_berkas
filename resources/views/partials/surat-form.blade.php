{{-- ============================================================
     Partial form Tambah Surat — dipakai BERSAMA oleh
     Admin (admin/surat/create) dan Petugas (petugas/surat/create)
     agar struktur, desain, dan fungsionalitas 100% identik.

     Parameter yang wajib dikirim saat @include:
       - action    : URL proses simpan (route admin.surat.store
                     atau petugas.surat.store)
       - cancelUrl : URL tombol Batal (route masing-masing index)

     Catatan: tabel database yang dipakai kedua role adalah "surat"
     (model App\Models\Surat) — sudah sama, tidak ada perubahan skema.
     ============================================================ --}}

{{-- enctype wajib agar file dapat diunggah --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
  @csrf

  {{-- ================= BAGIAN 1: INFORMASI SURAT ================= --}}
  <div class="mb-8">
    <h6 class="mb-1 text-lg font-semibold text-slate-800 dark:text-slate-200">Informasi Surat</h6>
    <p class="mb-4 text-slate-500 text-sm dark:text-slate-400">Data utama surat: identitas, tanggal, dan pihak penerima.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

      {{-- Nomor Surat --}}
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
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('nomor_surat') is-invalid @enderror"
            value="{{ old('nomor_surat') }}" placeholder="Contoh: 001/KEMENAG/KAR/2026" required />
        </div>
        @error('nomor_surat')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

      {{-- Jenis Surat --}}
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
            class="form-select w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 pr-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('jenis_surat') is-invalid @enderror"
            required>
            <option value="">-- Pilih jenis surat --</option>
            @foreach ($jenisSurat as $nilai => $label)
              <option value="{{ $nilai }}" {{ old('jenis_surat') === $nilai ? 'selected' : '' }}>
                {{ $label }}</option>
            @endforeach
          </select>
          <span class="form-field__chevron">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M6 9l6 6 6 -6" />
            </svg>
          </span>
        </div>
        @error('jenis_surat')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

      {{-- Tanggal Surat --}}
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
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('tanggal_surat') is-invalid @enderror"
            value="{{ old('tanggal_surat', date('Y-m-d')) }}" required />
        </div>
        @error('tanggal_surat')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

      {{-- Tanggal Penerimaan --}}
      <div class="form-field">
        <label for="tanggal_penerimaan" class="form-label fw-semibold text-slate-700 text-sm mb-2">Tanggal
          Penerimaan</label>
        <div class="form-field__control">
          <span class="form-field__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M9 11l3 3l8 -8" /><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9" />
            </svg>
          </span>
          <input type="date" name="tanggal_penerimaan" id="tanggal_penerimaan"
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('tanggal_penerimaan') is-invalid @enderror"
            value="{{ old('tanggal_penerimaan', date('Y-m-d')) }}" />
        </div>
        <small class="text-slate-500 dark:text-slate-400">Tanggal fisik surat diterima (tanda serah terima).</small>
        @error('tanggal_penerimaan')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

      {{-- Perihal --}}
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
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('perihal') is-invalid @enderror"
            placeholder="Tuliskan ringkasan isi surat" required>{{ old('perihal') }}</textarea>
        </div>
        @error('perihal')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

      {{-- Nama Pihak / Lembaga Penerima --}}
      <div class="form-field" style="grid-column: 1 / -1;">
        <label for="nama_penerima_lembaga" class="form-label fw-semibold text-slate-700 text-sm mb-2">Nama Pihak /
          Lembaga Penerima <span class="text-danger">*</span></label>
        <div class="form-field__control">
          <span class="form-field__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M3 21h18" /><path d="M5 21V7l7 -4l7 4v14" /><path d="M9 21v-6h6v6" />
            </svg>
          </span>
          <input type="text" name="nama_penerima_lembaga" id="nama_penerima_lembaga"
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('nama_penerima_lembaga') is-invalid @enderror"
            value="{{ old('nama_penerima_lembaga') }}" placeholder="Contoh: Kantor PD Pontren" required />
        </div>
        @error('nama_penerima_lembaga')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

    </div>
  </div>

  {{-- ================= BAGIAN 2: UNGGAH DOKUMEN ================= --}}
  <div class="mb-8 pt-6 border-t border-slate-100 dark:border-slate-700">
    <h6 class="mb-1 text-lg font-semibold text-slate-800 dark:text-slate-200">Unggah Dokumen</h6>
    <p class="mb-4 text-slate-500 text-sm dark:text-slate-400">Unggah dokumen fisik ke bentuk digital (PDF/File Arsip).</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

      {{-- Unggah Dokumen Fisik ke bentuk digital --}}
      <div class="form-field" style="grid-column: 1 / -1;">
        <label for="file_dokumen" class="form-label fw-semibold text-slate-700 text-sm mb-2">Unggah Dokumen Fisik ke
          Bentuk Digital (PDF/File Arsip)</label>
        <div class="form-field__control">
          <span class="form-field__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M21 15v4a2 2 0 0 1 -2 2H5a2 2 0 0 1 -2 -2v-4" /><path d="M7 10l5 5 5 -5" /><path d="M12 15V3" />
            </svg>
          </span>
          <input type="file" name="file_dokumen" id="file_dokumen"
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('file_dokumen') is-invalid @enderror"
            data-file-input accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,.xls,.xlsx" />
        </div>
        <small class="text-slate-500 dark:text-slate-400">
          Format PDF/gambar/dokumen (Word, Excel), maksimal 10 MB. Berkas disimpan pada penyimpanan privat server.
        </small>
        @error('file_dokumen')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

    </div>
  </div>

  {{-- ================= BAGIAN 3: VERIFIKASI & TANDA TERIMA (PENUTUP) ================= --}}
  <div class="pt-6 border-t border-slate-100 dark:border-slate-700">
    <h6 class="mb-1 text-lg font-semibold text-slate-800 dark:text-slate-200">Bukti Validasi &amp; Tanda Tangan
      Penerima</h6>
    <p class="mb-4 text-slate-500 text-sm dark:text-slate-400">Bagian penutup: catatan serah terima surat dan tanda
      tangan digital penerima.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

      {{-- Catatan Penerimaan / TTD Ekspedisi --}}
      <div class="form-field" style="grid-column: 1 / -1;">
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
            class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:border-slate-700 @error('bukti_penerimaan') is-invalid @enderror"
            value="{{ old('bukti_penerimaan') }}" placeholder="Contoh: Diterima oleh Budi (ttd) — Register 05/2026" />
        </div>
        <small class="text-slate-500 dark:text-slate-400">Catatan tanda serah terima surat (nama pihak penerima, nomor
          register, dsb).</small>
        @error('bukti_penerimaan')
          <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
        @enderror
      </div>

      {{-- Tanda tangan digital (canvas) --}}
      @include('partials.canvas-tanda-tangan')

    </div>
  </div>

  {{-- ================= Tombol aksi ================= --}}
  <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-end gap-3 mt-6 pt-6 border-top border-slate-100 dark:border-slate-700">
    <a href="{{ $cancelUrl }}"
      class="btn btn-white rounded-xl px-4 py-2.5 text-sm font-medium w-full sm:w-auto justify-content-center d-inline-flex align-items-center gap-2 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
      Batal
    </a>
    <button type="submit"
      class="btn btn-primary rounded-xl px-4 py-2.5 text-sm font-medium shadow-sm w-full sm:w-auto justify-content-center d-inline-flex align-items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M20 6L9 17l-5 -5" />
      </svg>
      Simpan Data Surat
    </button>
  </div>
</form>
