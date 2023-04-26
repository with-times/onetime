<?php

namespace App\Providers;

use App\Models\Web\Subscribe;
use App\Models\Web\WebSite;
use App\Observers\SubscribeObserver;
use App\Observers\WebsiteObserver;
use Illuminate\Support\Facades\Schema;
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
        Schema::defaultStringLength(191);

        WebSite::observe(WebsiteObserver::class);
        Subscribe::observe(SubscribeObserver::class);


    }
}
