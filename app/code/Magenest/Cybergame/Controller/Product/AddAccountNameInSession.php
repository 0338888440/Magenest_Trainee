<?php

namespace Magenest\Cybergame\Controller\Product;

use Magento\Checkout\Model\Session as CheckoutSession;

class AddAccountNameInSession extends \Magento\Framework\App\Action\Action
{
    protected $_checkoutSession;

    public function __construct(CheckoutSession $checkoutSession,
                                \Magento\Framework\App\Action\Context $context)
    {
        $this->_checkoutSession = $checkoutSession;
        parent::__construct ($context);
    }

    public function execute()
    {
        $accountName = $this->getRequest ()->getParam ('account_name');
        $productId = $this->getRequest ()->getParam ('product_id');
        $qty = $this->getRequest ()->getParam ('qty');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $customerSession = $objectManager->create ('\Magento\Customer\Model\Session');
        if ($customerSession->isLoggedIn ()) {
            $this->_checkoutSession->setData ('account_name', $accountName);
            $this->_checkoutSession->setData ('qty', $qty);
            $this->_checkoutSession->setData ('product_id', $productId);
        }
    }
}