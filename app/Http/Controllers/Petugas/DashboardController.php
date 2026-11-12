<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard Petugas: ringkasan surat masuk & arsip digital terunggah.
     *
     * Membaca tabel yang sama dengan panel Admin (surat, arsip) sehingga
     * data yang diinput Petugas langsung tersedia untuk seluruh sistem.
     */
    public function index(): View
    {
        $stats = Surat::ringkasan();

        // Fokus utama dasbor: 5 surat terakhir berdasarkan tanggal surat.
        $suratTerbaru = Surat::query()
            ->ringkas()
            ->latest('tanggal_surat')
            ->latest('id')
            ->limit(5)
            ->get();

        return view('petugas.dashboard', compact('stats', 'suratTerbaru'));
    }
}
