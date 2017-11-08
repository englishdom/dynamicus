<?php

namespace Common\Middleware;

use Common\Entity\ImageDataObject;
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
    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        /* @var ImageDataObject $do */
        $do = $request->getAttribute(ImageDataObject::class);
        $relativePath = $this->getFolder($do->getEntityId(), $request->getAttribute('entity'));
        $do->setRelativePath($relativePath);

        return $delegate->process($request);
    }

    private function getFolder($identifier, $prefix = null): string
    {
        /* Добавление 0, чтобы получилось 12 значная строка */
        $twelveChars = sprintf('%012d', $identifier);
        /* Дробление 12 значной строки по 3 знака */
        $split = str_split($twelveChars, 3);

        if (!$prefix) {
            array_unshift($split, $prefix);
        }

        /* Построение пути */
        return implode(DIRECTORY_SEPARATOR, $split);
    }
}
