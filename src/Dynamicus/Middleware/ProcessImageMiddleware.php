<?php

namespace Dynamicus\Middleware;

use Common\Entity\ImageDataObject;
use Dynamicus\Image\ImageCreatorInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProcessImageMiddleware
 * @package Dynamicus\Middleware
 */
class ProcessImageMiddleware implements MiddlewareInterface
{
    /**
     * @var ImageCreatorInterface
     */
    private $imageManager;

    /**
     * ProcessImageMiddleware constructor.
     * @param ImageCreatorInterface $imageManager
     */
    public function __construct(ImageCreatorInterface $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $this->imageManager->process(
            $request->getAttribute(ImageDataObject::class),
            $request->getParsedBody()
        );

        return $delegate->process($request);
    }

}
