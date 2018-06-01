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
        $this->addHash($do->getEntityName(), $do->getEntityId(), $hash);

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
     * @param $entityName
     * @param $entityId
     * @param $hash
     * @return bool
     */
    protected function addHash($entityName, $entityId, $hash):bool
    {
        $key = $this->generateKey($entityName, $entityId);
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
}
