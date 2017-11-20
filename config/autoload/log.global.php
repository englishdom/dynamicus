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
        'host' => env('GRAYLOG_HOST'),
        'port' => env('GRAYLOG_PORT'),
        'protocol' => 'TCP',
        'facility' => 'Dinamicus. HOST: ' . $host . ' IP:' . $ip,
    ],
];