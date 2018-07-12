<?php

namespace Dynamicus\Middleware;

use Common\Entity\DataObject;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class SetExtensionMiddleware
 * @package Dynamicus\Middleware
 */
class SetExtensionMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $do = $request->getAttribute(DataObject::class);

        switch($do->getNamespace()) {
            case 'svg':
                $do->setExtension(TYPE_SVG);
                break;
            default:
                $do->setExtension(TYPE_JPG);
                break;
        }

        return $delegate->process($request);
    }
}
