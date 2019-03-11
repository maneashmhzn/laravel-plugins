<?php

namespace Maneash\Plugins\Lumen;

use Illuminate\Support\Str;
use Maneash\Plugins\Plugin as BasePlugin;

class Plugin extends BasePlugin
{
    /**
     * {@inheritdoc}
     */
    public function getCachedServicesPath()
    {
        return Str::replaceLast('services.php', $this->getSnakeName() . '_plugin.php', $this->app->basePath('storage/app/') . 'services.php');
    }

    /**
     * {@inheritdoc}
     */
    public function registerProviders()
    {
        foreach ($this->get('providers', []) as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerAliases()
    {
    }
}
