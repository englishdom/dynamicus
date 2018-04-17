<?php

namespace Audicus\Middleware;

use Audicus\Entity\AudioDataObject;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class GenerateHashMiddleware
 * @package Audicus\Middleware
 */
class GenerateHashMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $content = $request->getBody()->getContents();
        $hash = md5($content);
        $request = $request->withAttribute(self::HASH, $hash);

        return $delegate->process($request);
    }
}
