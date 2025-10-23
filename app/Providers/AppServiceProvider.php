<?php

namespace App\Providers;
use App\Models\Evenement;
use App\Policies\EvenementPolicy;
//use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Pail\PailServiceProvider::class)) {
            $this->app->register(\Laravel\Pail\PailServiceProvider::class);
        }
    }

    public function boot(): void
    {
        // Share notifications data with the admin dashboard layout
        View::composer('admin.dashboard', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Vérifier si l'utilisateur est admin en utilisant une requête directe
                $isAdmin = \Spatie\Permission\Models\Role::where('name', 'admin')
                    ->whereHas('users', function($query) use ($user) {
                        $query->where('users.id', $user->id);
                    })->exists();
                
                if ($isAdmin) {
                    // Récupérer les notifications directement depuis la table
                    $notifications = DatabaseNotification::where('notifiable_id', $user->id)
                        ->where('notifiable_type', User::class)
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

                    $unreadNotificationsCount = DatabaseNotification::where('notifiable_id', $user->id)
                        ->where('notifiable_type', User::class)
                        ->whereNull('read_at')
                        ->count();

                    $view->with('unreadNotificationsCount', $unreadNotificationsCount);
                    $view->with('notifications', $notifications);
                } else {
                    $view->with('unreadNotificationsCount', 0);
                    $view->with('notifications', collect());
                }
            } else {
                $view->with('unreadNotificationsCount', 0);
                $view->with('notifications', collect());
            }
        });
    }
}
