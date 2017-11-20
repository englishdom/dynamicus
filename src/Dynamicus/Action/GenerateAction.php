<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\ImageDataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class GenerateAction
 * @package Dynamicus\Action
 */
class GenerateAction implements ActionInterface
{
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var Item $item */
        $item = $request->getAttribute(self::RESPONSE);
        /* @var ImageDataObject $do */
        $do = $item->getData();
        $do->getImageFiles()->rewind();

        $file = $do->getImageFiles()->current();

        $data = [
            'data' => [
                'links' => [
                    'url' => $file->getUrl()
                ]
            ]
        ];

        $request = $request->withParsedBody($data);

        return $delegate->process($request);
    }

}
