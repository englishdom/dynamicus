<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Container\Config;
use Common\Entity\File;
use Common\Entity\DataObject;
use Common\Exception\RuntimeException;
use Dynamicus\Image\ImageCreatorTrait;
use Dynamicus\Image\Options;
use Dynamicus\Image\Transformer\Plugin\ParsingConfigArray;
use Dynamicus\Image\Transformer\Transformer;
use Dynamicus\Transformer\ImageTransformer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Collection;
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
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        if ($do instanceof \SplObjectStorage) {
            foreach ($do as $object) {
                $object->setExtension($object->getExtension());
                $this->createImagesPath($object);
            }
            $item = new Collection($do, new ImageTransformer(), $this->getResourceName($object));
        } else {
            /* Добавление расширения, так как мы не читаем файловую систему и не знаем реальное расширение */
            $do->setExtension($do->getExtension());
            $this->createImagesPath($do);

            $item = new Item($do, new ImageTransformer(), $this->getResourceName($do));
        }

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * @param DataObject $do
     * @throws RuntimeException
     */
    private function createImagesPath(DataObject $do)
    {
        /* Добавление оригинального имиджа */
        $pathObject = new File();
        $pathObject->setUrl(
            $this->getHost($do->getEntityName()) .
            $do->getRelativeDirectoryUrl() .
            $this->makeFileName($do, null)
        );
        $do->attachFile($pathObject);

        /* Добавление остальных имиджей */
        foreach ($this->createOptions($do) as $options) {
            $pathObject = new File();
            $pathObject->setUrl(
                $this->getHost($do->getEntityName()) .
                $do->getRelativeDirectoryUrl() .
                $this->makeFileName($do, $options)
            );
            $do->attachFile($pathObject);
        }
    }

    /**
     * Получение хоста из конфига
     * @param string $entityName
     * @return string
     */
    protected function getHost(string $entityName): string
    {
        $configKey = 'hosts.cdn.'.$entityName;
        if ($this->config->get($configKey, null) === null) {
            $configKey = 'hosts.cdn.0';
        }

        return $this->config->get($configKey);
    }

    /**
     * Парсинг конфига имиджей
     * @param DataObject $do
     * @return \SplObjectStorage|Options[]
     */
    private function createOptions(DataObject $do): \SplObjectStorage
    {
        $transformationParams = $this->config->get('images.'.$do->getEntityName());

        $transformer = new Transformer();
        $transformer->setPlugin(new ParsingConfigArray());
        return $transformer->transform($do, $transformationParams);
    }

    public function getResourceName(DataObject $do): string
    {
        return 'list/'.$do->getEntityName();
    }
}
