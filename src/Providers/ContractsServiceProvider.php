<?php

namespace Demo\Plugins\Providers;

use Illuminate\Support\ServiceProvider;
use Demo\Plugins\Contracts\RepositoryInterface;
use Demo\Plugins\Laravel\LaravelFileRepository;

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
