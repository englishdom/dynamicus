<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\ImageDataObject;
use Dynamicus\Transformer\ImageTransformer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;
use League\Fractal\Resource\Item;

/**
 * Class PostAction
 * @package Dynamicus\Action
 */
class PostAction implements ActionInterface
{
    public function getResourceName(ImageDataObject $do): string
    {
        return $do->getEntityName().'/'.$do->getEntityId();
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $item = new Item($do, new ImageTransformer(), $this->getResourceName($do));

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_201);

        return $delegate->process($request);
    }
}
