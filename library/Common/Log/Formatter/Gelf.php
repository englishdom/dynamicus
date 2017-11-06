<?php

namespace Common\Log\Formatter;

use Gelf\Message;
use \Zend\Log\Formatter\Base;

class Gelf extends Base
{
    private $facility = 'ZF2';

    public function __construct($facility = null)
    {
        if (!is_null($facility)) {
            $this->facility = (string) $facility;
        }
    }

    /**
     * Returns a GELFMessage instance to be used with a GELFMessagePublisher
     *
     * @return Message
     */
    public function format($event)
    {
        $message = new Message();
        $message->setHost(gethostname());
        if (isset($event['priority'])) {
            $message->setLevel($event['priority']);
        } else if (isset($event['errno'])) {
            // @todo Convert to syslog error levels?
            $message->setLevel($event['errno']);
        }
        $message->setFullMessage($event['message']);
        $message->setShortMessage($event['message']);
        if (isset($event['full'])) $message->setFullMessage($event['full']);
        if (isset($event['short'])) $message->setShortMessage($event['short']);
        if (isset($event['file'])) $message->setFile($event['file']);
        if (isset($event['line'])) $message->setLine($event['line']);
        if (isset($event['version'])) $message->setVersion($event['version']);
        if (isset($event['facility'])) {
            $message->setFacility($event['facility']);
        } else {
            $message->setFacility($this->facility);
        }
        // Set timestamp
        $timestamp = $event['timestamp'];
        if ($event['timestamp'] && ($event['timestamp'] instanceof \DateTime)) {
            $timestamp = $event['timestamp']->getTimestamp();
        }
        $message->setTimestamp($timestamp);
        foreach ($event as $k => $v) {
            if (!in_array($k, array( 'message', 'priority', 'errno', 'full', 'short',
                'file', 'line', 'version', 'facility', 'timestamp', 'extra') )) {
                $message->setAdditional($k, $v);
            }
        }
        foreach ( $event['extra'] as $k => $v ) {
            $message->setAdditional($k, $v);
        }
        return $message;
    }
}