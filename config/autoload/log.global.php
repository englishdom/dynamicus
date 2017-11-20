<?php
namespace Common;

use Common\Factory;
use Zend\Log\LoggerInterface;

$host = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
$ip = (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');

return [
    'dependencies' => [
        'factories'  => [
            LoggerInterface::class => Factory\GrayLogFactory::class,
        ],
    ],
    'graylog' => [
        'host' => '148.251.1.228',
        'port' => '12202',
        'protocol' => 'TCP',
        'facility' => 'Dinamicus. HOST: ' . $host . ' IP:' . $ip,
    ],
];