<?php

namespace Uet\Calendar\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (!$setup->tableExists('occasions')) {
            $table = $setup->getConnection()->newTable(
                $setup->getTable('occasions')
            )
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Occasion ID'
            )
            ->addColumn(
                'user_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'User ID'
            )
            ->addColumn(
                'occasion',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Occasion Title'
            )
            ->addColumn(
                'date',
                Table::TYPE_DATE,
                null,
                ['nullable' => false],
                'Occasion Date'
            )
            ->setComment('User Occasions Table');
            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}
