<?php

namespace Common\Log\Writer;

use Common\Log\Message\RequestIdMessage;

class RequestIdGrayLogWriter extends GrayLogWriter
{
    public function doWrite(array $event)
    {
        $message = $this->formatter->format($event);
        $messageProxy = new RequestIdMessage($message);
        $this->publisher->publish($messageProxy);
    }
}