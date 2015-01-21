<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 15-1-20
 * Time: 上午11:18
 */

namespace Common\Logs;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class OperationLogger
{
    protected $logger;
    public function getLogger()
    {
        $writer = new Stream('data/logs/operation.log');
        $this->logger = new Logger();
        $this->logger->addWriter($writer);
        return $this->logger;
    }
}