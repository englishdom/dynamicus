<?php

namespace Audicus\Action;

use Common\Middleware\ConstantMiddlewareInterface;
use Audicus\Transformer\AudioTransformer;
use Common\Action\ActionInterface;
use Common\Entity\DataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;
use League\Fractal\Resource\Item;

/**
 * Class PostAction
 * @package Audicus\Action
 */
class PostAction implements ActionInterface
{
    public function getResourceName(DataObject $do): string
    {
        return '/audio/'.$do->getEntityName().'/'.$do->getEntityId();
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);

        $item = new Item($do, new AudioTransformer(), $this->getResourceName($do));

        $fileExists = $request->getAttribute(ConstantMiddlewareInterface::FILE_EXISTS);
        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(
                self::HTTP_CODE,
                $fileExists === true ? Response::STATUS_CODE_200 : Response::STATUS_CODE_201
            );

        return $delegate->process($request);
    }
}
