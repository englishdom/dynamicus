<?php

namespace Dynamicus\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class WriteImagesMiddleware
 * @package Dinamicus\Middleware
 */
class WriteImagesMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $do = $request->getAttribute(ImageDataObject::class);
        $filesystem = $request->getAttribute(FilesystemInterface::class);
        $this->writeImage($do, $filesystem, $stream);

    }

    private function setExtension(ImageDataObject $do, $queryData)
    {
        $extension = pathinfo($queryData['data']['links']['url'], PATHINFO_EXTENSION);
        $do->setImageExtension($extension);
    }

    private function writeImage(ImageDataObject $do, FilesystemInterface $filesystem, $stream)
    {
        $filePath = $do->getShardingPath() . DIRECTORY_SEPARATOR
            . $do->getEntityId() . '.' . $do->getImageExtension();
        $filesystem->write($filePath, $stream);
    }
}
