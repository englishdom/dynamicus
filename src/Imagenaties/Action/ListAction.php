<?php

namespace Imagenaties\Action;

use Common\Action\ActionInterface;
use Common\Container\ConfigInterface;
use Imagenaties\Entity\ImageDataObject;
use Imagenaties\Transformer\ImageTransformer;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;

/**
 * Class OfflineDictionary
 * @package Common\Adapter
 */
class ListAction implements ActionInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $DO = new ImageDataObject();
        $DO->setId(1);
        $DO->setEntityName('word');
        $DO->setImagesPath(['/dfgdfg/dfgdfg/dfgdfg.jpg']);

        $item = new Item($DO, new ImageTransformer(), $this->getResourceName());

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    public function getResourceName(): string
    {
        return 'list';
    }
}
