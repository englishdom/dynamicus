<?php

namespace Common;

use League\Flysystem\AdapterInterface;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies()
    {
        return [
            'factories'  => [
                Container\ConfigInterface::class => Factory\ConfigFactory::class,
                Middleware\PrepareResponseMiddleware::class => Factory\PrepareResponseMiddlewareFactory::class,
                Middleware\ShardingMiddleware::class => Factory\ShardingMiddlewareFactory::class,
                Middleware\PrepareFilesystemMiddleware::class => Factory\PrepareFilesystemMiddlewareFactory::class,
                AdapterInterface::class => Factory\FilesystemAdapterFactory::class,
            ],
        ];
    }
}
