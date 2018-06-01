<?php

namespace Common\Helper\BodyParams;

use Common\Middleware\ConstantMiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Helper\BodyParams\StrategyInterface;

/**
 * Class MultipartJsonStrategy
 * @package Common\Helper\BodyParams
 */
class MultipartJsonStrategy implements StrategyInterface
{
    public function match($contentType)
    {
        return (bool)strstr($contentType, 'multipart/form-data');
    }

    public function parse(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();

        if (isset($body['json'])) {
            $parsedBody = json_decode($body['json'], true);
            $request = $request
                ->withParsedBody($parsedBody)
                ->withAttribute(ConstantMiddlewareInterface::RAW_BODY, $body['json']);
        }

        return $request;
    }
}
