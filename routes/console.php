<?php

use App\Jobs\SyncDevicesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Artisan Commands
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Jobs
|--------------------------------------------------------------------------
|
| Sync device dari GenieACS setiap 5 menit.
| Pastikan cron scheduler sudah berjalan:
|   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
|
| Atau jalankan di development dengan: php artisan schedule:work
|
*/

Schedule::job(new SyncDevicesJob)
    ->everyFiveMinutes()
    ->name('sync-genieacs-devices')
    ->withoutOverlapping()         // Cegah job dobel jika eksekusi sebelumnya belum selesai
    ->onFailure(function () {
        \Illuminate\Support\Facades\Log::error('[Scheduler] SyncDevicesJob gagal dijalankan.');
    });
