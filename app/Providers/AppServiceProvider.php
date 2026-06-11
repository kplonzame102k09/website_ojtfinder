<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use Illuminate\View\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // Important for HTTPS fix

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // FIX: Tell Laravel that the public assets are now in htdocs
        $this->app->bind('path.public', function() {
            return base_path(); 
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FIX: Force HTTPS for CSS/JS links if your site uses SSL
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        view()->composer('*', function ($view) {
            if (auth()->check()) {
                // This shares the variable with EVERY view automatically
                $view->with('globalUnreadMessages', auth()->user()->unreadMessagesCount());
                $view->with('unreadNotifs', auth()->user()->unreadNotifications->count());
            } else {
                // Default values for guests so the site doesn't crash
                $view->with(['globalUnreadMessages' => 0, 'unreadNotifs' => 0]);
            }
        });
    }    
}