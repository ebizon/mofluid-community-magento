<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

namespace Mofluid\Payment\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
	
        $installer = $setup;

        $installer->startSetup();
        
        $connection = $installer->getConnection();

		/**
         * Create table 'payment_index'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mofluid_payment_items')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'payment_index'
        )
		->addColumn(
            'payment_method_title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_method_title'
        )
		->addColumn(
            'payment_method_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_method_code'
        )
		->addColumn(
            'payment_method_order_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_method_order_code'
        )
		->addColumn(
            'payment_method_status',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_method_status'
        )
		->addColumn(
            'payment_account_email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_account_email'
        )
		->addColumn(
            'payment_method_account_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_method_account_id'
        )
		->addColumn(
            'payment_method_account_key',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_method_account_key'
        )
		->addColumn(
            'payment_method_mode',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'payment_method_mode'
        )
		/*{{CedAddTableColumn}}}*/
		
		
        ->setComment(
            'Mofluid Payment payment_index'
        );
		
		$installer->getConnection()->createTable($table);
		/*{{CedAddTable}}*/
		
		$connection->insertForce(
            $installer->getTable('mofluid_payment_items'),
            [
                'id' => 1,
                'payment_method_title' => 'Cash On Delivery',
                'payment_method_code' => 'cod',
                'payment_method_order_code' => 'cashondelivery',
                'payment_method_status' => 0,
                'payment_method_mode' => 0
            ]
        );
        $connection->insertForce(
            $installer->getTable('mofluid_payment_items'),
            [
                'id' => 2,
                'payment_method_title' => 'Authorize.Net',
                'payment_method_code' => 'authorize',
                'payment_method_order_code' => 'authorizenet',
                'payment_method_status' => 0,
                'payment_method_mode' => 0           
            ]
        );
        $connection->insertForce(
            $installer->getTable('mofluid_payment_items'),
            [
                'id' => 3,
                'payment_method_title' => 'Paypal Standard',
                'payment_method_code' => 'paypal',
                'payment_method_order_code' => 'paypal_standard',
                'payment_method_status' => 0,
                'payment_method_mode' => 0
            ]
        );
        $connection->insertForce(
            $installer->getTable('mofluid_payment_items'),
            [
                'id' => 5,
                'payment_method_title' => 'Bank Transfer',
                'payment_method_code' => 'banktransfer',
                'payment_method_order_code' => 'banktransfer',
                'payment_method_status' => 0,
                'payment_method_mode' => 0
            ]
        );
        $connection->insertForce(
            $installer->getTable('mofluid_payment_items'),
            [
                'id' => 9,
                'payment_method_title' => 'Stripe',
                'payment_method_code' => 'md_stripe',
                'payment_method_order_code' => 'md_stripe',
                'payment_method_status' => 1,
                'payment_method_mode' => 0
            ]
        );
        $connection->insertForce(
            $installer->getTable('mofluid_payment_items'),
            [
                'id' => 10,
                'payment_method_title' => 'Paypal Express Checkout',
                'payment_method_code' => 'express_checkout',
                'payment_method_order_code' => 'express_checkout',
                'payment_method_status' => 1,
                'payment_method_mode' => 0
            ]
        ); 

        $installer->endSetup();

    }
}
