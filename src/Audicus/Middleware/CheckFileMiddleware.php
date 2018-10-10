<?php

namespace Audicus\Middleware;

use Common\Entity\DataObject;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use League\Flysystem\FilesystemInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CheckFileMiddleware
 * @package Audicus\Middleware
 */
class CheckFileMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $hash = $request->getAttribute(self::HASH);
        $do = $request->getAttribute(DataObject::class);
        $fileSystem = $request->getAttribute(FilesystemInterface::class);

        $fileName = $hash . '.' . $do->getExtension();
        $path = $do->getShardingPath() . DIRECTORY_SEPARATOR . $fileName;
        if ($fileSystem instanceof \SplObjectStorage) {
            $fileSystem->rewind();
            $request = $request->withAttribute(self::FILE_EXISTS, $fileSystem->current()->has($path));
        } else {
            $request = $request->withAttribute(self::FILE_EXISTS, $fileSystem->has($path));
        }
        return $delegate->process($request);
    }

}
