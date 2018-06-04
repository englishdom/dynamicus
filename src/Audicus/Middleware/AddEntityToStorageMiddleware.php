<?php

namespace Audicus\Middleware;

use Common\Entity\DataObject;
use Common\Middleware\ConstantMiddlewareInterface;
use Common\Middleware\GenerateKeyTrait;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AddEntityToStorageMiddleware
 * @package Audicus\Middleware
 */
class AddEntityToStorageMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    use GenerateKeyTrait;

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * StoreEntityInRedisMiddleware constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $hash = $request->getAttribute(self::HASH);

        /* Если хеша нет в редисе, добавить */
        if ($request->getAttribute(self::HASH_IS_EXIST) !== true) {
            $data = $request->getAttribute(self::RAW_BODY);
            $this->writeHash($hash, $data);
        }

        $do = $request->getAttribute(DataObject::class);
        /* Добавить хеш, если хеша нет в ентити */
        $result = $this->addHash($do, $hash);
        
        if ($result === true) {
            /* Удалние старых файлов из entity */
            $this->removeOldFiles($do, $hash);
        }

        return $delegate->process($request);
    }

    /**
     * Set STRING key - value in redis
     * @param $hash
     * @param $value
     * @return bool
     */
    protected function writeHash($hash, $value): bool
    {
        $key = $this->generateHashKey($hash);
        return $this->redis->set($key, $value);
    }

    /**
     * @param DataObject $do
     * @param            $hash
     * @return bool
     */
    protected function addHash(DataObject $do, $hash):bool
    {
        $key = $this->generateKey($do->getEntityName(), $do->getEntityId());
        $values = $this->redis->lRange($key, 0, -1);
        $hashKey = $this->generateHashKey($hash);
        $hashIsExist = false;
        foreach ($values as $value) {
            if ($value == $hashKey) {
                $hashIsExist = true;
                break;
            }
        }

        if ($hashIsExist === false) {
            return (bool)$this->redis->lPush($key, $hashKey);
        }
        return false;
    }

    /**
     * @param                     $excludeHash
     * @param DataObject          $do
     * @TODO нужно добавить проверку по неймспейсу
     */
    protected function removeOldFiles(DataObject $do, $excludeHash)
    {
        $key = $this->generateKey($do->getEntityName(), $do->getEntityId());
        $hashes = $this->redis->lRange($key, 0, -1);
        foreach ($hashes as $hash) {
            $parts = explode(':', $hash);
            if (count($parts) == 3 && $parts[2] != $excludeHash) {
                /* Удаление хеша только из entity. Потому что файл может быть связан с другой entity */
                $this->redis->lRemove($key, $hash, 1);
            }
        }
    }
}
