<?php

namespace Common\Middleware;

use Common\Entity\ImageDataObject;
use Common\Exception\BadRequestException;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;

/**
 * Class PrepareDataObjectMiddleware
 * @package Common\Middleware
 */
class PrepareDataObjectMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @TODO сделать валидатор параметров. */
//        if (!$this->validate($request->getAttribute(RouteResult::class))) {
//            throw new BadRequestException('entity_id or entity does not exist in url');
//        }

        $do = new ImageDataObject();
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

        $request = $request->withAttribute(get_class($do), $do);
        return $delegate->process($request);
    }

    /**
     * Простой валидатор
     * @param RouteResult $routeResult
     * @return bool
     */
    private function validate(RouteResult $routeResult)
    {
        $attributes = $routeResult->getMatchedParams();
        return (!isset($attributes['entity_id']) || !isset($attributes['entity'])
            || empty($attributes['entity_id']) || empty($attributes['entity']));
    }
}
