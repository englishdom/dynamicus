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
        $collection = $request->getAttribute(FilesystemInterface::class);

        $fileName = $hash . '.' . $do->getExtension();
        $path = $do->getShardingPath() . DIRECTORY_SEPARATOR . $fileName;
        $collection->rewind();
        $request = $request->withAttribute(self::FILE_EXISTS, $collection->current()->has($path));
        return $delegate->process($request);
    }

}
