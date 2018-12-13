<?php

namespace Dynamicus\Action;

use Common\Action\AbstractAction;
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
use League\Flysystem\AdapterInterface;

/**
 * Class OfflineDictionary
 * @package Common\Adapter
 */
class ListAction extends AbstractAction
{
    use ImageCreatorTrait;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var AdapterInterface
     */
    protected $fileSystemAdapter;

    /**
     * ListAction constructor.
     * @param Config $config
     * @param AdapterInterface $fileSystemAdapter
     */
    public function __construct(Config $config, AdapterInterface $fileSystemAdapter)
    {
        $this->config = $config;
        $this->fileSystemAdapter = $fileSystemAdapter;
    }

    /**
     * Чтение файлов из файловой системы. Потому как мы не храним расширение файлов в базе.
     * В базе нет информации о именах всех файлов для текущего id
     *
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     * @throws RuntimeException
     * @throws \League\Flysystem\FileNotFoundException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        if ($do instanceof \SplObjectStorage) {
            foreach ($do as $object) {
                $object->setExtension($object->getExtension());
                $this->createImagesPath($object, $request->getAttribute(self::WITH_INFO) === self::WITH_INFO);
            }
            $item = new Collection($do, new ImageTransformer(), $this->getResourceName($object));
        } else {
            /* Добавление расширения, так как мы не читаем файловую систему и не знаем реальное расширение */
            $do->setExtension($do->getExtension());
            $this->createImagesPath($do, $request->getAttribute(self::WITH_INFO) === self::WITH_INFO);

            $item = new Item($do, new ImageTransformer(), $this->getResourceName($do));
        }

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * @param DataObject $do
     * @param bool $withMetaInfo
     * @throws RuntimeException
     * @throws \League\Flysystem\FileNotFoundException
     */
    private function createImagesPath(DataObject $do, bool $withMetaInfo = false)
    {
        /* Добавление оригинального имиджа */
        $pathObject = new File();
        $pathObject->setUrl($this->createFilePath($do, null));
        if ($withMetaInfo) {
            $pathObject->setMetaData($this->getFileInfo($this->createUrlInFileSystem($do, null)));
        }
        $do->attachFile($pathObject);

        /* Добавление остальных имиджей */
        foreach ($this->createOptions($do) as $options) {
            $pathObject = new File();
            $pathObject->setUrl($this->createFilePath($do, $options));
            $do->attachFile($pathObject);
        }
    }

    /**
     * @param DataObject $do
     * @param $options
     * @return string
     */
    protected function createFilePath(DataObject $do, $options): string
    {
        return $this->getHost($do->getEntityName()) . $do->getRelativeDirectoryUrl() . $this->makeFileName($do, $options);
    }

    /**
     * @param DataObject $do
     * @param $options
     * @return string
     */
    protected function createUrlInFileSystem(DataObject $do, $options): string
    {
        return $do->getShardingPath() . DIRECTORY_SEPARATOR . $this->makeFileName($do, $options);
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

    /**
     * @param DataObject $do
     * @return string
     */
    public function getResourceName(DataObject $do): string
    {
        return 'list/'.$do->getEntityName();
    }
}
