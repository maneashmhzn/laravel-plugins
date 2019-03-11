<?php

namespace Maneash\Plugins;

use Maneash\Plugins\Support\Stub;

class LumenPluginsServiceProvider extends PluginsServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->setupStubPath();
    }

    /**
     * Register all plugins.
     */
    public function register()
    {
        $this->registerNamespaces();
        $this->registerServices();
        $this->registerPlugins();
        $this->registerProviders();
    }

    /**
     * Setup stub path.
     */
    public function setupStubPath()
    {
        Stub::setBasePath(__DIR__ . '/Commands/stubs');

        if (app('plugins')->config('stubs.enabled') === true) {
            Stub::setBasePath(app('plugins')->config('stubs.path'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function registerServices()
    {
        $this->app->singleton(Contracts\RepositoryInterface::class, function ($app) {
            $path = $app['config']->get('plugins.paths.plugins');

            return new Lumen\LumenFileRepository($app, $path);
        });
        $this->app->alias(Contracts\RepositoryInterface::class, 'plugins');
    }
}
