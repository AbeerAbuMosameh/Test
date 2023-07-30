<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
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
    public function boot():void
    {
        $preferredLocale = request()->header('Accept-Language');
        $availableLocales = ['en', 'ar'];
        App::setLocale($preferredLocale && in_array($preferredLocale, $availableLocales) ? $preferredLocale : 'en');
    }
}
