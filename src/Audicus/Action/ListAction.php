<?php

namespace Audicus\Action;

use Audicus\Transformer\AudioTransformer;
use Common\Action\ActionInterface;
use Common\Container\Config;
use Common\Entity\DataObject;
use Common\Entity\File;
use Common\Middleware\GenerateKeyTrait;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;
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
    use GenerateKeyTrait;

    protected $redis;
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
     * @param \Redis $redis
     * @param Config $config
     * @param AdapterInterface $fileSystemAdapter
     */
    public function __construct(\Redis $redis, Config $config, AdapterInterface $fileSystemAdapter)
    {
        $this->redis = $redis;
        $this->config = $config;
        $this->fileSystemAdapter = $fileSystemAdapter;
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
        $this->addFiles($do,$request->getAttribute(self::WITH_INFO) === self::WITH_INFO);
        $item = new Item($do, new AudioTransformer(), $this->getResourceName($do));

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * Чтение хешей из редиса
     *
     * @param DataObject $do
     * @param bool $withMetaInfo
     * @throws \Common\Exception\RuntimeException
     */
    protected function addFiles(DataObject $do, bool $withMetaInfo = false)
    {
        $do->setExtension(TYPE_MP3);
        $key = $this->generateKey($do->getEntityName(), $do->getEntityId());
        $values = $this->redis->lRange($key, 0, -1);

        foreach ($values as $value) {
            $hash = $this->cleanHash($value);
            $file = new File();
            if ($withMetaInfo) {
                $file->setMetaData($this->getFileInfo($this->createUrlInFileSystem($do, $hash)));
            }
            $file->setUrl($this->createUrl($do, $hash));
            $do->attachFile($file);
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
        $configKey = 'hosts.'.$entityName;
        if ($this->config->get($configKey, null) === null) {
            $configKey = 'hosts.0';
        }

        return $this->config->get($configKey);
    }

    /**
     * @param $string
     * @return string
     */
    protected function cleanHash($string): string
    {
        return substr($string, -32);
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
