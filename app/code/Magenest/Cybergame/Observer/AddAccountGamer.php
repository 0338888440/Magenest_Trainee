<?php

namespace Magenest\Cybergame\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\ObserverInterface;

class AddAccountGamer implements ObserverInterface
{
    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;
    protected $_checkoutSession;

    public function __construct(
        CheckoutSession $checkoutSession,
        \Psr\Log\LoggerInterface $logger)
    {
        $this->_checkoutSession = $checkoutSession;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $gamerAccountList = $objectManager->create ('\Magenest\Cybergame\Model\GamerAccountList');

        $qty = $this->_checkoutSession->getData ('qty');
        $accountName = $this->_checkoutSession->getData ('account_name');
        $productId = $this->_checkoutSession->getData ('product_id');

        $gamerAccountListColl = $gamerAccountList->load ($accountName, 'account_name');
        if (empty($gamerAccountListColl->getData ())) {
            $gamerAccountListColl->setData('hour', $qty);
            $gamerAccountListColl->setData('password', '1');
            $gamerAccountListColl->setData('product_id', $productId);
            $gamerAccountListColl->setData('account_name', $accountName);
            $gamerAccountListColl->save();
        } else {
            $hour = $gamerAccountListColl->getData ('hour') + $qty;
            $gamerAccountListColl->setData('hour', $hour);
            $gamerAccountListColl->save();
        }

    }
}