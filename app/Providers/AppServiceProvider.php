<?php

namespace App\Providers;
use App\Models\Evenement;
use App\Policies\EvenementPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
    public function boot(): void {
        $this->registerPolicies();
    }
    protected $policies = [
        Evenement::class => EvenementPolicy::class,
    ];



}
