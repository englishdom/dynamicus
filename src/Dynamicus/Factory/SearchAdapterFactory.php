<?php

namespace Dynamicus\Factory;

use Common\Container\ConfigInterface;
use Dynamicus\Image\Search\GoogleSearchAdapter;
use Psr\Container\ContainerInterface;

/**
 * Class SearchAdapterGoogleFactory
 * @package Dynamicus\Factory
 */
class SearchAdapterFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get(ConfigInterface::class);
        return new GoogleSearchAdapter($config);
    }
}
