<?php

namespace Dinamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\PathObject;
use Common\Entity\ImageDataObject;
use Common\Exception\RuntimeException;
use Dinamicus\Transformer\ImageTransformer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;

/**
 * Class OfflineDictionary
 * @package Common\Adapter
 */
class ListAction implements ActionInterface
{
    /**
     * Чтение файлов из файловой системы. Потому как мы не храним расширение файлов в базе.
     * В базе нет информации о именах всех файлов для текущего id
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws RuntimeException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $path = $do->getAbsoluteDirectoryPath();

        foreach (glob($path . $do->getEntityId() . '*') as $fileName) {
            $pathInfo = pathinfo($fileName);

            $pathObject = new PathObject();
            $pathObject->setEntity($do->getEntityName());
            $pathObject->setPath($fileName);
            $pathObject->setUrl($do->getRelativeDirectoryUrl() . $pathInfo['basename']);

            /* @var ImageDataObject $do */
            $do = $request->getAttribute(ImageDataObject::class);
            $do->attachImagePath($pathObject);
        }

        /* @TODO бросать исключение или возвращать пустой links ? */
        if (!$do->getImagesPath()) {
            throw new RuntimeException('Images not found!');
        }

        $item = new Item($do, new ImageTransformer(), $this->getResourceName($do));

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    public function getResourceName(ImageDataObject $do): string
    {
        return 'list/'.$do->getEntityName();
    }
}
