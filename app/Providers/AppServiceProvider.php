<?php

namespace App\Providers;

use Carbon\Carbon;
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
        // Nama bulan & tanggal dalam bahasa Indonesia
        Carbon::setLocale('id');

        // Set default pagination view to Bootstrap 5
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
