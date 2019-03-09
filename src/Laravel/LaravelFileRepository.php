<?php

namespace Demo\Plugins\Laravel;

use Demo\Plugins\FileRepository;

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
