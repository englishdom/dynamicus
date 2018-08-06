<?php

namespace Common\Factory;

use Common\Storage\RedisStorage;
use Psr\Container\ContainerInterface;

/**
 * @package Common\Factory
 */
class RedisStorageFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $redis = $container->get(\Redis::class);
        return new RedisStorage($redis);
    }
}
