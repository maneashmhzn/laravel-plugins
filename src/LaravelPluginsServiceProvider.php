<?php

namespace Demo\Plugins;

use Demo\Plugins\Support\Stub;

class LaravelPluginsServiceProvider extends PluginsServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerPlugins();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerServices();
        $this->setupStubPath();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        Stub::setBasePath(__DIR__ . '/Commands/stubs');

        $this->app->booted(function ($app) {
            if ($app['plugins']->config('stubs.enabled') === true) {
                Stub::setBasePath($app['plugins']->config('stubs.path'));
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices()
    {
        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('plugins.paths.plugins');

            return new Laravel\LaravelFileRepository($app, $path);
        });
        $this->app->alias(Contracts\RepositoryInterface::class, 'plugins');
    }
}
