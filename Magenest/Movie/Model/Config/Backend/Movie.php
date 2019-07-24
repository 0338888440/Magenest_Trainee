<?php

namespace Magenest\Movie\Model\Config\Backend;

use phpDocumentor\Reflection\Types\This;

class Movie extends \Magento\Framework\App\Config\Value
{
    protected $_configValueFactory;
    protected $_movie;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Config\ValueFactory $configValueFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        \Magenest\Movie\Model\ResourceModel\Movie\CollectionFactory $movie,
        array $data = []
    )
    {
        $this->_configValueFactory = $configValueFactory;
        $this->_movie = $movie;
        parent::__construct ($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function _afterLoad()
    {
        $temp = $this->_movie->create()->getSize();

        $this->setValue($temp);
        return $temp;
    }
}
