<?php

namespace App\Providers;

use App\Models\Santri;
use App\Models\Setoran;
use App\Observers\SantriObserver;
use App\Observers\SetoranObserver;
use App\Policies\SantriPolicy;
use App\Policies\SetoranPolicy;
use App\Services\EvaluasiService;
use App\Services\HafalanService;
use App\Services\LaporanService;
use App\Services\NotifikasiService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(HafalanService::class);
        $this->app->singleton(EvaluasiService::class);
        $this->app->singleton(NotifikasiService::class);
        $this->app->singleton(LaporanService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan observer
        Santri::observe(SantriObserver::class);
        Setoran::observe(SetoranObserver::class);

        // Daftarkan policy
        Gate::policy(Santri::class, SantriPolicy::class);
        Gate::policy(Setoran::class, SetoranPolicy::class);
    }
}
