<?php

namespace Audicus;

/**
 * Class ConfigProvider
 * @package Audicus
 */
class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * Returns the container dependencies
     *
     * @return array
     */
    public function getDependencies()
    {
        return [
            'factories'  => [
                Middleware\CheckHashMiddleware::class => Factory\CheckHashInRedisMiddlewareFactory::class,
                Middleware\ShardingMiddleware::class => Factory\ShardingMiddlewareFactory::class,
                Middleware\AddEntityToStorageMiddleware::class => Factory\StoreEntityInRedisMiddlewareFactory::class,
                Middleware\GenerateAudioMiddleware::class => Factory\GenerateAudioMiddlewareFactory::class,
            ]
        ];
    }
}
