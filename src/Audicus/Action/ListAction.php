<?php

namespace Audicus\Action;

use Audicus\Transformer\AudioTransformer;
use Common\Action\ActionInterface;
use Common\Container\Config;
use Common\Entity\DataObject;
use Common\Entity\File;
use Common\Middleware\GenerateKeyTrait;
use Interop\Http\ServerMiddleware\DelegateInterface;
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
     * CheckHashInRedisMiddleware constructor.
     * @param \Redis $redis
     * @param Config $config
     */
    public function __construct(\Redis $redis, Config $config)
    {
        $this->redis = $redis;
        $this->config = $config;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws \Common\Exception\RuntimeException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        /* чтение файлов */
        $this->addFiles($do);
        $item = new Item($do, new AudioTransformer(), $this->getResourceName($do));

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * Чтение хешей из редиса
     * @param DataObject $do
     * @throws \Common\Exception\RuntimeException
     */
    protected function addFiles(DataObject $do)
    {
        $do->setExtension(TYPE_MP3);
        $key = $this->generateKey($do->getEntityName(), $do->getEntityId());
        $values = $this->redis->lRange($key, 0, -1);
        foreach ($values as $value) {
            $hash = $this->cleanHash($value);
            $file = new File();
            $file->setUrl(
                $this->getHost($do->getEntityName()) .
                $do->getRelativeDirectoryUrl() . $hash . '.' . $do->getExtension()
            );
            $do->attachFile($file);
        }
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

    protected function cleanHash($string): string
    {
        return substr($string, -32);
    }

    protected function getResourceName(DataObject $do): string
    {
        return '/audio/'.$do->getEntityName().'/'.$do->getEntityId();
    }
}
