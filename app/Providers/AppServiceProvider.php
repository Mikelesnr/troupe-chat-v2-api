<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use App\Enums\UserRole;
use Illuminate\Auth\Notifications\ResetPassword;

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
        Vite::prefetch(concurrency: 3);

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        // Force HTTPS in production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Role-based gates
        Gate::define('isAdmin', fn($user) => $user->role === UserRole::Admin);
        Gate::define('isModerator', fn($user) => $user->role === UserRole::Moderator);
        Gate::define('isTrouper', fn($user) => $user->role === UserRole::Trouper);

        // Composite gates
        Gate::define(
            'isStaff',
            fn($user) =>
            in_array($user->role, [UserRole::Admin, UserRole::Moderator])
        );

        Gate::define(
            'isNotAdmin',
            fn($user) =>
            $user->role !== UserRole::Admin
        );
    }
}
