<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

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
        // Form absensi bersifat publik, jadi dibatasi dua lapis:
        // per karyawan (employee_id dikirim oleh klien, bisa diganti-ganti)
        // dan per alamat IP agar satu pengirim tidak bisa menyapu banyak karyawan.
        RateLimiter::for('attendance', function (Request $request) {
            return [
                Limit::perMinute(3)->by('karyawan:' . ($request->input('employee_id') ?: $request->ip())),
                Limit::perMinute(30)->by('ip:' . $request->ip()),
            ];
        });
    }
}
