<?php

namespace Dynamicus\Factory;

use Dynamicus\Action\SearchAction;
use Dynamicus\Image\Search\SearchAdapterInterface;
use Psr\Container\ContainerInterface;
use Zend\Log\LoggerInterface;

/**
 * Class SearchActionFactory
 * @package Dynamicus\Factory
 */
class SearchActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new SearchAction(
            $container->get(SearchAdapterInterface::class),
            $container->get(LoggerInterface::class)
        );
    }
}
