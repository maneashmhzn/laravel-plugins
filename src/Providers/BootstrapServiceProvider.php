<?php

namespace Demo\Plugins\Providers;

use Illuminate\Support\ServiceProvider;

class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->app['plugins']->boot();
    }

    /**
     * Register the provider.
     */
    public function register()
    {
        $this->app['plugins']->register();
    }
}
