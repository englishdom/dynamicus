<?php

namespace Audicus\Factory;

use Audicus\Middleware\StorageEntityMiddleware;
use Common\Storage\RedisStorage;
use Common\Storage\RQLiteStorage;
use Psr\Container\ContainerInterface;

/**
 * Class StorageEntityInRedisMiddlewareFactory
 * @package Audicus\Factory
 */
class StorageEntityMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $redisStorage = $container->get(RedisStorage::class);
        $RQLiteStorage = $container->get(RQLiteStorage::class);
        return new StorageEntityMiddleware($redisStorage, $RQLiteStorage);
    }
}
