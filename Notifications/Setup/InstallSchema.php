<?php
/**
 * Copyright © 2015 Social. All rights reserved.
 */

namespace Mofluid\Notifications\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        $connection = $installer->getConnection();
        $table  = $installer->getConnection()
            ->newTable($installer->getTable('mofluid_notifications_items'))
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )
            ->addColumn(
            'notification_title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'notification_title'
            )
            ->addColumn(
                'pemfile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Pemfile'
            )
            ->addColumn(
                'passphrase',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Passphrase'
            )
             ->addColumn(
                'message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Message'
            )
               ->addColumn(
                'gcm_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'gcm_id'
            )
                ->addColumn(
                'gcm_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'gcm_key'
            );

        $installer->getConnection()->createTable($table);
        $connection->insertForce(
				$installer->getTable('mofluid_notifications_items'),
				[
					'id' => 1,
					'notification_title' => 'IOS Notification',
					'pemfile' => 0,
					'passphrase' => 0,
					'message' => null
				]
        );
          $connection->insertForce(
				$installer->getTable('mofluid_notifications_items'),
				[
					'id' => 2,
					'notification_title' => 'Android Notification',
					'gcm_id' => 0,
					'gcm_key' => 0,
					'message' => null
				]
        );
        $installer->endSetup();
    }
}
