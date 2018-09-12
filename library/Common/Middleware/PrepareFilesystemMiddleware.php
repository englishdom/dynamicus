<?php

namespace Common\Middleware;

use Common\Container\Config;
use Common\Entity\DataObject;
use Common\Exception\RuntimeException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Инициализация flysystem с установленным адаптером
 * @package Common\Middleware
 */
class PrepareFilesystemMiddleware implements MiddlewareInterface
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * PrepareFilesystemMiddleware constructor.
     * @param ContainerInterface $container
     * @param Config             $config
     */
    public function __construct(ContainerInterface $container, Config $config)
    {
        $this->config = $config;
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);

        $configKey = 'adapters.'.$do->getEntityName();
        if ($this->config->get($configKey, null) === null) {
            $configKey = 'adapters.0';
        }

        /* Получение адаптера из контейнера */
        $adapters = $this->container->get($this->config->get($configKey));
        $collection = new \SplObjectStorage();

        if (is_array($adapters)) {
            foreach ($adapters as $adapter) {
                $collection->attach(new Filesystem($adapter));
            }
        } else {
            throw new RuntimeException('Invalid config for file adapters.');
        }

        $request = $request->withAttribute(
            FilesystemInterface::class,
            $collection
        );

        return $delegate->process($request);
    }
}
