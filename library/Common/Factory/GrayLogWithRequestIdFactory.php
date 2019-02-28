<?php

namespace Common\Factory;

use Common\Log\Writer\RequestIdGrayLogWriter;
use Gelf\Transport\TcpTransport;
use Gelf\Transport\UdpTransport;
use Zend\Log\Logger;

/**
 * Class GreyLogFactory
 * @package Common\Factory
 */
class GrayLogWithRequestIdFactory extends GrayLogFactory
{
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

        $writer = new RequestIdGrayLogWriter($facility, $transport);
        $logger->addWriter($writer);

        return $logger;
    }
}