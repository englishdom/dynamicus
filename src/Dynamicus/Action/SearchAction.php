<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\DataObject;
use Common\Exception\RuntimeException;
use Common\Middleware\ConstantMiddlewareInterface;
use Dynamicus\Image\Search\SearchAdapterInterface;
use Dynamicus\Transformer\SearchImageTransformer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;
use Zend\Log\LoggerInterface;

/**
 * Class SearchAction
 * @package Dynamicus\Action
 */
class SearchAction implements ActionInterface, ConstantMiddlewareInterface
{
    /**
     * @var SearchAdapterInterface
     */
    private $adapter;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SearchAction constructor.
     * @param SearchAdapterInterface $adapter
     * @param LoggerInterface        $logger
     */
    public function __construct(SearchAdapterInterface $adapter, LoggerInterface $logger)
    {
        $this->adapter = $adapter;
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $do = new DataObject();
        $searchText = urldecode($request->getAttribute('search_text'));

        try {
            $this->adapter->setConfigApiName($request->getAttribute(self::GOOGLE_API_NAME));
            $collection = $this->adapter->search($searchText);
            $do->setFiles($collection);
        } catch (\Exception $err) {
            $this->logger->err(
                'Dynamicus: '.$err->getMessage(),
                ['StackTrace' => $err->getTraceAsString()]
            );
            throw new RuntimeException('Error search in dynamicus! '.$err->getMessage());
        }

        $this->writeToLog($searchText);

        $item = new Item($do, new SearchImageTransformer(), 'search');

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * @TODO какую инфу нцжно писать в лог ?
     * @param $searchQuery
     */
    private function writeToLog($searchQuery)
    {
        $this->logger->info(
            'Dynamicus: Request to Image API for `'.$searchQuery.'`',
            ['StackTrace' => '']
        );
    }
}
