{{-- Script panel: Bootstrap 5 (dropdown), tema, dan sidebar/modal.
     Pada hasil build semuanya sudah di-bundle oleh Vite dalam satu file
     (tanpa request ke CDN). Fallback dipakai bila aset build belum ada. --}}
@if (file_exists(public_path('build/manifest.json')))
  @vite('resources/js/app.js')
@else
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="{{ asset('assets/js/theme.js') }}" defer></script>
  <script src="{{ asset('assets/js/admin.js') }}" defer></script>
@endif
