<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\SuratMasuk::observe(\App\Observers\SuratMasukObserver::class);
        \App\Models\Disposisi::observe(\App\Observers\DisposisiObserver::class);
        \App\Models\DrafSurat::observe(\App\Observers\DrafSuratObserver::class);
        \App\Models\ReviuSurat::observe(\App\Observers\ReviuSuratObserver::class);
        \App\Models\SuratFinal::observe(\App\Observers\SuratFinalObserver::class);
    }
}
