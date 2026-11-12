<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  @include('partials.head-meta')
  <title>@yield('page-title', 'Dashboard') - Kemenag Karawang</title>
  @include('partials.head-links')
  {{-- Mode tampilan (terang/gelap): di-inline di <head> supaya tema
       tersimpan langsung diterapkan tanpa kedipan (FOUC) dan tanpa
       request script yang memblokir render --}}
  @include('partials.theme-init')
</head>

<body>
  <div>
    @include('partials.sidebar')

    <!-- Main Content -->
    <div id="content" class="position-relative h-100">
      @include('partials.topbar')

      <div class="custom-container px-4 sm:px-6 lg:px-8">
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="me-1 align-middle"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 12l5 5l10 -10" /></svg>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
          </div>
        @endif

        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="me-1 align-middle"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 8v4" /><path d="M12 16h.01" /><circle cx="12" cy="12" r="9" /></svg>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
          </div>
        @endif

        @yield('content')
      </div>
    </div>
  </div>

  {{-- Modal (dialog) diletakkan langsung di <body> agar tidak terjepit
       stacking context .custom-container yang memiliki animasi transform --}}
  @yield('modals')

  @include('partials.scripts')
</body>

</html>
