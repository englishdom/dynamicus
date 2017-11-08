<?php

namespace Dinamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\PathObject;
use Common\Entity\ImageDataObject;
use Common\Exception\RuntimeException;
use Dinamicus\Transformer\ImageTransformer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Plugin\ListFiles;
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

        $filesystem = $request->getAttribute(FilesystemInterface::class);
        $filesystem->addPlugin(new ListFiles());

        /**
         * $file = array (
         *   'type' => 'file',
         *   'path' => '34.jpg',
         *   'timestamp' => 1510070576,
         *   'size' => 0,
         *   'dirname' => '',
         *   'basename' => '34.jpg',
         *   'extension' => 'jpg',
         *   'filename' => '34',
         * )
         */
        foreach ($filesystem->listFiles($do->getShardingPath()) as $file) {
            $pathObject = new PathObject();
            $pathObject->setEntity($do->getEntityName());
            $pathObject->setUrl($do->getRelativeDirectoryUrl() . $file['basename']);

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
