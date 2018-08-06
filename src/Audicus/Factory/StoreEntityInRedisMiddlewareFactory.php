<?php

namespace Audicus\Factory;

use Audicus\Middleware\AddEntityToStorageMiddleware;
use Common\Storage\RedisStorage;
use Common\Storage\RQLiteStorage;
use Psr\Container\ContainerInterface;

/**
 * Class StoreEntityInRedisMiddlewareFactory
 * @package Audicus\Factory
 */
class StoreEntityInRedisMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $redisStorage = $container->get(RedisStorage::class);
        $RQLiteStorage = $container->get(RQLiteStorage::class);
        return new AddEntityToStorageMiddleware($redisStorage, $RQLiteStorage);
    }
}
