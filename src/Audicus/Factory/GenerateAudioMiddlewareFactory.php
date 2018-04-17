<?php

namespace Audicus\Factory;
use Audicus\Middleware\GenerateAudioMiddleware;
use Common\Container\ConfigInterface;
use Psr\Container\ContainerInterface;

/**
 * Class GenerateAudioMiddlewareFactory
 * @package Audicus\Factory
 */
class GenerateAudioMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new GenerateAudioMiddleware($config);
    }
}
