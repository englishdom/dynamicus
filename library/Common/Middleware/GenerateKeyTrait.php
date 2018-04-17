<?php

namespace Common\Middleware;

/**
 * Class GenerateKey
 * @package Audicus\Middleware
 */
trait GenerateKeyTrait
{
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
