<?php

namespace Common\Middleware;

use Common\Container\ConfigInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\TextResponse;

class DomainMiddleware implements MiddlewareInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * DomainMiddleware constructor.
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        return new JsonResponse(['domain' => $this->config->get('hosts.default.0')]);
    }
}