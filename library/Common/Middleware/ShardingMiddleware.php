<?php

namespace Common\Middleware;

use Common\Container\ConfigInterface;
use Common\Entity\DataObject;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ShardingMiddleware
 * @package Common\Middleware
 */
class ShardingMiddleware implements MiddlewareInterface
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

    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var DataObject $do */
        $do = $request->getAttribute(DataObject::class);
        $shardingPath = $this->getFolder($do->getEntityId(), $do->getEntityName());
        $do->setShardingPath($shardingPath);
        $do->setRelativeDirectoryUrl($this->getRelativeUrlPrefix($do));
        $do->setTmpDirectoryPath($this->getTmpPathPrefix($do));

        return $delegate->process($request);
    }

    private function getFolder($identifier, $prefix = null): string
    {
        /* Добавление 0, чтобы получилось 9 значная строка */
        $twelveChars = sprintf('%09d', $identifier);
        /* Дробление 12 значной строки по 3 знака */
        $split = str_split($twelveChars, 3);

        if ($prefix) {
            array_unshift($split, $prefix);
        }

        /* Построение пути */
        return implode(DIRECTORY_SEPARATOR, $split);
    }

    /**
     * Получение абсолютного пути к папке в файловой системе /var/www/images/word/000/000/000/001/
     * @param DataObject $do
     * @return string
     */
    private function getTmpPathPrefix(DataObject $do): string
    {
        return str_replace('//', '/', $this->config->get('images-path.absolute-tmp-path', '')
            . DIRECTORY_SEPARATOR . $do->getShardingPath()
            . DIRECTORY_SEPARATOR
        );
    }

    /**
     * Получение относительного урла к рисункам /images/word/000/000/000/001/
     * @param DataObject $do
     * @return string
     */
    private function getRelativeUrlPrefix(DataObject $do): string
    {
        return str_replace('//', '/', $this->config->get('images-path.relative-url', '')
            . DIRECTORY_SEPARATOR . $do->getShardingPath()
            . DIRECTORY_SEPARATOR
        );
    }
}
