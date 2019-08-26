<?php
namespace Magenest\KnockoutJs\Controller\Test;

use Magento\Store\Model\StoreManager;


class SaveCustomer extends \Magento\Framework\App\Action\Action
{
    protected $_storeManager;
    protected $_customerSession;
    protected $_customerFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Customer\Model\Session $customerSession,
        StoreManager $storeManager)
    {
        $this->_customerFactory = $customerFactory;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        $firstname = $this->getRequest()->getParam('firstname');
        $lastname = $this->getRequest()->getParam('lastname');
        $id = $this->getSessionID();
        $collCus = $this->_customerFactory->create ()->load ($id);
        $collCus->setData ('firstname',$firstname);
        $collCus->setData ('lastname',$lastname);
        $collCus->save ();
    }
    public function getSessionID()
    {
        if ($this->_customerSession->isLoggedIn ())
            return $this->_customerSession->getCustomer ()->getId ();
    }
}