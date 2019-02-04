<?php

namespace Common\Factory;

use Common\Container\ConfigInterface;
use Common\Exception\RuntimeException;
use Common\Log\Writer\GrayLogWriter;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use Interop\Container\ContainerInterface;
use Zend\Log\Logger;

/**
 * Class GreyLogFactory
 * @package Common\Factory
 */
class GrayLogFactory
{
    /**
     * @param ContainerInterface $container
     * @return Logger
     * @throws RuntimeException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Logger
    {
        $config = $container->get(ConfigInterface::class);

        $host = $config->get('graylog.host');
        $port = $config->get('graylog.port');
        $protocol = $config->get('graylog.protocol');
        $facility = 'ZF 2 Graylog logger';
        if ($config->get('graylog.facility')) {
            $facility = $config->get('graylog.facility');
        }
        if (!$host) {
            throw new RuntimeException('Graylog host not exist in config');
        }

        return $this->getGrayLogLogger($host, $port, $facility, $protocol);
    }

    /**
     * @param $hostname
     * @param $port
     * @param $facility
     * @param $protocol
     * @return Logger
     */
    protected function getGrayLogLogger($hostname, $port, $facility, $protocol): Logger
    {
        $logger = new Logger();
        if ('TCP' == $protocol) {
            $transport = new TcpTransport($hostname, $port);
        }
        else {
            $transport = new UdpTransport($hostname, $port);
        }

        $writer = new GrayLogWriter($facility, $transport);
        $logger->addWriter($writer);

        return $logger;
    }
}
