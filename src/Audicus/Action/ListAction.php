<?php

namespace Audicus\Action;

use Audicus\Transformer\AudioTransformer;
use Common\Action\ActionInterface;
use Common\Container\Config;
use Common\Entity\DataObject;
use Common\Entity\File;
use Common\Exception\RuntimeException;
use Common\Storage\RedisStorage;
use Common\Storage\RQLiteStorage;
use Common\Storage\StorageInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;

/**
 * Class ListAction
 * @package Audicus\Action
 */
class ListAction implements ActionInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var AdapterInterface
     */
    protected $fileSystemAdapter;

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * ListAction constructor.
     * @param StorageInterface $storage
     * @param Config           $config
     * @param AdapterInterface $fileSystemAdapter
     */
    public function __construct(StorageInterface $storage, Config $config, AdapterInterface $fileSystemAdapter)
    {
        $this->config = $config;
        $this->fileSystemAdapter = $fileSystemAdapter;
        $this->storage = $storage;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     * @throws \Common\Exception\RuntimeException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        /* чтение файлов */
        if ($do instanceof \SplObjectStorage) {
            /* если хранилище RQLite */
            if ($this->storage instanceof RQLiteStorage) {
                $this->addFiles($do, $request->getAttribute(self::WITH_INFO) === self::WITH_INFO);
                $do->rewind();
                $item = new Collection($do, new AudioTransformer(), $this->getResourceName($do->current()));
            } else {
                foreach ($do as $object) {
                    $this->addFiles($object, $request->getAttribute(self::WITH_INFO) === self::WITH_INFO);
                }
                $item = new Collection($do, new AudioTransformer(), $this->getResourceName($object));
            }
        } else {
            /* Добавление расширения, так как мы не читаем файловую систему и не знаем реальное расширение */
            $this->addFiles($do,$request->getAttribute(self::WITH_INFO) === self::WITH_INFO);
            $item = new Item($do, new AudioTransformer(), $this->getResourceName($do));
        }

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * Чтение хешей из хранилища
     *
     * @param DataObject $do
     * @param bool $withMetaInfo
     * @throws \Common\Exception\RuntimeException
     */
    protected function addFiles($do, bool $withMetaInfo = false)
    {
        if ($do instanceof DataObject) {
            $do->setExtension(TYPE_MP3);
            $hashes = $this->storage->readHashes($do);

            foreach ($hashes as $hash) {
                $file = new File();
                if ($withMetaInfo) {
                    $file->setMetaData($this->getFileInfo($this->createUrlInFileSystem($do, $hash)));
                }
                $file->setUrl($this->createUrl($do, $hash));
                $do->attachFile($file);
            }
        } elseif ($do instanceof \SplObjectStorage) {
            /* это код работает только для RQLite хранилища */
            $hashes = $this->storage->readCollection($do);
            foreach ($do as $object) {
                if (!$object instanceof DataObject) {
                    throw new RuntimeException('Collection does not have DataObject');
                }

                $file = new File();
                $object->setExtension(TYPE_MP3);
                foreach ($hashes as $id => $hash) {
                    if ($id == $object->getEntityId()) {
                        $file->setUrl($this->createUrl($object, $hash));
                        $object->attachFile($file);
                    }
                }
            }
        }
    }

    /**
     * @param string $url
     * @return array|null
     */
    protected function getFileInfo(string $url): ?array
    {
        $result = null;
        $fileSystem = new Filesystem($this->fileSystemAdapter);

        if ($fileSystem->has($url)) {
            $result = $fileSystem->getMetadata($url);
        }

        return $result;
    }

    /**
     * @param DataObject $do
     * @param $hash
     * @return string
     */
    protected function createUrlInFileSystem(DataObject $do, $hash): string
    {
        return $do->getShardingPath() . DIRECTORY_SEPARATOR . $hash . '.' . $do->getExtension();
    }

    /**
     * @param DataObject $do
     * @param $hash
     * @return string
     */
    protected function createUrl(DataObject $do, $hash): string
    {
        return $this->getHost($do->getEntityName()) . $do->getRelativeDirectoryUrl() . $hash . '.' . $do->getExtension();
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
     * @param DataObject $do
     * @return string
     */
    protected function getResourceName(DataObject $do): string
    {
        return '/audio/'.$do->getEntityName().'/'.$do->getEntityId();
    }
}
