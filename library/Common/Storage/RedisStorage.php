<?php

namespace Common\Storage;

use Common\Entity\DataObject;
use Common\Middleware\ConstantMiddlewareInterface;

class RedisStorage implements StorageInterface
{
    /**
     * @var \Redis
     */
    private $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Read all hashes from entity
     * @param DataObject $do
     * @return array
     */
    public function readHashes(DataObject $do): array
    {
        $hashes = [];
        $key = $this->generateKey($do->getEntityName(), $do->getEntityId());
        $values = $this->redis->lRange($key, 0, -1);

        foreach ($values as $value) {
            $hashes[] = substr($value, -32);
        }
        return $hashes;
    }

    /**
     * Write a value to the storage if a key does not exist
     * @param $hash
     * @param $value
     * @return bool
     */
    public function writeHash($hash, $value): bool
    {
        $hashKey = $this->generateHashKey($hash);
        if (!$this->redis->exists($hashKey)) {
            return $this->redis->set($hashKey, $value);
        }
        return false;
    }

    /**
     * Link hash for entity.
     * Remove old hashes in entity.
     * @param DataObject $do
     * @param            $hash
     * @return bool
     */
    public function linkHash(DataObject $do, $hash): bool
    {
        /* Generate key for entity: dynamicus:{entity}:{id} */
        $key = $this->generateKey($do->getEntityName(), $do->getEntityId());
        /* Get all values from key */
        $values = $this->redis->lRange($key, 0, -1);
        /* Generate key for hash: dynamicus:hash:{key} */
        $hashKey = $this->generateHashKey($hash);
        $hashIsExist = false;
        /* Search hash in entity */
        foreach ($values as $value) {
            if ($value == $hashKey) {
                $hashIsExist = true;
                break;
            }
        }

        if ($hashIsExist === false) {
            $result = (bool)$this->redis->lPush($key, $hashKey);
            if ($result === true) {
                $hashes = $this->redis->lRange($key, 0, -1);
                foreach ($hashes as $hash) {
                    $parts = explode(':', $hash);
                    if (count($parts) == 3 && $parts[2] != $hash) {
                        /* Удаление хеша только из entity. Потому что файл может быть связан с другой entity */
                        $this->redis->lRemove($key, $hash, 1);
                    }
                }
            }
            return $result;
        }
        return false;
    }

    protected function generateHashKey($hash)
    {
        return $this->generateKey('hash', $hash);
    }

    protected function generateKey($entityName, $entityId, $namespace = null): string
    {
        if (!$namespace) {
            return sprintf('%s:%s:%s', ConstantMiddlewareInterface::DYNAMICUS_KEY, $entityName, $entityId);
        }

        return sprintf('%s:%s:%s:%s', ConstantMiddlewareInterface::DYNAMICUS_KEY, $entityName, $entityId, $namespace);
    }
}