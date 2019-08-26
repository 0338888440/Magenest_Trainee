<?php

namespace Magenest\Movie\Block\Adminhtml;

use Magento\Backend\Block\Template;

class Report extends \Magento\Backend\Block\Template
{
    protected $_customersCollection;
    protected $_productCollectionFactory;
    protected $_orderCollectionFactory;
    protected $_invoicesCollection;
    protected $_creditmemoCollection;
    protected $_moduleList;

    public function __construct(Template\Context $context,
                                \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerFactory,
                                \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
                                \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollection,
                                \Magento\Sales\Model\ResourceModel\Order\Creditmemo\Collection $creditmemoCollection,
                                \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
                                \Magento\Framework\Module\ModuleList $moduleList,
                                array $data = [])
    {
        $this->_moduleList = $moduleList;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_creditmemoCollection = $creditmemoCollection;
        $this->_invoicesCollection = $invoiceCollection;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_customersCollection = $customerFactory;
        parent::__construct ($context, $data);
    }

    public function getNumMod()
    {
        $list = $this->_moduleList->getAll ();
        return count ($list);
    }

    public function getNumModNotMage()
    {
        $list = $this->_moduleList->getNames ();
        $i = 0;
        foreach ($list as $item) {
            if (substr ($item, 0, 8) != "Magento_")
                $i++;
        }
        return $i;
    }

    public function getNumModMagenest()
    {
        $list = $this->_moduleList->getNames ();
        $i = 0;
        foreach ($list as $item) {
            if (substr ($item, 0, 9) == "Magenest_")
                $i++;
        }
        return $i;
    }

    public function getNumModule()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance (); // Instance of object manager
        $resource = $objectManager->get ('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection ();
        $tableName = $resource->getTableName ('setup_module'); //gives table name with prefix
        //Select Data from table
        $sql = "Select count(module) FROM " . $tableName;
        return $connection->fetchOne ($sql); // gives associated array, table fields as key in array.
    }

    public function getNumModuleMagenest()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance (); // Instance of object manager
        $resource = $objectManager->get ('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection ();
        $tableName = $resource->getTableName ('setup_module'); //gives table name with prefix
        //Select Data from table
        $sql = "Select count(module) FROM " . $tableName . " WHERE module like '%Magenest%'";
        return $connection->fetchOne ($sql);
    }

    public function getNumModuleNotMagento()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance (); // Instance of object manager
        $resource = $objectManager->get ('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection ();
        $tableName = $resource->getTableName ('setup_module'); //gives table name with prefix
        //Select Data from table
        $sql = "Select count(module) FROM " . $tableName . " WHERE module not like '%Magento%'";
        return $connection->fetchOne ($sql);
    }

    public function getNumCus()
    {
        return $this->_customersCollection->create ()->getSize ();
    }

    public function getNumPro()
    {
        return $this->_productCollectionFactory->create ()->getSize ();
    }

    public function getNumOrd()
    {
        return $this->_orderCollectionFactory->create ()->getSize ();
    }

    public function getNumInv()
    {
        return $this->_invoicesCollection->create ()->getSize ();
    }

    public function getNumCrm()
    {
        return $this->_creditmemoCollection->getSize ();
    }
}