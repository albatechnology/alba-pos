<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        \Laravel\Sanctum\Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
