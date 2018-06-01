<?php

namespace Common\Helper\BodyParams;

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
                ->withAttribute('rawBody', $body['json']);
        }

        return $request;
    }
}
