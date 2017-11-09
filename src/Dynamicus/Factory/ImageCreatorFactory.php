<?php

namespace Dynamicus\Factory;

use Common\Container\ConfigInterface;
use Dynamicus\Image\ImageCreator;
use Psr\Container\ContainerInterface;

/**
 * Class ImageProcessFactory
 * @package Dinamicus\Factory
 */
class ImageCreatorFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new ImageCreator($config);
    }
}
