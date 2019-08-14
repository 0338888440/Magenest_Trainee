<?php

namespace Magenest\Movie\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup ();
        if (version_compare ($context->getVersion (), "0.0.13") < 0) {
            $this->addStatusMovie ($setup);
        }

        $setup->endSetup ();
    }
    public function addStatusMovie($setup){
        $tableName = $setup->getTable('magenest_movie');
        $setup->getConnection()->addColumn ($tableName,
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [

            ]
        );
    }
}