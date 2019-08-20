<?php

namespace Magenest\FrontEnd\Setup;

use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var EavSetupFactory
     */
    private $_eavSetupFactory;

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @param Config $eavConfig
     * @param EavSetupFactory $eavSetupFactory
     * @param AttributeSetFactory $attributeSetFactory
     */
    public function __construct(
        Config $eavConfig,
        EavSetupFactory $eavSetupFactory,
        AttributeSetFactory $attributeSetFactory
    )
    {
        $this->eavConfig = $eavConfig;
        $this->_eavSetupFactory = $eavSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
    }

    public function upgrade (ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup ();
        if (version_compare ($context->getVersion (), "0.0.4") < 0) {
            $this->installStreetId ($setup);
            $this->installCityId ($setup);
        }
        $setup->endSetup ();
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function installStreetId($setup)
    {
        $eavSetup = $this->_eavSetupFactory->create (['setup' => $setup]);

        $eavSetup->addAttribute ('customer_address', 'street_id', [
            'type' => 'int',
            'input' => 'text',
            'label' => 'Street Id',
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'system' => false,
            'group' => 'General',
            'global' => true,
            'visible_on_front' => true,
        ]);

        $customAttribute = $this->eavConfig->getAttribute ('customer_address', 'street_id');

        $customAttribute->setData (
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit'] //list of forms where you want to display the custom attribute
        );
        $customAttribute->save ();
    }

    public function installCityId($setup)
    {
        $eavSetup = $this->_eavSetupFactory->create (['setup' => $setup]);

        $eavSetup->addAttribute ('customer_address', 'city_id', [
            'type' => 'int',
            'input' => 'text',
            'label' => 'City Id',
            'visible' => true,
            'required' => false,
            'user_defined' => true,
            'system' => false,
            'group' => 'General',
            'global' => true,
            'visible_on_front' => true,
        ]);

        $customAttribute = $this->eavConfig->getAttribute ('customer_address', 'city_id');

        $customAttribute->setData (
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit'] //list of forms where you want to display the custom attribute
        );
        $customAttribute->save ();
    }
}