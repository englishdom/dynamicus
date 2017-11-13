<?php

namespace Common\Container;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Log;

class LoggingErrorListener
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var Log\LoggerInterface
     */
    private $logger;

    /**
     * LoggingErrorListener constructor.
     * @param ConfigInterface     $config
     * @param Log\LoggerInterface $logger
     */
    public function __construct(ConfigInterface $config, Log\LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function __invoke(Throwable $throwable, ServerRequestInterface $request, ResponseInterface $response)
    {
        // пишем в грей лог только те ошибки, которые описанны в конфиге
        if (in_array(get_class($throwable), $this->config->get('error-handler.logging-exceptions'))) {
            $this->logger->err(
                'Dynamicus: '.$throwable->getMessage(),
                ['StackTrace' => $throwable->getTraceAsString()]
            );
        }
    }
}
