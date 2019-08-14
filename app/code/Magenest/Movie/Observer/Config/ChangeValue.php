<?php

namespace Magenest\Movie\Observer\Config;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class ChangeValue implements ObserverInterface
{
    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;
    protected $_scopeConfig;
    protected $_configWriter;

    public function __construct(\Psr\Log\LoggerInterface $logger,
                                \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                                \Magento\Framework\App\Config\Storage\WriterInterface $configWriter)
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_configWriter = $configWriter;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->debug ('Save Config');
        $value = $this->_scopeConfig->getValue ('movie/moviepage/text_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($value == 'Ping') {
            $this->_configWriter->save ('movie/moviepage/text_field', 'Pong', $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
        }
    }
}