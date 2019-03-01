<?php

namespace Dynamicus\Middleware;

use Common\Container\ConfigInterface;
use Common\Entity\DataObject;
use Common\Exception\WrongImageSizeException;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CheckImageSizesMiddleware
 * @package Dynamicus\Middleware
 */
class ConfigGoogleAPIMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    const ED_TRANSLATOR_KEY = 'ed-translator';
    const WEB_KEY = 'web';
    const ED_WORDS_KEY = 'ed-words';

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws WrongImageSizeException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $header = $request->getHeader('X-Client-Id');
        if (isset($header[0]) && in_array($header[0], [self::ED_TRANSLATOR_KEY, self::ED_WORDS_KEY, self::WEB_KEY])) {
            $request = $request->withAttribute(self::GOOGLE_API_NAME, $header[0]);
        }

        return $delegate->process($request);
    }
}
