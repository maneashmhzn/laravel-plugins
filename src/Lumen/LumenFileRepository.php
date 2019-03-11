<?php

namespace Maneash\Plugins\Lumen;

use Maneash\Plugins\FileRepository;

class LumenFileRepository extends FileRepository
{
    /**
     * {@inheritdoc}
     */
    protected function createPlugin(...$args)
    {
        return new Plugin(...$args);
    }
}
