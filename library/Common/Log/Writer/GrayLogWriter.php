<?php

namespace Common\Log\Writer;

use Gelf\MessageValidator;
use Gelf\Publisher;
use Gelf\Transport\AbstractTransport;
use \Zend\Log\Writer\AbstractWriter;
use Common\Log\Formatter\Gelf;

class GrayLogWriter extends AbstractWriter
{
    /**
     * @var Publisher
     */
    private $publisher;

    /**
     * @var \Common\Log\Formatter\Gelf
     */
    protected $formatter;

    public function __construct($facility, AbstractTransport $transport)
    {
        $messageValidator = new MessageValidator();
        $this->setPublisher(new Publisher($transport, $messageValidator));
        $this->setFormatter(new Gelf($facility));
    }

    public function setFormatter($formatter, array $options = null)
    {
        if (!($formatter instanceof Gelf)) {
            throw new \RuntimeException('Wrong formatter for graylog logger');
        }
        $this->formatter = $formatter;
    }

    /**
     * @param Publisher $publisher
     * @return $this
     */
    public function setPublisher(Publisher $publisher)
    {
        $this->publisher = $publisher;
        return $this;
    }

    public function doWrite(array $event)
    {
        $message = $this->formatter->format($event);
        $this->publisher->publish($message);
    }
}
