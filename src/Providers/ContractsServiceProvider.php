<?php

namespace Maneash\Plugins\Providers;

use Illuminate\Support\ServiceProvider;
use Maneash\Plugins\Contracts\RepositoryInterface;
use Maneash\Plugins\Laravel\LaravelFileRepository;

class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, LaravelFileRepository::class);
    }
}
