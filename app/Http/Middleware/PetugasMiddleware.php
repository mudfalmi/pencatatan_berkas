<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses ke modul khusus Petugas (PD Pontren).
 *
 * Petugas hanya boleh: menginput & mengelola surat masuk, melihat arsip
 * digital yang diunggah, serta dashboard ringkas. Petugas tidak dapat
 * mengakses modul khusus Admin (kelola pengguna, dsb).
 */
class PetugasMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Belum login -> arahkan ke halaman login.
        if (! $user) {
            return redirect()->guest(route('login'));
        }

        // Admin yang membuka route petugas -> arahkan ke panel Admin.
        if ($user->isAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('info', 'Anda sedang login sebagai Admin. Modul ini diperuntukkan untuk Petugas.');
        }

        // Bukan petugas (dan bukan admin) -> tolak dengan pesan yang jelas.
        if ($user->hak_akses !== \App\Models\User::HAK_AKSES_PETUGAS) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Halaman ini hanya dapat diakses oleh Petugas.');
        }

        return $next($request);
    }
}
