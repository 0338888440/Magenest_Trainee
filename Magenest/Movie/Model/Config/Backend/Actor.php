<?php

namespace Magenest\Movie\Model\Config\Backend;

use phpDocumentor\Reflection\Types\This;

class Actor extends \Magento\Framework\App\Config\Value
{
    protected $_configValueFactory;
    protected $_actor;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magenest\Movie\Model\ResourceModel\Actor\CollectionFactory $actor,
        array $data = []
    )
    {
        $this->_configValueFactory = $configValueFactory;
        $this->_actor = $actor;
        parent::__construct ($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function afterLoad()
    {
        $temp = $this->_actor->create ()->getSize ();
        $this->setValue ($temp);
        return $temp;
    }
}
