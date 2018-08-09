<?php

namespace Audicus\Action;

use Common\Container\Config;
use Common\Middleware\ConstantMiddlewareInterface;
use Audicus\Transformer\AudioTransformer;
use Common\Action\ActionInterface;
use Common\Entity\DataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;
use League\Fractal\Resource\Item;

/**
 * Class PostAction
 * @package Audicus\Action
 */
class PostAction implements ActionInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * ListAction constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getResourceName(DataObject $do): string
    {
        return '/audio/'.$do->getEntityName().'/'.$do->getEntityId();
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        $this->createAudioPath($do);
        $item = new Item($do, new AudioTransformer(), $this->getResourceName($do));

        $fileExists = $request->getAttribute(ConstantMiddlewareInterface::FILE_EXISTS);
        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(
                self::HTTP_CODE,
                $fileExists === true ? Response::STATUS_CODE_200 : Response::STATUS_CODE_201
            );

        return $delegate->process($request);
    }

    /**
     * @param DataObject $do
     */
    protected function createAudioPath(DataObject $do)
    {
        foreach ($do->getFiles() as $file) {
            $file->setUrl(
                $this->getHost($do->getEntityName()) .
                $file->getUrl()
            );
        }
    }

    /**
     * Получение хоста из конфига
     * @param string $entityName
     * @return string
     */
    protected function getHost(string $entityName): string
    {
        $configKey = 'hosts.default.'.$entityName;
        if ($this->config->get($configKey, null) === null) {
            $configKey = 'hosts.default.0';
        }

        return $this->config->get($configKey);
    }
}
