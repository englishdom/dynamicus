<?php

namespace Audicus\Middleware;

use Common\Entity\DataObject;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AddExtensionMiddleware
 * @package Audicus\Middleware
 */
class SetExtensionMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $do = $request->getAttribute(DataObject::class);

        if ($do instanceof \SplObjectStorage) {
            foreach ($do as $single) {
                $single->setExtension(TYPE_MP3);
            }
        } else {
            $do->setExtension(TYPE_MP3);
        }


        return $delegate->process($request);
    }
}
