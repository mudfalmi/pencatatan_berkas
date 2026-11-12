<nav class="topbar">
  <div class="d-flex align-items-center gap-3">
    <button type="button" class="btn btn-white btn-icon d-lg-none" data-sidebar-toggle aria-label="Buka menu">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 6h16" /><path d="M4 12h16" /><path d="M4 18h16" /></svg>
    </button>
    <div class="d-none d-md-block">
      <h6 class="mb-0 dark:text-slate-100">@yield('page-title', 'Dashboard')</h6>
      <small class="text-secondary dark:text-slate-400">Sistem Informasi Manajemen Surat &amp; Arsip</small>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3">
    {{-- Tombol mode tampilan (terang / gelap) --}}
    <button type="button"
      class="theme-toggle btn-icon bg-white border border-slate-200 p-2 rounded-xl hover:bg-slate-50 transition shadow-sm dark:bg-slate-800 dark:border-slate-700 dark:hover:bg-slate-700 dark:text-slate-200"
      data-theme-toggle aria-label="Aktifkan mode gelap" aria-pressed="false" title="Mode gelap">
      <svg data-theme-icon="moon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" /></svg>
      <svg data-theme-icon="sun" class="d-none" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><circle cx="12" cy="12" r="4" /><path d="M12 2v2" /><path d="M12 20v2" /><path d="M4.93 4.93l1.41 1.41" /><path d="M17.66 17.66l1.41 1.41" /><path d="M2 12h2" /><path d="M20 12h2" /><path d="M4.93 19.07l1.41-1.41" /><path d="M17.66 6.34l1.41-1.41" /></svg>
    </button>

    {{-- Notifikasi akun nonaktif: hanya tersedia untuk Admin --}}
    @if (auth()->user() && auth()->user()->isAdmin())
    <a href="{{ route('admin.users.index') }}" class="btn btn-white btn-icon position-relative dark:bg-slate-800 dark:border-slate-700 dark:hover:bg-slate-700 dark:text-slate-200" aria-label="Notifikasi">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
      @if (($jumlahMenunggu ?? 0) > 0)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $jumlahMenunggu }}</span>
      @endif
    </a>
    @endif

    <div class="dropdown">
      <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-body dark:text-slate-200" data-bs-toggle="dropdown"
        aria-expanded="false">
        <span class="avatar dark:bg-blue-500/15 dark:text-blue-300">{{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'A', 0, 1)) }}</span>
        <span class="d-none d-lg-flex flex-column lh-1">
          <small class="fw-semibold dark:text-slate-200">{{ auth()->user()->nama_lengkap ?? 'Admin' }}</small>
          <small class="text-secondary text-capitalize dark:text-slate-400">{{ auth()->user()->hak_akses ?? 'admin' }}</small>
        </span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li>
          <span class="dropdown-item-text small text-secondary">
            Masuk sebagai <strong class="text-capitalize dark:text-slate-200">{{ auth()->user()->hak_akses ?? 'admin' }}</strong>
          </span>
        </li>
        <li><hr class="dropdown-divider" /></li>
        <li>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item text-danger">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 21h-4a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h4" /><path d="M16 17l5 -5l-5 -5" /><path d="M21 12h-13" /></svg>
              Logout
            </button>
          </form>
        </li>
      </ul>
    </div>
  </div>
</nav>
