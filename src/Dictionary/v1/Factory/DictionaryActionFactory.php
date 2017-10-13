<?php

namespace Dictionary\Factory;

use Dictionary\Action\DictionaryAction;
use Dictionary\Adapter\AdapterManager;
use Psr\Container\ContainerInterface;

class DictionaryActionFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $manager = $container->get(AdapterManager::class);
        return new DictionaryAction($manager);
    }
}
