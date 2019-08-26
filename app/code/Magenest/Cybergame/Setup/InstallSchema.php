<?php

namespace Magenest\Cybergame\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup ();

        $this->upgradeGamerAccountList ($installer);
        $this->upgradeRoomExtraOptions ($installer);

        $installer->endSetup ();

    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeGamerAccountList($installer)
    {
        $connection = $installer->getConnection ();
        //Install new database table
        $table = $connection->newTable ($installer->getTable ('gamer_account_list')
        )->addColumn (
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Id'
        )->addColumn (
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Product Id'
        )->addColumn (
            'account_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            [],
            'Account Name'
        )->addColumn (
            'password',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            [
                'default' => '1'
            ],
            'Password'
        )->addColumn (
            'hour',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [],
            'Hour'
        )->addColumn (
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
            ],
            'Created at'
        )->addColumn (
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
            ],
            'Updated at'
        )->addIndex ($installer->getIdxName ('gamer_account_list', ['product_id']), ['product_id'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
        )->addIndex ($installer->getIdxName ('gamer_account_list', ['account_name']), ['account_name'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        $installer->getConnection ()->createTable ($table);
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeRoomExtraOptions($installer)
    {
        $connection = $installer->getConnection ();
        //Install new database table
        $table = $connection->newTable ($installer->getTable ('room_extra_option')
        )->addColumn (
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Id'
        )->addColumn (
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Product Id'
        )->addColumn (
            'number_pc',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [],
            'Number Pc'
        )->addColumn (
            'address',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            [],
            'Address'
        )->addColumn (
            'food_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [],
            'Food Price'
        )->addColumn (
            'drink_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [],
            'Drink Price'
        )->addColumn (
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
            ],
            'Created at'
        )->addColumn (
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            [
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
            ],
            'Updated at'
        )->addIndex ($installer->getIdxName ('gamer_account_list', ['product_id']), ['product_id'],
            \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
        );
        $installer->getConnection ()->createTable ($table);
    }
}