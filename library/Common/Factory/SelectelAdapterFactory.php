<?php

namespace Common\Factory;

use ArgentCrusade\Flysystem\Selectel\SelectelAdapter;
use ArgentCrusade\Selectel\CloudStorage\Api\ApiClient;
use ArgentCrusade\Selectel\CloudStorage\CloudStorage;
use Common\Container\ConfigInterface;
use League\Flysystem\AdapterInterface;
use Psr\Container\ContainerInterface;

/**
 * Class FilesystemSelectelAdapterFactory
 * @package Common\Factory
 */
class SelectelAdapterFactory
{
    public function __invoke(ContainerInterface $container): AdapterInterface
    {
        $config = $container->get(ConfigInterface::class);
        $api = new ApiClient(
            $config->get('filesystem.selectel.username'),
            $config->get('filesystem.selectel.password')
        );
        $storage = new CloudStorage($api);
        $container = $storage->getContainer($config->get('filesystem.selectel.container'));

        $adapter = new SelectelAdapter($container);
        return $adapter;
    }
}
