<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Class RedisFactory
 * @package Common\Factory
 */
class RedisFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        $host = $config->get('storage.redis.host');
        $port = $config->get('storage.redis.port');
        $password = $config->get('storage.redis.password');
        $redis = new \Redis();
        $redis->connect($host, $port);
        $redis->auth($password);
        return $redis;
    }
}
