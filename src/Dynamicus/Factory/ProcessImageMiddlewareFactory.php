<?php

namespace Dynamicus\Factory;

use Dynamicus\Image\ImageCreatorInterface;
use Dynamicus\Middleware\ProcessImageMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Class ProcessImageMiddlewareFactory
 * @package Dynamicus\Factory
 */
class ProcessImageMiddlewareFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $manager = $container->get(ImageCreatorInterface::class);
        return new ProcessImageMiddleware($manager);
    }
}
