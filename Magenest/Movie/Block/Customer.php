<?php
namespace Magenest\Movie\Block;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class Customer extends Template
{
    protected $_customerFactory;
    protected $_customerSession;
    protected $_urlInterface;
    public function __construct(Template\Context $context,
                                \Magento\Customer\Model\CustomerFactory $customerFactory,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Framework\UrlInterface $urlInterface,
                                array $data = [])
    {
        $this->_urlInterface = $urlInterface;
        $this->_customerSession = $customerSession;
        $this->_customerFactory = $customerFactory;
        parent::__construct ($context, $data);
    }
    public function getCustomerCollection($id) {
        return $this->_customerFactory->create ()->getCollection ()
            ->addFieldToSelect ('*')
            ->addFieldToFilter ('entity_id',$id);
    }
    public function getSessionID() {
        if ($this->_customerSession->isLoggedIn ())
        return $this->_customerSession->getCustomer ()->getId ();
    }
    public function getBaseUrlImg()
    {
        $currentStore=$this->_storeManager->getStore ();
         return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'customer';
    }
}