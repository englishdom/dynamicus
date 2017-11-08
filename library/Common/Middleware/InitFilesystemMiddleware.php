<?php

namespace Common\Middleware;

use Common\Entity\ImageDataObject;
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
class InitFilesystemMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $do = $request->getAttribute(ImageDataObject::class);

        $request = $request->withAttribute(
            FilesystemInterface::class,
            $this->createLocalFilesystem($do->getAbsoluteDirectoryPath())
        );

        return $delegate->process($request);
    }

    private function createLocalFilesystem($path): FilesystemInterface
    {
        $adapter = new Local($path);
        return new Filesystem($adapter);
    }
}
