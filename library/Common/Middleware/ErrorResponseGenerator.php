<?php
namespace Common\Middleware;

use Common\Exception;
use Interop\Container\ContainerInterface;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Expressive\Router\RouteResult;
use Throwable;
use Zend\Http;
use Zend\Log;

final class ErrorResponseGenerator
{
    /**
     * @var array
     */
    private $responseCode;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var array
     */
    private $loggingExceptions;

    /**
     * ErrorResponseGenerator constructor.
     * @param ContainerInterface $container
     * @param array              $responseCodes
     * @param array              $loggingExceptions
     */
    public function __construct(ContainerInterface $container, array $responseCodes, array $loggingExceptions)
    {
        $this->responseCode = $responseCodes;
        $this->container = $container;
        $this->loggingExceptions = $loggingExceptions;
    }


    public function __invoke($err, ServerRequestInterface $request, ResponseInterface $response)
    {
        if (!$err instanceof \Exception && !$err instanceof \Throwable) {
            $hasRoute = $request->getAttribute(RouteResult::class) !== null;
            if (!$hasRoute) {
                $err = new Exception\NotFoundException('Not found');
            } else {
                $err = new \Exception('Internal server error');
            }
        }

        return $this->prepareJson($request, $response, $err);
    }

    /**
     * @param Throwable $exception
     * @param Throwable $exception
     */
    protected function writeToLog($exception)
    {
        $logger = $this->container->get(Log\LoggerInterface::class);
        $logger->err('Dinamicus: '.$exception->getMessage(), ['StackTrace' => $exception->getTraceAsString()]);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Throwable $exception
     * @param int $httpCode
     * @return array
     */
    protected function fillTemplate(ServerRequestInterface $request, Throwable $exception, int $httpCode): array
    {
        $identifier = 'unknown';
        if ($exception instanceof Exception\ExceptionInterface) {
            $identifier = $exception->getIdentifier();
        }

        $result = [
            'errors' => [
                'id' => (string)$identifier,
                'status' => (string)$httpCode,
                'title' => (string)$exception->getMessage(),
                'code' => (string)$exception->getCode(),
                'source' => [
                    'pointer' => $request->getUri()->getPath(),
                    'parameter' => $request->getUri()->getQuery()
                ]
            ]
        ];
        if ($exception instanceof Exception\ExceptionDetailInterface) {
            $result['errors']['detail'] = $exception->getDetail();
        }

        return $result;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param Throwable $exception
     * @return MessageInterface
     */
    protected function prepareJson(ServerRequestInterface $request, ResponseInterface $response, Throwable $exception)
    {
        $exceptionName = get_class($exception);
        if (array_key_exists($exceptionName, $this->responseCode)) {
            $httpCode = $this->responseCode[$exceptionName];
            $result = null;
        } else {
            $result = $this->fillTemplate($request, $exception, Http\Response::STATUS_CODE_503);
            $httpCode = 200;
            $result = json_encode($result);
        }

        /* Write to log exceptions */
        if (in_array($exceptionName, $this->loggingExceptions)) {
            $this->writeToLog($exception);
        }

        $newResponse = $response
            ->withHeader('Content-type', 'application/vnd.api+json')
            ->withStatus($httpCode);
        $newResponse->getBody()->write($result);

        return $newResponse;
    }
}
