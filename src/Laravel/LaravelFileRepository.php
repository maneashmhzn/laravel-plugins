<?php

namespace Maneash\Plugins\Laravel;

use Maneash\Plugins\FileRepository;

class LaravelFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createPlugin(...$args)
    {
        return new Plugin(...$args);
    }
}
