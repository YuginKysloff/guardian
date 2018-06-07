<?php

namespace Rennokki\Guardian;

use Illuminate\Support\ServiceProvider;

class GuardianServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/guardian.php' => config_path('guardian.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/2018_06_07_123211_permissions.php' => database_path('migrations/2018_06_07_123211_permissions.php'),
        ], 'migration');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
