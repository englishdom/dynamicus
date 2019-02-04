<?php

namespace Common\Log\Message;

use Gelf\Message;
use Gelf\MessageInterface;

class RequestIdMessage extends Message
{
    public function __construct(MessageInterface $message)
    {
        foreach (get_object_vars($message) as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = parent::toArray();
        if (isset($result['_RequestId'])) {
            $result['request_id'] = $result['_RequestId'];
            unset($result['_RequestId']);
        }

        return $result;
    }
}