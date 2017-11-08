<?php

namespace Common\Container;

use Common\Exception\RuntimeException;
use Psr\Container\ContainerInterface AS PsrContainerInterface;

/**
 * Class ContainerInterface
 * @package Common\Container
 */
interface ContainerInterface extends PsrContainerInterface
{

    /**
     * @param string $key
     * @param mixed $value
     * @param int $action
     * @param bool $reWrite
     * @return bool
     * @throws RuntimeException
     */
    public function attach($key, $value, $action = self::APPEND, $reWrite = false);

    /**
     * @param $key
     * @return bool
     */
    public function detach($key);

    public function toArray();
}
