<?php

namespace Common\Middleware;

use Common\Entity\ImageDataObject;
use Common\Exception\RuntimeException;
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
        if (!$this->validate($request->getAttributes())) {
            throw new RuntimeException('...');
        }

        $do = new ImageDataObject();
        $do->setEntityId($request->getAttribute('entity_id'));
        $do->setEntityName($request->getAttribute('entity'));

        $request = $request->withAttribute(get_class($do), $do);
        return $delegate->process($request);
    }

    private function validate(array $attributes)
    {
        /* @TODO проверить чтобы приходили поля entity_id / entity */
        return true;
    }
}
