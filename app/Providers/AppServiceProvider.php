<?php

namespace App\Providers;

use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema;
// Import Builder where defaultStringLength method is defined
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Builder::defaultStringLength(191); // Update defaultStringLength
        Schema::defaultStringLength(191);

        view()->composer('*', function ($view) {
            if (auth()->check()) {
                $role = auth()->user()->role;
                $view->with('role', $role);
            }
        });
    }

}
