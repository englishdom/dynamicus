<?php

namespace Common;

use Common\Container\SelectelFilesystemAdapter;
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
                \Redis::class => Factory\RedisFactory::class,
                Middleware\PrepareResponseMiddleware::class => Factory\PrepareResponseMiddlewareFactory::class,
                Middleware\ShardingMiddleware::class => Factory\ShardingMiddlewareFactory::class,
                Middleware\PrepareFilesystemMiddleware::class => Factory\PrepareFilesystemMiddlewareFactory::class,
                // для работы с локальной ФС
                AdapterInterface::class => Factory\FilesystemLocalFSAdapterFactory::class,
                // для работы с selectel
                /* временный интерфейс */
                SelectelFilesystemAdapter::class => Factory\FilesystemSelectelAdapterFactory::class
            ],
        ];
    }
}
