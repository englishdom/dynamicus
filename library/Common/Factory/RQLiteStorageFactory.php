<?php

namespace Common\Factory;

use Common\Storage\RQLiteStorage;
use Psr\Container\ContainerInterface;

/**
 * Class RQLiteStorageFactory
 * @package Common\Factory
 */
class RQLiteStorageFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new RQLiteStorage();
    }
}
