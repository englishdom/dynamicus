<?php

namespace Common\Middleware;

use Common\Entity\DataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class SetFilenameMiddleware
 * @package Common\Middleware
 */
class SetFilenameMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $queryData = $request->getParsedBody();

        if (isset($queryData['data']['filename'])) {
            $do = $request->getAttribute(DataObject::class);
            $do->setFileName($queryData['data']['filename']);
        }

        return $delegate->process($request);
    }
}
