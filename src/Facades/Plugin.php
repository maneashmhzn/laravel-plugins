<?php

namespace Maneash\Plugins\Facades;

use Illuminate\Support\Facades\Facade;

class Plugin extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'plugins';
    }
}
