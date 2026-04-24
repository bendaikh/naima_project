<?php

namespace App\Providers;

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
        // Share company parameters with all views
        view()->composer('*', function ($view) {
            $companyParams = \App\Models\ParametresEntreprise::get();
            $view->with('companyParams', $companyParams);
        });
    }
}
