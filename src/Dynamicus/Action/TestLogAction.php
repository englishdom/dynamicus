<?php
namespace Dynamicus\Action;

use Common\Action\ActionInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Http\Response;
use Zend\Log\LoggerInterface;


class TestLogAction implements ActionInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SearchAction constructor.
     * @param SearchAdapterInterface $adapter
     * @param LoggerInterface        $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function process(ServerRequestInterface $request, DelegateInterface $delegate): ResponseInterface
    {
        $type = $request->getAttribute('type');

        switch($type)
        {
            // notice
            case 'div-zero':
                $ass = true;
                $ass->a/0;
                break;
            // notice
            case 'ob-clean':
                ob_clean();
                break;
            case 'exec':
                exec('unknow-test-command');
                break;
            case 'object':
                $a = 2;
                $a->NotExistMethod();
                break;
            case 'file':
                $temp = tmpfile();
                fwrite($temp, '<?php $a=[[[[];?>' );
                require $temp;
                break;
            case 'log':
                /* @var $logger \Zend\Log\Logger */
                $logger = $this->logger;
                $logger->err('test err() ');
                $logger->notice('test notice(() ');
                $logger->crit('test crit(() ');
                $logger->warn('test warn() ');
                $logger->debug('test debug() ');
                $logger->emerg('test emerg() ');
                $logger->alert('test alert() ');
                $logger->info('test info() ');
                $logger->log(\Zend\Log\Logger::DEBUG, 'test log() ');

                break;
            case 'exception':
                throw new \Exception('тестирование throw');
                break;
            default:
                $this->logger->err('test err ', ['facility' => 'grubber-translate']);
                $this->logger->debug('test debug ', ['facility' => 'grubber-translate']);
        }

        $request = $request
            //->withAttribute(self::RESPONSE, 'OK')
            ->withAttribute(self::HTTP_CODE, Response::STATUS_CODE_204);

        return $delegate->process($request);
    }
}
