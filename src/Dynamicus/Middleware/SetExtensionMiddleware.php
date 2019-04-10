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

        if ($do instanceof \SplObjectStorage) {
            foreach ($do as $single) {
                $this->setExtension($single);
            }
        } else {
            $this->setExtension($do);
        }


        return $delegate->process($request);
    }

    protected function setExtension(DataObject $do)
    {
        switch($do->getNamespace()) {
            case 'svg':
                $do->setExtension(TYPE_SVG);
                break;
            case 'png':
                $do->setExtension(TYPE_PNG);
                break;
            default:
                $do->setExtension(TYPE_JPG);
                break;
        }
    }
}
