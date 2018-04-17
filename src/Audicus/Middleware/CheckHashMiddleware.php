<?php

namespace Audicus\Middleware;

use Common\Middleware\ConstantMiddlewareInterface;
use Common\Middleware\GenerateKeyTrait;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CheckHashInRedisMiddleware
 * @package Audicus\Middleware
 */
class CheckHashMiddleware implements MiddlewareInterface, ConstantMiddlewareInterface
{
    use GenerateKeyTrait;

    /**
     * @var \Redis
     */
    private $redis;

    /**
     * CheckHashInRedisMiddleware constructor.
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
        $request = $request->withAttribute(self::HASH_IS_EXIST, $this->isHash($hash));

        return $delegate->process($request);
    }

    /**
     * Check STRING key in redis
     * @param $hash
     * @return bool
     */
    protected function isHash($hash): bool
    {
        return $this->redis->exists($this->generateHashKey($hash));
    }
}
