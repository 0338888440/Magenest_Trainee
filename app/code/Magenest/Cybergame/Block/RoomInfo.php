<?php

namespace Magenest\Cybergame\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class UpdateRoomInfo
 * @package Magenest\Cybergame\Block
 */
class RoomInfo extends Template
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * UpdateRoomInfo constructor.
     * @param Template\Context $context
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param array $data
     */
    public function __construct(Template\Context $context,
                                \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Customer\Model\CustomerFactory $customerFactory,
                                array $data = [])
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_customerFactory = $customerFactory;
        parent::__construct ($context, $data);
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create ();
        $collection->addAttributeToSelect ('*');
        return $collection;
    }

    /**
     * @return string
     */
    public function checkManager()
    {
        $cusIdLogIn = $this->getSessionID ();
        $cusColl = $this->getCustomerCollection ($cusIdLogIn);
        $is_manager = $cusColl->getData ('is_manager');
        if ($is_manager == 0)
            return 'hidden';
    }

    /**
     * @return mixed
     */
    public function getSessionID()
    {
        if ($this->_customerSession->isLoggedIn ())
            return $this->_customerSession->getCustomer ()->getId ();
    }

    /**
     * @param $id
     * @return \Magento\Framework\DataObject
     */
    public function getCustomerCollection($id)
    {
        return $this->_customerFactory->create ()->getCollection ()
            ->addFieldToSelect ('*')
            ->addFieldToFilter ('entity_id', $id)->getFirstItem ();
    }

    public function getEditUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $storeManager = $objectManager->get ('\Magento\Store\Model\StoreManagerInterface');
        $baseUrl = $storeManager->getStore ()->getBaseUrl ();
        $editUrl = $baseUrl . 'cybergame/updateroominfo/edit/id/';
        return $editUrl;
    }
}