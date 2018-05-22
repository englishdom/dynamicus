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
        if (strstr($request->getAttribute('entity_id'), ',')) {
            $do = new \SplObjectStorage();
            foreach (explode(',', $request->getAttribute('entity_id')) as $entityId) {
                $result = $this->setDataObject($request->getAttribute('entity'), $entityId);
                $do->attach($result);
            }
        } else {
            $do = $this->setDataObject(
                $request->getAttribute('entity'),
                $request->getAttribute('entity_id')
            );
        }

        $request = $request->withAttribute(DataObject::class, $do);
        return $delegate->process($request);
    }

    protected function setDataObject($entityName, $entityId): DataObject
    {
        $do = new DataObject();
        $do->setEntityId((int)$entityId);
        $entityName = (string)$entityName;
        /* Если приходит название с неймспейсом meta_info:og_image */
        if (stristr($entityName, ':')) {
            $entityParts = explode(':', $entityName);
            $do->setEntityName($entityParts[0]);
            $do->setNamespace($entityParts[1] ?? null);
        } else {
            $do->setEntityName($entityName);
        }
        return $do;
    }
}
