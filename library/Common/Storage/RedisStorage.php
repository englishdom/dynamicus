<?php

namespace Common\Storage;

use Common\Middleware\GenerateKeyTrait;

class RedisStorage implements StorageInterface
{
    use GenerateKeyTrait;

    /**
     * @var \Redis
     */
    private $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Check key in the storage
     * @param $hashKey
     * @return bool
     */
    public function exist($hashKey): bool
    {
        return $this->redis->exists($hashKey);
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
        if (!$this->exist($hashKey)) {
            return $this->redis->set($hashKey, $value);
        }
        return false;
    }

    /**
     * Link hash for entity.
     * Remove old hashes in entity.
     *
     * @param $entityName
     * @param $entityId
     * @param $hash
     * @return bool
     */
    public function linkHash($entityName, $entityId, $hash)
    {
        /* Generate key for entity: dynamicus:{entity}:{id} */
        $key = $this->generateKey($entityName, $entityId);
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
}