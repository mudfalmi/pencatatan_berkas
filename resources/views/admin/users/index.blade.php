@extends('layouts.admin')

@section('page-title', 'Kelola Pengguna & Hak Akses')

@section('content')
  <div class="row g-6">
    <div class="col-12">
      <div class="card card-lg">
        <div class="card-header border-bottom-0 p-6 d-flex flex-wrap align-items-center justify-content-between gap-3">
          <div>
            <h5 class="mb-0 fw-bold">Kelola Akun &amp; Hak Akses Pengguna</h5>
            <small class="text-secondary">Total {{ $users->total() }} akun terdaftar</small>
          </div>
          <a href="{{ route('admin.users.create') }}"
            class="btn btn-primary btn-lift d-inline-flex align-items-center justify-content-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 5l0 14" /><path d="M5 12l14 0" />
            </svg>
            Tambah Akun
          </a>
        </div>

        {{-- Pencarian & filter --}}
        <div class="px-6 pb-6">
          <form method="GET" action="{{ route('admin.users.index') }}" class="bg-slate-50/80 border rounded-xl p-4 dark:bg-slate-50/80">
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
                    placeholder="Nama lengkap atau username..." value="{{ request('q') }}" />
                </div>
              </div>
              <div class="col-lg-4 col-md-4">
                <label for="hak_akses" class="form-label text-xs fw-semibold text-slate-500 tracking-wider text-uppercase dark:text-slate-400">Hak
                  Akses</label>
                <select name="hak_akses" id="hak_akses" class="form-select">
                  <option value="">Semua hak akses</option>
                  <option value="admin" {{ request('hak_akses') === 'admin' ? 'selected' : '' }}>Admin</option>
                  <option value="petugas" {{ request('hak_akses') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
              </div>
              <div class="col-lg-2 col-md-3 d-grid">
                <button type="submit"
                  class="btn btn-primary rounded-lg shadow-sm d-inline-flex align-items-center justify-content-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 6h16" /><path d="M7 12h10" /><path d="M10 18h4" />
                  </svg>
                  Filter
                </button>
              </div>
            </div>
          </form>
        </div>

        <div class="table-responsive">
          <table class="table text-nowrap mb-0 table-centered table-hover table-soft users-table">
            <thead>
              <tr>
                <th style="width: 3rem;">No.</th>
                <th>Nama Lengkap</th>
                <th>Username</th>
                <th>Hak Akses</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $index => $user)
                <tr>
                  <td class="text-secondary">{{ $users->firstItem() + $index }}</td>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <span class="avatar">{{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}</span>
                      <span>{{ $user->nama_lengkap }}</span>
                    </div>
                  </td>
                  <td>{{ $user->username }}</td>
                  <td>
                    @if ($user->isAdmin())
                      <span class="badge text-warning-emphasis bg-warning-subtle">Admin</span>
                    @else
                      <span class="badge text-info-emphasis bg-info-subtle">Petugas</span>
                    @endif
                  </td>
                  <td>
                    @if ($user->isActive())
                      <span class="badge text-success-emphasis bg-success-subtle">Aktif</span>
                    @else
                      <span class="badge text-danger-emphasis bg-danger-subtle">Nonaktif</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <a href="{{ route('admin.users.edit', $user) }}"
                        class="btn p-2.5 rounded-xl bg-amber-50 text-amber-600 border border-amber-200/60 hover:bg-amber-100 hover:text-amber-600 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center dark:bg-amber-50 dark:text-amber-600 dark:border-amber-200/60 dark:hover:bg-amber-100"
                        title="Edit Akun" aria-label="Edit akun {{ $user->nama_lengkap }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M12 20h9" /><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1 -4L16.5 3.5z" />
                        </svg>
                      </a>
                      <button type="button"
                        class="btn p-2.5 rounded-xl bg-red-50 text-red-600 border border-red-200/60 hover:bg-red-100 hover:text-red-600 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center dark:bg-red-50 dark:text-red-600 dark:border-red-200/60 dark:hover:bg-red-100"
                        data-delete-modal="deleteUserModal" data-url="{{ route('admin.users.destroy', $user) }}"
                        data-name="{{ $user->nama_lengkap }}" title="Hapus Akun"
                        aria-label="Hapus akun {{ $user->nama_lengkap }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M4 7h16" /><path d="M10 11v6" /><path d="M14 11v6" />
                          <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3h6v3" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-secondary py-5">
                    Tidak ada akun yang cocok dengan pencarian Anda.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="card-body border-top">
          {{ $users->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection

{{-- Modal konfirmasi hapus: dirender di level <body> agar tidak
     terjepit stacking context .custom-container (animasi transform) --}}
@section('modals')
  <div class="confirm-modal fixed inset-0 flex items-center justify-center p-4" id="deleteUserModal" role="dialog"
    aria-modal="true" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="confirm-modal__backdrop"></div>

    <div class="confirm-modal__box">
      <button type="button" class="confirm-modal__close" data-modal-close aria-label="Tutup modal">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M18 6l-12 12" /><path d="M6 6l12 12" />
        </svg>
      </button>

      <div class="bg-red-100 text-red-600 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3 dark:bg-red-100 dark:text-red-600">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M4 7h16" /><path d="M10 11v6" /><path d="M14 11v6" />
          <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3h6v3" />
        </svg>
      </div>

      <h5 class="confirm-modal__title" id="deleteUserModalLabel">Konfirmasi Hapus Akun</h5>

      <p class="confirm-modal__body">
        Apakah Anda yakin ingin menghapus akun <strong id="deleteUserName" data-delete-name>pengguna ini</strong>?
        Tindakan ini tidak dapat dibatalkan.
      </p>

      <div class="confirm-modal__footer flex justify-center gap-3 mt-6">
        <button type="button"
          class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2.5 rounded-xl font-medium transition dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-slate-200"
          data-modal-close>Batal</button>
        <form id="deleteUserForm" data-delete-form method="POST" action="">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-medium shadow-md shadow-red-200 transition hover:-translate-y-0.5 dark:bg-red-600 dark:hover:bg-red-700 dark:shadow-none"
            data-modal-confirm>Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
@endsection
