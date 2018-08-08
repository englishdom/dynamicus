<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
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
        $config = $container->get(ConfigInterface::class);
        $host = $config->get('storage.rqlite.host');
        $port = $config->get('storage.rqlite.port');
        $username = $config->get('storage.rqlite.username');
        $password = $config->get('storage.rqlite.password');
        return new RQLiteStorage($host, $port, $username, $password);
    }
}
