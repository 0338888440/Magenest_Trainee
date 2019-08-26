<?php

namespace Magenest\Cybergame\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class UpdateRoomInfo
 * @package Magenest\Cybergame\Block
 */
class EditRoomInfo extends Template
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productFactory;
    protected $_roomExtraOption;

    /**
     * UpdateRoomInfo constructor.
     * @param Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(Template\Context $context,
                                \Magento\Catalog\Model\ProductFactory $productCollectionFactory,
                                \Magenest\Cybergame\Model\ResourceModel\RoomExtraOption\CollectionFactory $collectionFactory,
                                array $data = [])
    {
        $this->_productFactory = $productCollectionFactory;
        $this->_roomExtraOption = $collectionFactory;
        parent::__construct ($context, $data);
    }

    public function getRoomOptionCollection()
    {
        $id = $this->getRequest ()->getParam ('id');
        $collection = $this->_roomExtraOption->create ();

        $firstItem = $collection->addFieldToSelect ('*')->addFieldToFilter ('product_id', $id)->getFirstItem ();

        return $firstItem;
    }

    public function getNameProductById()
    {
        $id = $this->getRequest ()->getParam ('id');
        $coll = $this->_productFactory->create ()->load ($id);
        return $coll->getData ('name');
    }

    public function getPriceProductById()
    {
        $id = $this->getRequest ()->getParam ('id');
        $coll = $this->_productFactory->create ()->load ($id);
        return $coll->getData ('price');
    }
    public function getSaveUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $storeManager = $objectManager->get ('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl = $storeManager->getStore ()->getBaseUrl ();
        $saveUrl = $baseUrl . 'cybergame/updateroominfo/save';
        return $saveUrl;
    }
}