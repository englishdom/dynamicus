<?php

namespace Audicus\Middleware;

use Common\Entity\DataObject;
use Common\Middleware\ConstantMiddlewareInterface;
use Common\Storage\RedisStorage;
use Common\Storage\RQLiteStorage;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AddEntityToStorageMiddleware
 * @package Audicus\Middleware
 */
class StorageEntityMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    /**
     * @var RedisStorage
     */
    private $redisStorage;

    /**
     * @var RQLiteStorage
     */
    private $RQLiteStorage;

    /**
     * StoreEntityInRedisMiddleware constructor.
     * @param RedisStorage  $redisStorage
     * @param RQLiteStorage $RQLiteStorage
     */
    public function __construct(RedisStorage $redisStorage, RQLiteStorage $RQLiteStorage)
    {
        $this->redisStorage = $redisStorage;
        $this->RQLiteStorage = $RQLiteStorage;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws \Common\Storage\RQLiteStorageException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $hash = $request->getAttribute(self::HASH);

        /* Save hash to storage */
        $data = $request->getAttribute(self::RAW_BODY);
        $this->redisStorage->writeHash($hash, $data);
        $this->RQLiteStorage->writeHash($hash, $data);

        $do = $request->getAttribute(DataObject::class);
        /* Link entity with hash */
        $this->redisStorage->linkHash($do, $hash);
        $this->RQLiteStorage->linkHash($do, $hash);

        return $delegate->process($request);
    }
}
