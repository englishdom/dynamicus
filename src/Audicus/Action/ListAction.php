<?php

namespace Audicus\Action;

use Audicus\Transformer\AudioTransformer;
use Common\Action\ActionInterface;
use Common\Entity\DataObject;
use Common\Entity\File;
use Common\Middleware\GenerateKeyTrait;
use Interop\Http\ServerMiddleware\DelegateInterface;
use League\Fractal\Resource\Item;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Http\Response;

/**
 * Class ListAction
 * @package Audicus\Action
 */
class ListAction implements ActionInterface
{
    use GenerateKeyTrait;

    protected $redis;

    /**
     * CheckHashInRedisMiddleware constructor.
     * @param \Redis $redis
     */
    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param ServerRequestInterface $request
     * @param DelegateInterface      $delegate
     * @return ResponseInterface
     * @throws \Common\Exception\RuntimeException
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        /* чтение файлов */
        $this->addFiles($do);
        $item = new Item($do, new AudioTransformer(), $this->getResourceName($do));

        $request = $request
            ->withAttribute(self::RESPONSE, $item)
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_200);

        return $delegate->process($request);
    }

    /**
     * Чтение хешей из редиса
     * @param DataObject $do
     * @throws \Common\Exception\RuntimeException
     */
    protected function addFiles(DataObject $do)
    {
        $do->setExtension(TYPE_MP3);
        $key = $this->generateKey($do->getEntityName(), $do->getEntityId());
        for ($i=0; $i<$this->redis->lSize($key); $i++) {
            $string = $this->redis->lGet($key, $i);
            if (strlen($string) < 47) {
                continue;
            }

            $hash = $this->cleanHash($string);
            $file = new File();
            $file->setUrl($do->getRelativeDirectoryUrl() . $hash . '.' . $do->getExtension());
            $do->attachFile($file);
        }
    }

    protected function cleanHash($string): string
    {
        return substr($string, 15);
    }

    protected function getResourceName(DataObject $do): string
    {
        return '/audio/'.$do->getEntityName().'/'.$do->getEntityId();
    }
}
