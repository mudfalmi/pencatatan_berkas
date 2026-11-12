@extends('layouts.admin')

@section('page-title', 'Edit Akun Pengguna')

@section('content')
  <div class="row g-6">
    <div class="col-lg-8 col-12">
      <div class="card card-lg">
        <div class="card-header border-bottom-0">
          <div>
            <h5 class="mb-0">Edit Akun &amp; Hak Akses User</h5>
            <small class="text-secondary">Perbarui data akun <strong>{{ $user->username }}</strong></small>
          </div>
        </div>

        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger py-2 small" role="alert">
              <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="nama_lengkap" id="nama_lengkap"
                class="form-control @error('nama_lengkap') is-invalid @enderror"
                value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required />
              @error('nama_lengkap')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
              <input type="text" name="username" id="username"
                class="form-control @error('username') is-invalid @enderror"
                value="{{ old('username', $user->username) }}" required />
              @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" name="password" id="password"
                  class="form-control @error('password') is-invalid @enderror"
                  placeholder="Kosongkan jika tidak diganti" />
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                  placeholder="Ulangi password baru" />
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="hak_akses" class="form-label">Hak Akses <span class="text-danger">*</span></label>
                <select name="hak_akses" id="hak_akses" class="form-select @error('hak_akses') is-invalid @enderror" required>
                  <option value="petugas" {{ old('hak_akses', $user->hak_akses) === 'petugas' ? 'selected' : '' }}>Petugas</option>
                  <option value="admin" {{ old('hak_akses', $user->hak_akses) === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('hak_akses')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 mb-4">
                <label for="status" class="form-label">Status Akun <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                  <option value="aktif" {{ old('status', $user->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                  <option value="nonaktif" {{ old('status', $user->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 12l5 5l10 -10" />
                </svg>
                Simpan Perubahan
              </button>
              <a href="{{ route('admin.users.index') }}" class="btn btn-white">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4 col-12">
      <div class="card card-lg">
        <div class="card-body">
          <h6 class="mb-3">Informasi Akun</h6>
          <ul class="list-unstyled small text-secondary d-flex flex-column gap-2 mb-0">
            <li><strong class="text-body">Username:</strong> {{ $user->username }}</li>
            <li><strong class="text-body">Dibuat:</strong> {{ $user->created_at?->format('d M Y H:i') ?? '-' }}</li>
            <li><strong class="text-body">Diperbarui:</strong> {{ $user->updated_at?->format('d M Y H:i') ?? '-' }}</li>
          </ul>
          <hr class="border-dashed" />
          <p class="small text-secondary mb-2">
            Kosongkan kolom password jika Anda tidak ingin mengubah password akun ini.
          </p>
          @if ($user->id === auth()->id())
            <div class="alert alert-info py-2 small mb-0" role="alert">
              Ini adalah akun Anda sendiri — hak akses dan status tidak dapat diturunkan.
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
