<?php

namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Common\Entity\ImageDataObject;
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
class SearchAction implements ActionInterface
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
        $do = new ImageDataObject();
        $searchText = urldecode($request->getAttribute('search_text'));

        $collection = $this->adapter->search($searchText);
        $do->setImageFiles($collection);

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
        $this->logger->err(
            'Dynamicus: Request to Image API for `'.$searchQuery.'`',
            ['StackTrace' => '']
        );
    }
}
