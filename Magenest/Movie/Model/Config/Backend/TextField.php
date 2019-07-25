<?php

namespace Magenest\Movie\Model\Config\Backend;

use Magento\Framework\App\Config\ScopeConfigInterface;
use phpDocumentor\Reflection\Types\This;

class TextField extends \Magento\Framework\App\Config\Value
{
    protected $_configValueFactory;

    protected $_scopeConfig;
    protected $_configWriter;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        array $data = []
    )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_configWriter = $configWriter;
        $this->_configValueFactory = $configValueFactory;
        parent::__construct ($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function _afterLoad()
    {
        $value = $this->_scopeConfig->getValue('movie/moviepage/text_field', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($value == 'Ping')
        {
            $this->setValue ('Pong');
        }
    }
}
