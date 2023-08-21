<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\DataAccessors;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(DataAccessors\ClientDataAccessorInterface::class, function () {
            return new DataAccessors\Cache\ClientDataAccessor(
                new DataAccessors\MySQL\ClientDataAccessor()
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
