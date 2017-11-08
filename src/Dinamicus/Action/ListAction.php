<?php

namespace Dinamicus\Action;

use Common\Action\ActionInterface;
use Common\Container\ConfigInterface;
use Common\Entity\PathObject;
use Common\Entity\ImageDataObject;
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
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Чтение файлов из файловой системы. Потому как мы не храним расширение файлов в базе.
     * В базе нет информации о именах всех файлов для текущего id
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $path = $this->getAbsolutePathPrefix($do);

        foreach (glob($path . $do->getEntityId() . '*') as $fileName) {
            $pathInfo = pathinfo($fileName);

            $pathObject = new PathObject();
            $pathObject->setEntity($do->getEntityName());
            $pathObject->setPath($fileName);
            $pathObject->setUrl($this->getRelativeUrlPrefix($do) . $pathInfo['basename']);

            /* @var ImageDataObject $do */
            $do = $request->getAttribute(ImageDataObject::class);
            $do->attachImagePath($pathObject);
        }

        $item = new Item($do, new ImageTransformer(), $this->getResourceName($do));

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * Получение абсолютного пути к папке в файловой системе /var/www/images/word/000/000/000/001/
     * @param ImageDataObject $do
     * @return string
     */
    private function getAbsolutePathPrefix(ImageDataObject $do): string
    {
        return str_replace('//', '/', $this->config->get('images-path.absolute-path', '')
            . DIRECTORY_SEPARATOR . $do->getRelativePath()
            . DIRECTORY_SEPARATOR
        );
    }

    /**
     * Получение относительного урла к рисункам /images/word/000/000/000/001/
     * @param ImageDataObject $do
     * @return string
     */
    private function getRelativeUrlPrefix(ImageDataObject $do): string
    {
        return str_replace('//', '/', $this->config->get('images-path.relative-url', '')
            . DIRECTORY_SEPARATOR . $do->getRelativePath()
            . DIRECTORY_SEPARATOR
        );
    }

    public function getResourceName(ImageDataObject $do): string
    {
        return 'list/'.$do->getEntityName();
    }
}
