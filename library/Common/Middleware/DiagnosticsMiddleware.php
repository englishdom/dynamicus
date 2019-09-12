<?php

namespace Common\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\TextResponse;
use ZendDiagnostics\Result\FailureInterface;
use ZendDiagnostics\Runner\Runner;

class DiagnosticsMiddleware implements MiddlewareInterface
{
    protected CONST PROMETHEUS_NAME = 'api_http_dynamicus_%s{response="%s", message="%s", method="GET"}';
    /**
     * @var \SplObjectStorage
     */
    private $collection;

    public function __construct(\SplObjectStorage $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* Create Runner instance */
        $runner = new Runner();

        foreach ($this->collection as $checker) {
            $runner->addCheck($checker);
        }

        // Run all checks
        $results = $runner->run();

        $textString = '';
        foreach ($this->collection as $checker) {
            $textString .= sprintf(
                self::PROMETHEUS_NAME,
                $this->collection->getInfo(),
                $results[$checker] instanceof FailureInterface ? 'Fail' : 'Ok',
                $results[$checker]->getMessage()
            ) . "\n";
        }

        return new TextResponse($textString);
    }
}