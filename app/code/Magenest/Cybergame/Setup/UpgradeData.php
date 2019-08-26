<?php

namespace Magenest\Cybergame\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;


class UpgradeData implements UpgradeDataInterface
{

    protected $customerSetupFactory;
    private $attributeSetFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory
    )
    {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }


    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        if (version_compare ($context->getVersion (), '0.0.1') < 0) {

            $setup->startSetup ();
            $this->addManager ($setup);
            $setup->endSetup ();
        }
    }
    public function addManager($setup){
        $customerSetup = $this->customerSetupFactory->create (['setup' => $setup]);

        $customerEntity = $customerSetup->getEavConfig ()->getEntityType ('customer');
        $attributeSetId = $customerEntity->getDefaultAttributeSetId ();

        $attributeSet = $this->attributeSetFactory->create ();
        $attributeGroupId = $attributeSet->getDefaultGroupId ($attributeSetId);

        $customerSetup->addAttribute (\Magento\Customer\Model\Customer::ENTITY, 'is_manager', [
            'type' => 'int',
            'label' => 'Is Manager',
            'input' => 'select',
            "source" => 'Magenest\Cybergame\Model\Config\Source\IsManager',
            'required' => false,
            'default' => '0',
            'visible' => true,
            'user_defined' => true,
            'sort_order' => 2,
            'position' => 2,
            'system' => false,
        ]);

        $status = $customerSetup->getEavConfig ()->getAttribute (Customer::ENTITY, 'is_manager')
            ->addData ([
                'attribute_set_id' => $attributeSetId,
                'attribute_group_id' => $attributeGroupId,
                'used_in_forms' => ['adminhtml_customer', 'customer_account_create', 'customer_account_edit'],
            ]);

        $status->save ();
    }
}