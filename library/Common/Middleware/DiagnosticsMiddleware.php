<?php

namespace Common\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;
use ZendDiagnostics\Result\FailureInterface;
use ZendDiagnostics\Runner\Runner;

class DiagnosticsMiddleware implements MiddlewareInterface
{
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

        $response = [];
        foreach ($this->collection as $checker) {
            $response[] = [
                'checker' => $this->collection->getInfo(),
                'response' => $results[$checker] instanceof FailureInterface ? 'Fail' : 'Ok',
                'message' => $results[$checker]->getMessage(),
            ];
        }

        return new Response\JsonResponse($response);
    }
}