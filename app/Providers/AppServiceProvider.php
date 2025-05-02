<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Auth;

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
        Event::listen(Login::class, function ($event) {
            ActivityLog::create([
                'user_id' => $event->user->id,
                'activity' => 'Logged in',
            ]);
        });
    
        Event::listen(Logout::class, function ($event) {
            $userId = Auth::id() ?? ($event->user->id ?? null);
            if ($userId) {
                ActivityLog::create([
                    'user_id' => $userId,
                    'activity' => 'Logged out',
                ]);
            }
        });
    }
}
