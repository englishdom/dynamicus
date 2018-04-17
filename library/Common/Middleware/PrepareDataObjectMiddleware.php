<?php

namespace Common\Middleware;

use Common\Entity\DataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class PrepareDataObjectMiddleware
 * @package Common\Middleware
 */
class PrepareDataObjectMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $do = new DataObject();
        $do->setEntityId((int)$request->getAttribute('entity_id'));
        $entityName = (string)$request->getAttribute('entity');
        /* Если приходит название с неймспейсом meta_info:og_image */
        if (stristr($entityName, ':')) {
            $entityParts = explode(':', $entityName);
            $do->setEntityName($entityParts[0]);
            $do->setNamespace($entityParts[1] ?? null);
        } else {
            $do->setEntityName($entityName);
        }

        $request = $request->withAttribute(DataObject::class, $do);
        return $delegate->process($request);
    }
}
