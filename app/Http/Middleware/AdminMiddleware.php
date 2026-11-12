<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatasi akses ke halaman khusus Admin.
 *
 * Sesuai spesifikasi hak akses: Admin standardised mengelola akun pengguna,
 * data surat, arsip digital, dan hak akses sistem. Petugas tidak memiliki
 * akses ke modul-modul tersebut.
 */
class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Belum login -> arahkan ke halaman login.
        if (! $user) {
            return redirect()->guest(route('login'));
        }

        // Sudah login tetapi bukan Admin -> tolak dengan pesan yang jelas.
        if (! $user->isAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Halaman ini hanya dapat diakses oleh Admin.');
        }

        return $next($request);
    }
}
