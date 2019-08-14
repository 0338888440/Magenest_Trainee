<?php

namespace Magenest\Movie\Observer\Backend;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Customer\Model\CustomerFactory;

class SaveCustomerObserver implements ObserverInterface
{
    /** @var \Psr\Log\LoggerInterface $logger */
    protected $logger;
    protected $_customer;

    public function __construct(\Psr\Log\LoggerInterface $logger,
                                CustomerFactory $customer)
    {
        $this->_customer = $customer;
        $this->logger = $logger;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->logger->debug ('Save A Customer');

        $newId = $observer->getCustomer ()->getId ();

        $customer = $this->_customer->create ()->load ($newId);
        $customer->setFirstname ('Magenest');
        $customer->save ();
    }
}