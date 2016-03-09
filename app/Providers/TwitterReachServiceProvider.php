<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TwitterReach;
use Log;

class TwitterReachServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind('App\Interfaces\TwitterReachInterface', function () {
            try {
                return new TwitterReach();
            } catch (\Exception $e) {
                Log::error($e);
            }

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Interfaces\TwitterReachInterface'];
    }

}