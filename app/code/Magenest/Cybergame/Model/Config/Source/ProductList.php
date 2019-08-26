<?php

namespace Magenest\Cybergame\Model\Config\Source;

class ProductList implements \Magento\Framework\Data\OptionSourceInterface
{
    private $_productCollectionFactory;
    protected $_options;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    public function toOptionArray()
    {
        if ($this->_options === null) {
            $collection = $this->_productCollectionFactory->create ();

            $this->_options = [['label' => '', 'value' => '']];

            foreach ($collection as $item) {
                $this->_options[] = [
                    'label' => __ ('%1', $item->getData ('sku')),
                    'value' => $item->getId ()
                ];
            }
        }
        return $this->_options;
    }
}