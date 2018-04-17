<?php

namespace Audicus\Factory;

use Audicus\Middleware\CheckHashMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Class CheckHashInRedisMiddlewareFactory
 * @package Audicus\Factory
 */
class CheckHashInRedisMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $redis = $container->get(\Redis::class);
        return new CheckHashMiddleware($redis);
    }
}
