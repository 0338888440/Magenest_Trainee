<?php

namespace Magenest\Cybergame\Controller\Product;

class CheckAccount extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context)
    {
        parent::__construct ($context);
    }

    public function execute()
    {
        $accountName = $this->getRequest ()->getParam ('account_name');
        $productId = $this->getRequest ()->getParam ('product_id');
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance ();
        $gamerAccountList = $objectManager->create ('\Magenest\Cybergame\Model\GamerAccountList');

        $raw = $this->resultFactory->create ('raw');
        $gamerAccountListColl = $gamerAccountList->load ($accountName, 'account_name');

        if ($gamerAccountListColl->getData ('product_id') == $productId) {
            $raw->setHttpResponseCode (200);
        } else {
            $raw->setHttpResponseCode (404);
        }
        return $raw;
    }
}