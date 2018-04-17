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
        $host = $config->get('containers.redis.host');
        $port = $config->get('containers.redis.port');
        $password = $config->get('containers.redis.password');
        $redis = new \Redis();
        $redis->connect($host, $port);
        $redis->auth($password);
        return $redis;
    }
}
