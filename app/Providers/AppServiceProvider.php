<?php

namespace App\Providers;

use App\Models\BarangDetail;
use App\Models\Notifications;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
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
        // Only run in web environment (not for console commands)
        if ($this->app->runningInConsole()) {
            return;
        }

        // Run the check on application boot
        $this->checkBarangExpiration();

        View::composer('*', function ($view) {
            $notifications = [];
            $unreadNotifications = [];
            $user = session('user_data');
            $idAkun = $user ? $user->idAkun : null;
            if ($idAkun) {
                $notifications = Notifications::where('idAkun', $idAkun)->where('read', true)->orderBy('created_at', 'desc')->get();

                $unreadNotifications = Notifications::where('idAkun', session('user_data')->idAkun)->where('read', false)->orderBy('created_at', 'desc')->get();
            }

            $view->with('unreadNotifications', $unreadNotifications);
            $view->with('globalNotifications', $notifications);
        });
    }

    protected function checkBarangExpiration()
    {
        try {
            $startTime = microtime(true);
            $now = Carbon::now();
            $updatedCount = 0;

            // Process in chunks for memory efficiency
            BarangDetail::whereNotNull('tglKadaluarsa')
                ->whereNotNull('tglMasuk')
                ->chunkById(200, function ($details) use ($now, &$updatedCount) {
                    foreach ($details as $detail) {
                        $tglKadaluarsa = Carbon::parse($detail->tglKadaluarsa);
                        $daysToExpire = $now->diffInDays($tglKadaluarsa, false);

                        if ($daysToExpire < 0) {
                            $newKondisi = 'Kadaluarsa';
                        } elseif ($daysToExpire <= 3) {
                            $newKondisi = 'Mendekati Kadaluarsa';
                        } else {
                            $newKondisi = 'Baik';
                        }

                        if ($detail->kondisiBarang !== $newKondisi) {
                            $detail->kondisiBarang = $newKondisi;
                            $detail->save();
                            $updatedCount++;
                            Log::info("Updated ID {$detail->idDetailBarang} to {$newKondisi}");
                        }
                    }
                });

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info("Kondisi barang update completed. Updated {$updatedCount} items in {$executionTime}ms");
        } catch (\Exception $e) {
            Log::error('Barang expiration check failed: ' . $e->getMessage());
        }
    }
}
