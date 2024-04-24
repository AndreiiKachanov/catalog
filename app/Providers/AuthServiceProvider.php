<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerPermissions();
    }

    private function registerPermissions(): void
    {
        Gate::define('admin-panel', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('vk-parser', function (User $user) {
            return $user->isAdmin();
        });
    }
}
