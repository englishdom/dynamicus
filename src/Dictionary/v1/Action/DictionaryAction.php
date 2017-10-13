<?php

namespace Dictionary\Action;

use Common\Action\ActionInterface;
use Dictionary\Adapter\AdapterManager;
use Dictionary\Transformer\DictionaryTransformer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;

/**
 * Class OfflineDictionary
 * @package Common\Adapter
 */
class DictionaryAction implements ActionInterface
{
    /**
     * @var AdapterManager
     */
    private $manager;

    /**
     * OfflineDictionaryAction constructor.
     * @param AdapterManager $manager
     */
    public function __construct(AdapterManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $adapter = $this->manager->getAdapter((string)$request->getAttribute('adapter'));
        $dictionary = $adapter(urldecode((string)$request->getAttribute('text')));

        $item = new Item($dictionary, new DictionaryTransformer(), $this->getResourceName());

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    public function getResourceName(): string
    {
        return 'stardict';
    }
}
