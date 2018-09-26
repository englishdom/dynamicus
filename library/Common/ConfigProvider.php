<?php

namespace Common;

use Common\Container\SelectelAdapterInterface;
use Common\Storage\RedisStorage;
use Common\Storage\RQLiteStorage;
use Common\Storage\StorageInterface;
use League\Flysystem\AdapterInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

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
                // для работы с локальной ФС
                AdapterInterface::class => Factory\LocalFSAdapterFactory::class,
                // для работы с selectel
                SelectelAdapterInterface::class => Factory\SelectelAdapterFactory::class,
                BodyParamsMiddleware::class => Factory\BodyParamsMiddlewareFactory::class,
                // write storages
                \Redis::class => Factory\RedisFactory::class,
                RedisStorage::class => Factory\RedisStorageFactory::class,
                RQLiteStorage::class => Factory\RQLiteStorageFactory::class,
                // a read storage
                StorageInterface::class => Factory\RQLiteStorageFactory::class,
                // Diagnostics
                Middleware\DiagnosticsMiddleware::class => Factory\DiagnosticsMiddlewareFactory::class,
            ],
        ];
    }
}
