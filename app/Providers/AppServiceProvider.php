<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceHttps();
    }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pagination memakai markup Bootstrap 5
        Paginator::useBootstrapFive();

        // Cegah N+1 query akibat lazy loading relasi saat development.
        // Bila ada relasi yang lupa di-eager-load, Laravel akan melempar
        // exception sehingga masalah performa cepat terdeteksi.
        Model::preventLazyLoading(! app()->isProduction());

        // Badge notifikasi akun nonaktif dipakai di seluruh halaman admin.
        // Query COUNT-nya di-cache agar tidak dijalankan pada setiap request.
        View::composer('partials.topbar', function ($view): void {
            $user = auth()->user();

            if (! $user || ! $user->isAdmin()) {
                $view->with('jumlahMenunggu', 0);

                return;
            }

            $jumlahMenunggu = Cache::remember(
                User::CACHE_KEY_NONAKTIF,
                now()->addMinutes(5),
                fn (): int => User::where('status', User::STATUS_NONAKTIF)->count()
            );

            $view->with('jumlahMenunggu', $jumlahMenunggu);
        });
    }
}
