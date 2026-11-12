<div class="sidebar-backdrop" data-sidebar-toggle></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <a href="<?php echo e(auth()->user() && auth()->user()->hak_akses === \App\Models\User::HAK_AKSES_PETUGAS ? route('petugas.dashboard') : route('admin.dashboard')); ?>" class="sidebar-brand">
      <img src="<?php echo e(asset('assets/images/kemenag.png')); ?>" alt="Logo Kemenag" width="40" height="40"
        class="brand-logo-img" decoding="async" />
      <span class="d-flex flex-column lh-1">
        <span class="dark:text-slate-100">PD Pontren</span>
        <small class="text-secondary fw-normal dark:text-slate-400" style="font-size: 0.7rem;">Kemenag Kab. Karawang</small>
      </span>
    </a>
    <button type="button" class="btn btn-white btn-icon sidebar-close dark:bg-slate-800 dark:border-slate-700 dark:hover:bg-slate-700 dark:text-slate-200" data-sidebar-toggle aria-label="Tutup menu">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
    </button>
  </div>

  <div class="sidebar-section">Menu Utama</div>
  <nav class="sidebar-nav">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a
          class="nav-link <?php echo e(request()->routeIs('admin.dashboard') || request()->routeIs('petugas.dashboard') ? 'active' : ''); ?>"
          href="<?php echo e(auth()->user() && auth()->user()->hak_akses === \App\Models\User::HAK_AKSES_PETUGAS ? route('petugas.dashboard') : route('admin.dashboard')); ?>">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" /></svg>
          Dashboard
        </a>
      </li>
      <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->isAdmin()): ?>
          <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('admin.surat.*') ? 'active' : ''); ?>"
              href="<?php echo e(route('admin.surat.index')); ?>">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 4h16v16h-16z" /><path d="M4 10h16" /><path d="M10 4v6" /></svg>
              Kelola Surat &amp; Arsip
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>"
              href="<?php echo e(route('admin.users.index')); ?>">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h4" /><path d="M18 9v4" /></svg>
              Kelola Pengguna &amp; Hak Akses
            </a>
          </li>
        <?php elseif(auth()->user()->hak_akses === \App\Models\User::HAK_AKSES_PETUGAS): ?>
          
          <li class="nav-item">
            <a class="nav-link <?php echo e(request()->routeIs('petugas.surat.*') ? 'active' : ''); ?>"
              href="<?php echo e(route('petugas.surat.index')); ?>">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 4h16v16h-16z" /><path d="M4 10h16" /><path d="M10 4v6" /></svg>
              Kelola Surat &amp; Arsip
            </a>
          </li>
        <?php endif; ?>
      <?php endif; ?>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <form method="POST" action="<?php echo e(route('logout')); ?>">
      <?php echo csrf_field(); ?>
      <button type="submit" class="logout-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 21h-4a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h4" /><path d="M16 17l5 -5l-5 -5" /><path d="M21 12h-13" /></svg>
        Logout
      </button>
    </form>

    
    <div class="sidebar-version">PD Pontren System v1.0</div>
  </div>
</aside>
<?php /**PATH D:\laragon\www\pencatatan_berkas\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>