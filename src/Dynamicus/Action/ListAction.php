<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Container\Config;
use Common\Entity\ImageFile;
use Common\Entity\ImageDataObject;
use Common\Exception\RuntimeException;
use Dynamicus\Image\ImageCreatorTrait;
use Dynamicus\Image\Options;
use Dynamicus\Image\Transformer\Plugin\ParsingConfigArray;
use Dynamicus\Image\Transformer\Transformer;
use Dynamicus\Transformer\ImageTransformer;
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
    use ImageCreatorTrait;

    /**
     * @var Config
     */
    private $config;

    /**
     * ListAction constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

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
        $this->createImagesPath($do);

        $item = new Item($do, new ImageTransformer(), $this->getResourceName($do));

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * Будет работать только если имиджи локально
     * @param ImageDataObject $do
     */
    private function createImagesPath(ImageDataObject $do)
    {
        foreach ($this->createOptions($do) as $options) {
            $pathObject = new ImageFile();
            $pathObject->setUrl($do->getRelativeDirectoryUrl() . $this->makeFileName($do, $options));
            $do->attachImageFile($pathObject);
        }
    }

    /**
     * Парсинг конфига имиджей
     * @param ImageDataObject $do
     * @return \SplObjectStorage|Options[]
     */
    private function createOptions(ImageDataObject $do): \SplObjectStorage
    {
        $transformationParams = $this->config->get('images.'.$do->getEntityName());

        $transformer = new Transformer();
        $transformer->setPlugin(new ParsingConfigArray());
        return $transformer->transform($do, $transformationParams);
    }

    public function getResourceName(ImageDataObject $do): string
    {
        return 'list/'.$do->getEntityName();
    }
}
