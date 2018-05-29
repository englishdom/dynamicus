<?php

namespace Common\Middleware;

use Webimpress\HttpMiddlewareCompatibility\HandlerInterface as DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Helper\BodyParams\BodyParamsMiddleware;

/**
 * Class CustomBodyParamsMiddleware
 * @package Common\Middleware
 */
class JsonBodyParamsMiddleware extends BodyParamsMiddleware
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $content = $request->getParsedBody();
        if (isset($content['json'])) {
            $content = json_decode($content['json'], true);
            $request = $request->withParsedBody($content);
        }
        return parent::process($request, $delegate);
    }
}
