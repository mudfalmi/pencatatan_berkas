<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Aplikasi sering diakses lewat reverse proxy (ngrok, nginx, dsb.).
        // Proxy meneruskan request sebagai HTTP biasa, sedangkan browser
        // membuka halaman lewat HTTPS. Tanpa mempercayai header
        // X-Forwarded-Proto, Laravel mengira skemanya HTTP sehingga URL aset
        // Vite/Tailwind ditulis sebagai http://... dan browser memblokirnya
        // sebagai mixed content -> CSS tidak termuat sama sekali.
        // Daftar proxy diatur lewat TRUSTED_PROXIES (dipisah koma, atau "*").
        // env() tetap aman di sini karena Railway menyuntikkan variabel sebagai
        // environment variable proses, bukan hanya lewat file .env.
        $trustedProxies = array_map('trim', explode(',', (string) env('TRUSTED_PROXIES', '127.0.0.1,::1')));

        $middleware->trustProxies(
            at: $trustedProxies === ['*'] ? '*' : $trustedProxies,
            headers: Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO,
        );

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(function () {
            // Petugas mendarat di dashboard petugas, Admin di dashboard admin.
            return auth()->user()?->hak_akses === \App\Models\User::HAK_AKSES_PETUGAS
                ? route('petugas.dashboard')
                : route('admin.dashboard');
        });

        // Alias middleware untuk membatasi akses ke modul khusus Admin
        // (Kelola Surat & Arsip, Kelola Pengguna & Hak Akses).
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'petugas' => \App\Http\Middleware\PetugasMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
