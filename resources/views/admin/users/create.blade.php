@extends('layouts.admin')

@section('page-title', 'Tambah Pengguna Baru')

@section('content')
  {{-- ================= Header halaman ================= --}}
  <div class="d-flex align-items-center gap-3 mb-6">
    <a href="{{ route('admin.users.index') }}"
      class="btn bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 rounded-xl px-4 py-2 text-sm font-medium shadow-sm d-inline-flex align-items-center gap-2 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
        <path d="M19 12H5" /><path d="M12 19l-7 -7 7 -7" />
      </svg>
      Kembali
    </a>
    <div>
      <h4 class="mb-1 fw-bold">Tambah Pengguna Baru</h4>
      <p class="mb-0 text-slate-500 text-sm dark:text-slate-400">Isi formulir di bawah untuk mendaftarkan pengguna baru</p>
    </div>
  </div>

  {{-- ================= Kartu form utama ================= --}}
  <div class="mx-auto" style="max-width: 56rem;">
    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-6 md:p-8 dark:bg-slate-800 dark:border-slate-700">

        {{-- Section header --}}
        <div class="d-flex align-items-center gap-3 mb-6">
          <div class="bg-blue-50 text-blue-600 p-3 rounded-xl d-inline-flex align-items-center justify-content-center dark:bg-blue-500/15 dark:text-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M20 21v-2a4 4 0 0 0-4 -4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />
            </svg>
          </div>
          <div>
            <h5 class="mb-0 fw-bold">Informasi Akun</h5>
            <p class="mb-0 text-slate-500 text-sm dark:text-slate-400">Nama, username, password, dan hak akses akun</p>
          </div>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger py-2 small mb-6" role="alert">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
          @csrf

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama Lengkap --}}
            <div class="form-field">
              <label for="nama_lengkap" class="form-label fw-semibold text-slate-700 text-sm mb-2">Nama Lengkap
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M20 21v-2a4 4 0 0 0-4 -4H8a4 4 0 0 0-4 4v2" /><circle cx="12" cy="7" r="4" />
                  </svg>
                </span>
                <input type="text" name="nama_lengkap" id="nama_lengkap"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('nama_lengkap') is-invalid @enderror"
                  value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap petugas/admin" required />
                @error('nama_lengkap')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Username --}}
            <div class="form-field">
              <label for="username" class="form-label fw-semibold text-slate-700 text-sm mb-2">Username
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="12" r="4" /><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-4 8" />
                  </svg>
                </span>
                <input type="text" name="username" id="username"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('username') is-invalid @enderror"
                  value="{{ old('username') }}" placeholder="Username untuk login" required />
                @error('username')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Password --}}
            <div class="form-field">
              <label for="password" class="form-label fw-semibold text-slate-700 text-sm mb-2">Password
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />
                  </svg>
                </span>
                <input type="password" name="password" id="password"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 pr-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('password') is-invalid @enderror"
                  placeholder="Minimal 6 karakter" required />
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
                @error('password')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div class="form-field">
              <label for="password_confirmation" class="form-label fw-semibold text-slate-700 text-sm mb-2">Konfirmasi
                Password <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />
                  </svg>
                </span>
                <input type="password" name="password_confirmation" id="password_confirmation"
                  class="form-control w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 pr-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                  placeholder="Ulangi password" required />
                <button type="button" class="form-field__action" data-password-toggle="password_confirmation"
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
            </div>

            {{-- Hak Akses --}}
            <div class="form-field">
              <label for="hak_akses" class="form-label fw-semibold text-slate-700 text-sm mb-2">Hak Akses
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 22s8 -4 8 -10V5l-8 -3 -8 3v7c0 6 8 10 8 10Z" />
                  </svg>
                </span>
                <select name="hak_akses" id="hak_akses"
                  class="form-select w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 pr-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('hak_akses') is-invalid @enderror"
                  required>
                  <option value="petugas" {{ old('hak_akses', 'petugas') === 'petugas' ? 'selected' : '' }}>Petugas
                  </option>
                  <option value="admin" {{ old('hak_akses') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <span class="form-field__chevron">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M6 9l6 6 6 -6" />
                  </svg>
                </span>
                @error('hak_akses')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            {{-- Status Akun --}}
            <div class="form-field">
              <label for="status" class="form-label fw-semibold text-slate-700 text-sm mb-2">Status Akun
                <span class="text-danger">*</span></label>
              <div class="form-field__control">
                <span class="form-field__icon">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="12" r="9" /><path d="M12 8v4" /><path d="M12 16h.01" />
                  </svg>
                </span>
                <select name="status" id="status"
                  class="form-select w-100 rounded-xl border border-slate-200 text-sm px-3 py-2.5 pl-10 pr-10 text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 @error('status') is-invalid @enderror"
                  required>
                  <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                  <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <span class="form-field__chevron">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M6 9l6 6 6 -6" />
                  </svg>
                </span>
                @error('status')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

          </div>

          {{-- ================= Tombol aksi ================= --}}
          <div class="d-flex align-items-center justify-content-end gap-3 mt-6 pt-6 border-top border-slate-100 dark:border-slate-700">
            <a href="{{ route('admin.users.index') }}"
              class="btn btn-white rounded-xl px-4 py-2.5 text-sm font-medium d-inline-flex align-items-center gap-2 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200">
              Batal
            </a>
            <button type="submit"
              class="btn btn-primary rounded-xl px-4 py-2.5 text-sm font-medium shadow-sm d-inline-flex align-items-center justify-content-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M20 6L9 17l-5 -5" />
              </svg>
              Simpan Akun
            </button>
          </div>
        </form>
      </div>
  </div>
@endsection
