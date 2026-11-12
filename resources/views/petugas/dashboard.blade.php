@extends('layouts.admin')

@section('page-title', 'Dashboard Petugas')

@section('content')
  {{-- Banner sambutan ringkas: gradasi light indigo -> soft cyan --}}
  <div class="welcome-banner welcome-banner-sm mb-4">
    <span class="banner-blob banner-blob-1"></span>
    <span class="banner-blob banner-blob-2"></span>

    <div class="position-relative">
      <span class="banner-eyebrow mb-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M12 3l2 5l5 2l-5 2l-2 5l-2 -5l-5 -2l5 -2z" />
        </svg>
        Panel Petugas
      </span>
      <h1 class="fs-4 fw-bold mb-1">👋 Selamat Datang, {{ auth()->user()->nama_lengkap }}!</h1>
      <p class="text-secondary mb-3">Input &amp; Kelola Surat &amp; Arsip — PD Pontren Kemenag Kabupaten Karawang.</p>

      {{-- Aksi utama: di bawah deskripsi, satu baris dengan area teks (tidak menabrak ikon kanan) --}}
      <a href="{{ route('petugas.surat.create') }}" class="btn btn-primary btn-lift justify-content-center">
        + Input Surat
      </a>
    </div>

    {{-- Ikon dokumen: tepi kanan banner, vertikal terpusat --}}
    <div class="banner-art-slot d-none d-md-block" style="right: 2.5rem;">
      <div class="banner-art" style="width: 7rem; height: 7rem;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
          stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 4rem; height: 4rem;">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" /><path d="M14 2v6h6" />
          <path d="M12 18v-6" /><path d="M9 15l3 3 3 -3" />
        </svg>
      </div>
    </div>
  </div>

  {{-- 2 kartu statistik compact --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
    {{-- 1. Total Surat & Arsip --}}
    <div class="bg-blue-50 border border-blue-100/80 rounded-2xl p-4 sm:p-5 shadow-sm d-flex flex-column gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-blue-500/15 dark:border-blue-500/30">
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
        <div class="stat-value text-2xl font-bold text-blue-600 dark:text-blue-300">{{ $stats['total_surat'] }}</div>
        <a href="{{ route('petugas.surat.index') }}" class="text-secondary small fw-medium text-decoration-none">Kelola →</a>
      </div>
    </div>

    {{-- 2. Arsip Digital Terunggah --}}
    <div class="bg-emerald-50 border border-emerald-100/80 rounded-2xl p-4 sm:p-5 shadow-sm d-flex flex-column gap-3 transition-all duration-300 hover:-translate-y-1 hover:shadow-md dark:bg-emerald-500/15 dark:border-emerald-500/30">
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
        <div class="stat-value text-2xl font-bold text-emerald-600 dark:text-emerald-300">{{ $stats['total_arsip'] }}</div>
        <a href="{{ route('petugas.surat.create') }}" class="text-secondary small fw-medium text-decoration-none">Unggah →</a>
      </div>
    </div>
  </div>

  {{-- Tabel arsip surat terbaru (maks. 5 data) --}}
  <div class="card card-lg">
    <div class="card-header border-bottom-0 d-flex flex-wrap align-items-center justify-content-between gap-2">
      <div>
        <h5 class="mb-0 fw-bold">Arsip Surat Terbaru</h5>
        <small class="text-secondary">5 surat terakhir berdasarkan tanggal surat</small>
      </div>
      <a href="{{ route('petugas.surat.index') }}" class="btn btn-white btn-sm">Kelola Semua</a>
    </div>
    <div class="table-responsive overflow-x-auto">
      <table class="table text-nowrap mb-0 table-centered table-hover table-soft">
        <thead>
          <tr>
            <th>Nomor Surat</th>
            <th>Tanggal</th>
            <th>Perihal</th>
            <th>Penerima / Lembaga</th>
            <th>Jenis</th>
            <th>Arsip</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($suratTerbaru as $surat)
            <tr>
              <td class="py-3">
                <a href="{{ route('petugas.surat.edit', $surat) }}" class="fw-semibold text-decoration-none">
                  {{ $surat->nomor_surat }}
                </a>
              </td>
              <td class="text-secondary py-3">{{ $surat->tanggal_surat->format('d M Y') }}</td>
              <td class="py-3" style="max-width: 22rem; white-space: normal;">{{ $surat->perihal }}</td>
              <td class="py-3">{{ $surat->nama_penerima_lembaga }}</td>
              <td class="py-3"><span class="badge text-info-emphasis bg-info-subtle">{{ $surat->jenis_surat }}</span></td>
              <td class="py-3">
                @if ($surat->hasDokumen())
                  <span class="badge badge-soft badge-soft-success">
                    <span class="badge-dot"></span>{{ strtoupper($surat->ekstensiDokumen() ?: 'file') }}
                  </span>
                @else
                  <span class="badge text-secondary bg-secondary-subtle">Belum ada arsip</span>
                @endif
              </td>
              <td class="text-center py-3">
                <div class="d-inline-flex justify-content-center align-items-center gap-1.5">
                  @if ($surat->hasDokumen())
                    <a href="{{ route('petugas.surat.file', $surat) }}" target="_blank" rel="noopener"
                      class="btn p-0 w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-slate-200 hover:bg-slate-50 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center"
                      title="Lihat Arsip" aria-label="Lihat arsip surat {{ $surat->nomor_surat }}">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        class="w-3.5 h-3.5">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M2 12s3.5 -7 10 -7 10 7 10 7 -3.5 7 -10 7 -10 -7 -10 -7Z" />
                        <circle cx="12" cy="12" r="3" />
                      </svg>
                    </a>
                    <a href="{{ route('petugas.surat.download', $surat) }}"
                      class="btn p-0 w-8 h-8 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-50 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center"
                      title="Unduh Arsip" aria-label="Unduh arsip surat {{ $surat->nomor_surat }}">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                        class="w-3.5 h-3.5">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M21 15v4a2 2 0 0 1 -2 2H5a2 2 0 0 1 -2 -2v-4" /><path d="M7 10l5 5 5 -5" /><path d="M12 15V3" />
                      </svg>
                    </a>
                  @endif
                  <a href="{{ route('petugas.surat.edit', $surat) }}"
                    class="btn p-0 w-8 h-8 rounded-lg bg-amber-50 text-amber-600 border border-amber-200/60 hover:bg-amber-100 hover:text-amber-600 transition-all duration-150 hover:scale-105 d-inline-flex align-items-center justify-content-center dark:bg-amber-50 dark:text-amber-600 dark:border-amber-200/60 dark:hover:bg-amber-100"
                    title="Edit Surat" aria-label="Edit surat {{ $surat->nomor_surat }}">
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
          @empty
            <tr>
              <td colspan="7" class="text-center text-secondary py-5">Belum ada data surat tercatat.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
