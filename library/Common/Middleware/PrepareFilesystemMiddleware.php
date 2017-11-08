<?php

namespace Common\Middleware;

use Common\Container\ConfigInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Инициализация flysystem с локальным адаптером
 * @package Common\Middleware
 */
class PrepareFilesystemMiddleware implements MiddlewareInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * PrepareFilesystemMiddleware constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $request = $request->withAttribute(
            FilesystemInterface::class,
            $this->createLocalFilesystem()
        );

        return $delegate->process($request);
    }

    private function createLocalFilesystem(): FilesystemInterface
    {
        $adapter = new Local($this->readRootDirectory());
        return new Filesystem($adapter);
    }

    private function readRootDirectory(): string
    {
        return $this->config->get('images-path.absolute-path', null);
    }
}
