<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ModeloRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    

public function register()
{
    $this->app->singleton(ModeloRepository::class, function ($app) {
        return new ModeloRepository();
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
