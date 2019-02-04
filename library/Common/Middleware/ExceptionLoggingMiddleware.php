<?php

namespace Common\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Log\LoggerInterface;

/**
 * Class ExceptionMiddleware
 * @package Common\Middleware
 */
class ExceptionLoggingMiddleware implements MiddlewareInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SearchAction constructor.
     * @param LoggerInterface        $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        try {
            return $delegate->process($request);
        } catch (\Exception $exception) {
            $this->logger->err(
                'Dynamicus exception: ' . $exception->getMessage(),
                [
                    'StackTrace' => $exception->getTraceAsString(),
                    'RequestId' => $request->getHeaderLine('RequestId')
                ]
            );
            throw $exception;
        }
    }
}