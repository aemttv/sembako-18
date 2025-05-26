<?php

namespace App\Providers;

use App\Models\Notifications;
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
        View::composer('*', function ($view) {
            $notifications = [];
            $user = session('user_data');
            $idAkun = $user ? $user->idAkun : null;
            if ($idAkun) {
                $notifications = Notifications::where('idAkun', $idAkun)
                    ->where('read', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            $view->with('globalNotifications', $notifications);
        });
    }
}
