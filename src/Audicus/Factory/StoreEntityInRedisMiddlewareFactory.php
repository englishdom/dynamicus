<?php

namespace Audicus\Factory;

use Audicus\Middleware\AddEntityToStorageMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Class StoreEntityInRedisMiddlewareFactory
 * @package Audicus\Factory
 */
class StoreEntityInRedisMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $redis = $container->get(\Redis::class);
        return new AddEntityToStorageMiddleware($redis);
    }
}
