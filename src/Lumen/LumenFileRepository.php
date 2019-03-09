<?php

namespace Demo\Plugins\Lumen;

use Demo\Plugins\FileRepository;

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
