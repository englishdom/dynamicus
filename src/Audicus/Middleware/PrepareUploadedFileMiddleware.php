<?php

namespace Audicus\Middleware;

use Common\Exception\InvalidParameterException;
use Common\Middleware\ConstantMiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

/**
 * Class PrepareUploadedFileMiddleware
 * @package Audicus\Middleware
 */
class PrepareUploadedFileMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $files = $request->getUploadedFiles();
        if (!isset($files['audio'])) {
            throw new InvalidParameterException('Can not read uploaded audio file from response');
        }

        /* @var UploadedFile $audio */
        $audio = $files['audio'];
        $request = $request->withAttribute(
            self::AUDIO_CONTENT,
            $audio->getStream()->getContents()
        );

        return $delegate->process($request);
    }
}
