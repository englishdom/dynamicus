<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\ImageDataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;

/**
 * Class PostAction
 * @package Dynamicus\Action
 */
class PostAction implements ActionInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $request = $request
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_204);

        return $delegate->process($request);
    }
}
