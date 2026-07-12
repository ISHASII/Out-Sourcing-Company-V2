<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
     * Set Carbon locale to Indonesian so all date/time formatting
     * (isoFormat, diffForHumans, etc.) uses Bahasa Indonesia.
     */
    public function boot(): void
    {
        // Set locale Carbon ke Bahasa Indonesia (global, semua halaman)
        Carbon::setLocale('id');

        // Set timezone aplikasi ke WIB (UTC+7)
        config(['app.timezone' => 'Asia/Jakarta']);
        date_default_timezone_set('Asia/Jakarta');
    }
}
