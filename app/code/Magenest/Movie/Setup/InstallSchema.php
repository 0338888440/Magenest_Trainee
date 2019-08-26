<?php

namespace Magenest\Movie\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup ();

        $this->upgradeMovie ($installer);
        $this->upgradeDirector ($installer);
        $this->upgradeActor ($installer);
        $this->upgradeMovieActor ($installer);
        $this->upgradeFKMovieDirector ($installer);
        $this->upgradeFKMovie ($installer);
        $this->upgradeFKActor ($installer);

        $installer->endSetup ();

    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeMovie($installer)
    {
        $connection = $installer->getConnection ();
        //Install new database table
        $table = $installer->getConnection ()->newTable ($installer->getTable ('magenest_movie')
        )->addColumn (
            'movie_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null, [
            'identity' => true,
            'nullable' => false,
            'primary' => true
        ],
            'Movie Id'
        )->addColumn (
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => false],
            'Name'
        )->addColumn (
            'description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => false],
            'Description'
        )->addColumn (
            'rating',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [
                'default' => '0',
                'unsigned' => true
            ],
            'Rating'
        )->addColumn (
            'director_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            [
            ],
            'Director Id'
        )->addIndex (
            $installer->getIdxName
            ('magenest_movie', ['name']), ['name']
        );
        $installer->getConnection ()->createTable ($table);
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeDirector($installer)
    {
        $connection = $installer->getConnection ();
        //Install new database table
        $table = $installer->getConnection ()->newTable ($installer->getTable ('magenest_director'))
            ->addColumn (
                'director_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Director Id'
            )->addColumn (
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Director Name'
            )->addIndex (
                $installer->getIdxName
                ('magenest_director', ['name']), ['name']
            )
            ->setComment ('Director');

        $installer->getConnection ()->createTable ($table);
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeActor($installer)
    {
        $table = $installer->getConnection ()->newTable ($installer->getTable ('magenest_actor'))
            ->addColumn (
                'actor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Actor ID'
            )
            ->addColumn (
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Actor Name'
            )->addIndex (
                $installer->getIdxName
                ('magenest_actor', ['name']), ['name']
            )
            ->setComment ('Actor');

        $installer->getConnection ()->createTable ($table);
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeMovieActor($installer)
    {
        $table = $installer->getConnection ()->newTable ($installer->getTable ('magenest_movie_actor'))
            ->addColumn (
                'movie_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [
                    'primary' => true,
                    'nullable' => false,

                ],
                'Movie ID'
            )->addColumn (
                'actor_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                [
                    'primary' => true,
                    'nullable' => false,
                ],
                'Actor ID'
            )->addIndex (
                $installer->getIdxName
                ('magenest_movie_actor', ['movie_id']), ['movie_id']
            );
        $installer->getConnection ()->createTable ($table);
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeFKMovieDirector($installer)
    {
        if ($installer->tableExists ('magenest_director')) {
            if ($installer->tableExists ('magenest_movie')) {
                $connection = $installer->getConnection ();
                $connection->addForeignKey ($installer->getFkName (
                    'magenest_movie',
                    'director_id',
                    'magenest_director',
                    'director_id'
                ),
                    'magenest_movie',
                    'director_id',
                    'magenest_director',
                    'director_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );
            }
        }
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeFKMovie($installer)
    {
        if ($installer->tableExists ('magenest_movie')) {
            if ($installer->tableExists ('magenest_movie_actor')) {
                $connection = $installer->getConnection ();
                $connection->addForeignKey ($installer->getFkName (
                    'magenest_movie_actor',
                    'movie_id',
                    'magenest_movie',
                    'movie_id'
                ),
                    'magenest_movie_actor',
                    'movie_id',
                    'magenest_movie',
                    'movie_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );
            }
        }
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    public function upgradeFKActor($installer)
    {
        if ($installer->tableExists ('magenest_actor')) {
            if ($installer->tableExists ('magenest_movie_actor')) {
                $connection = $installer->getConnection ();
                $connection->addForeignKey ($installer->getFkName (
                    'magenest_movie_actor',
                    'actor_id',
                    'magenest_actor',
                    'actor_id'
                ),
                    'magenest_movie_actor',
                    'actor_id',
                    'magenest_actor',
                    'actor_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                );
            }
        }
    }
}