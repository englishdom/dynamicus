<?php

namespace Common\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Инициализация flysystem с установленным адаптером
 * @package Common\Middleware
 */
class PrepareFilesystemMiddleware implements MiddlewareInterface
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * PrepareFilesystemMiddleware constructor.
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $request = $request->withAttribute(
            FilesystemInterface::class,
            new Filesystem($this->adapter)
        );

        return $delegate->process($request);
    }
}
