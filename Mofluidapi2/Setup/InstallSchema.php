<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

namespace Mofluid\Mofluidapi2\Setup;

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
        
        $table  = $installer->getConnection()->newTable( $installer->getTable('mofluid_themes') )
		->addColumn(
            'mofluid_theme_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Mofluid theme id'
        )
		->addColumn(
		    'mofluid_store_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Mofluid Store ID'
        )
		->addColumn(
		    'mofluid_theme_code',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			64,
			[],
			'Mofluid theme code'
        )
		->addColumn(
		    'mofluid_theme_title',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			64,
			[],
			'Mofluid theme title'
        )
		->addColumn(
		    'mofluid_theme_status',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['unsigned' => true, 'nullable' => false, 'default' => 1],
			'Mofluid theme status'
        )
		->addColumn(
            'mofluid_theme_custom_footer',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            2048,
            [],
            'Mofluid theme custom footer'
        )
		->addColumn(
            'mofluid_display_catsimg',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Mofluid display catsimg'
        )
		->addColumn(
            'mofluid_theme_display_custom_attribute',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'mofluid theme display custom attribute'
        )
		->addColumn(
            'mofluid_theme_banner_image_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['nullable' => false, 'default' => 1],
            'mofluid theme banner image type'
        )
		->addColumn(
            'google_ios_clientid',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => 1],
            'google ios clientid'
        )
        ->addColumn(
            'google_login',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            10,
            ['default' => ''],
            'google login'
        )
        ->addColumn(
            'cms_pages',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'cms pages'
        )
		->addColumn(
            'about_us',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'about us'
        )
        ->addColumn(
            'term_condition',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'term condition'
        )
        ->addColumn(
            'privacy_policy',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'privacy policy'
        )
        ->addColumn(
            'return_privacy_policy',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'return privacy policy'
        )
        ->addColumn(
            'tax_flag',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'tax flag'
        )
        ->setComment(
            'Mofluid Theme mordern'
        );
		
		$installer->getConnection()->createTable($table);
		
		$table1 = $installer->getConnection()->newTable( $installer->getTable('mofluid_themes_messages') )
		->addColumn(
            'mofluid_theme_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'unsigned' => true, 'nullable' => false],
            'mofluid theme id'
        )
		->addColumn(
            'mofluid_store_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			null,
			['nullable' => false, 'default' => '0'],
			'Mofluid Store ID'
        )
		->addColumn(
            'mofluid_message_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Mofluid message id'
        )
		->addColumn(
            'mofluid_message_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'mofluid message type'
        )
		->addColumn(
            'mofluid_message_label',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'mofluid message label'
        )
		->addColumn(
            'mofluid_message_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => ''],
            'mofluid message value'
        )
		->addColumn(
            'mofluid_message_helptext',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            512,
            ['default' => ''],
            'mofluid message helptext'
        )
		->addColumn(
            'mofluid_message_helplink',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => ''],
            'mofluid message helplink'
        )
		->addColumn(
            'mofluid_message_isrequired',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['nullable' => false, 'default' => '0'],
            'mofluid message isrequired'
        )
        ->setComment(
            'Mofluid Theme message'
        );
		
		$installer->getConnection()->createTable($table1);
		
		
		$_table  = $installer->getConnection()->newTable($installer->getTable('mofluid_authentication') )
			->addColumn(
				'id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				null,
				['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
				'ID'
				)
				->addColumn(
				'appid',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'64k',
				[],
				'App Id'
				)
				->addColumn(
				'token',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'64k',
				[],
				'Token'
				)
				->addColumn(
				'secretkey',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'64k',
				[],
				'secret Key'
				);
		
		$installer->getConnection()->createTable($_table);
		
		$table2 = $installer->getConnection()->newTable( $installer->getTable('mofluid_themes_images') )
		->addColumn(
            'mofluid_theme_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'unsigned' => true, 'nullable' => false],
            'mofluid theme id'
        )
		->addColumn(
            'mofluid_store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			11,
			['nullable' => false, 'default' => '0'],
            'mofluid store id'
        )
		->addColumn(
            'mofluid_image_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'mofluid image id'
        )
		->addColumn(
            'mofluid_image_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'mofluid image type'
        )
		->addColumn(
            'mofluid_image_label',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'mofluid image label'
        )
		->addColumn(
            'mofluid_image_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            512,
            ['default' => ''],
            'mofluid image value'
        )
		->addColumn(
            'mofluid_image_helptext',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            512,
            ['default' => ''],
            'mofluid image helptext'
        )
		->addColumn(
            'mofluid_image_helplink',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => ''],
            'mofluid image helplink'
        )
		->addColumn(
            'mofluid_image_isrequired',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
			['nullable' => false, 'default' => '0'],
            'mofluid_image_isrequired'
        )
        ->addColumn(
            'mofluid_image_sort_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
			['nullable' => false, 'default' => '0'],
            'mofluid image sort order'
        )
        ->addColumn(
            'mofluid_image_isdefault',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
			['nullable' => false, 'default' => '0'],
            'mofluid image isdefault'
        )
        ->addColumn(
            'mofluid_image_action',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            512,
            ['default' => ''],
            'mofluid image action'
        )
        ->addColumn(
            'mofluid_image_data',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            512,
            ['default' => ''],
            'mofluid image data'
        )
        ->addColumn(
            'cms_pages',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'cms pages'
        )
		->addColumn(
            'about_us',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'about us'
        )
        ->addColumn(
            'term_condition',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'term condition'
        )
        ->addColumn(
            'privacy_policy',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'privacy policy'
        )
        ->addColumn(
            'return_privacy_policy',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
			['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'return privacy policy'
        )
        ->setComment(
            'Mofluid Theme message'
        );
		
		$installer->getConnection()->createTable($table2);
		
		$table3 = $installer->getConnection()->newTable( $installer->getTable('mofluid_themes_colors') )
		->addColumn(
            'mofluid_theme_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            [ 'unsigned' => true, 'nullable' => false],
            'mofluid theme id'
        )
		->addColumn(
            'mofluid_store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
			11,
			['nullable' => false, 'default' => '0'],
            'mofluid store id'
        )
		->addColumn(
            'mofluid_color_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'mofluid color id'
        )
		->addColumn(
            'mofluid_color_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'mofluid color type'
        )
		->addColumn(
            'mofluid_color_label',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'mofluid color label'
        )
		->addColumn(
            'mofluid_color_value',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            ['default' => ''],
            'mofluid color value'
        )
		->addColumn(
            'mofluid_color_helptext',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            512,
            ['default' => ''],
            'mofluid color helptext'
        )
		->addColumn(
            'mofluid_color_helplink',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            256,
            ['default' => ''],
            'mofluid color helplink'
        )
		->addColumn(
            'mofluid_color_isrequired',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
			['nullable' => false, 'default' => '0'],
            'mofluid color isrequired'
        )
        ->setComment(
            'Mofluid Theme message'
        );
		
		$installer->getConnection()->createTable($table3);
		
		$connection->insertForce(
            $installer->getTable('mofluid_themes'),
            [ 'mofluid_theme_id' => 2, 'mofluid_theme_code' => 'modern', 'mofluid_theme_title' => 'Modern', 'mofluid_theme_status' => '0', 'google_ios_clientid' => '', 'google_login' => 0, 'cms_pages' => 0, 'about_us' => '', 'term_condition' => '', 'privacy_policy' => '', 'return_privacy_policy' => '', 'tax_flag' =>0 ]
        );
        
        $connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2300001, 'mofluid_image_type' =>'logo', 'mofluid_image_label' => 'Logo', 'mofluid_image_value' => 'mofluidlogo/images/l/o/logo.png', 'mofluid_image_helptext' => 'Upload your application logo displayed in the app (<b>Recommended Size : 150X50px</b>).', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 1, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2300002, 'mofluid_image_type' =>'banner', 'mofluid_image_label' => 'Banner', 'mofluid_image_value' => 'mofluidbanner/images/b/a/banner4.png', 'mofluid_image_helptext' => 'Upload your application banner displayed in the app (<b>Recommended Size : 1024x500px</b>).', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 1, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 1, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2300003, 'mofluid_image_type' =>'banner', 'mofluid_image_label' => 'Banner', 'mofluid_image_value' => 'mofluidbanner/images/b/a/banner5.png', 'mofluid_image_helptext' => 'Upload your application banner displayed in the app (<b>Recommended Size : 1024x500px</b>).', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 1, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 1, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2300004, 'mofluid_image_type' =>'banner', 'mofluid_image_label' => 'Banner', 'mofluid_image_value' => 'mofluidbanner/images/b/a/banner6.png', 'mofluid_image_helptext' => 'Upload your application banner displayed in the app (<b>Recommended Size : 1024x500px</b>).', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 1, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 1, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2400003, 'mofluid_image_type' =>'themeicons', 'mofluid_image_label' => 'Menu (32x32)', 'mofluid_image_value' => '', 'mofluid_image_helptext' => 'leave blank for default theme icons.', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 0, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2400004, 'mofluid_image_type' =>'themeicons', 'mofluid_image_label' => 'Back (32x32)', 'mofluid_image_value' => '', 'mofluid_image_helptext' => 'leave blank for default theme icons.', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 0, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2400005, 'mofluid_image_type' =>'themeicons', 'mofluid_image_label' => 'Cart (32x32)', 'mofluid_image_value' => '', 'mofluid_image_helptext' => 'leave blank for default theme icons.', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 0, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2400006, 'mofluid_image_type' =>'themeicons', 'mofluid_image_label' => 'Search (32x32)', 'mofluid_image_value' => '', 'mofluid_image_helptext' => 'leave blank for default theme icons.', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 0, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2400007, 'mofluid_image_type' =>'themeicons', 'mofluid_image_label' => 'Delete (32x32)', 'mofluid_image_value' => '', 'mofluid_image_helptext' => 'leave blank for default theme icons.', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 0, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2400008, 'mofluid_image_type' =>'themeicons', 'mofluid_image_label' => 'Cross (32x32)', 'mofluid_image_value' => '', 'mofluid_image_helptext' => 'leave blank for default theme icons.', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 0, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_images'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_image_id' => 2400009, 'mofluid_image_type' =>'themeicons', 'mofluid_image_label' => 'Cart Empty (150x150)', 'mofluid_image_value' => '', 'mofluid_image_helptext' => 'leave blank for default theme icons.', 'mofluid_image_helplink' =>'', 'mofluid_image_isrequired' => 0, 'mofluid_image_sort_order' => 0, 'mofluid_image_isdefault' => 0, 'mofluid_image_action' =>'' , 'mofluid_image_data' =>''  ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2100001, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Home', 'mofluid_message_value' => 'Home', 'mofluid_message_helptext' => 'This text will displayed at home button visible in slider menu', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100002, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'My Account', 'mofluid_message_value' => 'My Account', 'mofluid_message_helptext' => 'This text will displayed at my account button visible in slider menu', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100003, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Edit Profile', 'mofluid_message_value' => 'Edit Profile', 'mofluid_message_helptext' =>'This text will displayed at Edit Profile button visible in slider menu' , 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100004, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'My Orders', 'mofluid_message_value' => 'My Orders', 'mofluid_message_helptext' => 'This text will displayed at My Orders button visible in slider menu', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100005, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Sign In', 'mofluid_message_value' => 'Sign In', 'mofluid_message_helptext' => 'This text will displayed at Sign In button visible in slider menu', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100006, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Sign Out', 'mofluid_message_value' => 'Sign Out', 'mofluid_message_helptext' => 'This text will displayed at Sign Out button visible in slider menu', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100007, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Login', 'mofluid_message_value' => 'Login', 'mofluid_message_helptext' => 'This text will displayed at Login button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100008, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Proceed', 'mofluid_message_value' => 'Proceed', 'mofluid_message_helptext' => 'This text will displayed at Proceed button.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100009, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Update', 'mofluid_message_value' => 'Update', 'mofluid_message_helptext' => 'This text will displayed at Update button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100010, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Change Password', 'mofluid_message_value' => 'Change Password', 'mofluid_message_helptext' => 'This text will displayed at Change Password button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100011, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Forgot Password', 'mofluid_message_value' => 'Forgot Password', 'mofluid_message_helptext' => 'This text will displayed at Forgot Password button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100012, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Create An Account', 'mofluid_message_value' => 'Create An Account', 'mofluid_message_helptext' => 'This text will displayed at Create An Account button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100013, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Details', 'mofluid_message_value' => 'Details', 'mofluid_message_helptext' => 'This text will displayed at product detail page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100014, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Add to Cart', 'mofluid_message_value' => 'Add to Cart', 'mofluid_message_helptext' => 'This text will displayed at Add to Cart Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100015, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Apply Coupon', 'mofluid_message_value' => 'Apply Coupon', 'mofluid_message_helptext' => 'This text will displayed at Apply Coupon Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100016, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Cancel Coupon', 'mofluid_message_value' => 'Cancel Coupon', 'mofluid_message_helptext' => 'This text will displayed at Cancel Coupon Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100017, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Checkout', 'mofluid_message_value' => 'Checkout', 'mofluid_message_helptext' => 'This text will displayed at Checkout Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100018, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Continue Shopping', 'mofluid_message_value' => 'Continue Shopping', 'mofluid_message_helptext' => 'This text will displayed at Continue Shopping Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100019, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Confirm Proceed', 'mofluid_message_value' => 'Confirm Proceed', 'mofluid_message_helptext' => 'This text will displayed at Confirm Proceed Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100020, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Submit', 'mofluid_message_value' => 'Submit', 'mofluid_message_helptext' => 'This text will displayed at Submit Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100021, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Retrive Your Password', 'mofluid_message_value' => 'Retrive Your Password', 'mofluid_message_helptext' => 'This text will displayed at Retrive Your Password Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100022, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Click Here', 'mofluid_message_value' => 'Click Here', 'mofluid_message_helptext' => 'This text will displayed at Click Here Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100023, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Continue', 'mofluid_message_value' => 'Continue', 'mofluid_message_helptext' => 'This text will displayed at Continue Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100024, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Sign Up Now', 'mofluid_message_value' => 'Sign Up Now', 'mofluid_message_helptext' => 'This text will displayed at Sign Up Now Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100025, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Edit Information', 'mofluid_message_value' => 'Edit Information', 'mofluid_message_helptext' => 'This text will displayed at Edit Information Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100026, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Change Account Password', 'mofluid_message_value' => 'Change Account Password', 'mofluid_message_helptext' => 'This text will displayed at Change Account Password Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100027, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Get Price', 'mofluid_message_value' => 'Get Price', 'mofluid_message_helptext' => 'This text will displayed on Get Price Button of Product Description Page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100028, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Close', 'mofluid_message_value' => 'Close', 'mofluid_message_helptext' => 'This text will displayed to close popup', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_message_id' =>2100029, 'mofluid_message_type' => 'button', 'mofluid_message_label' => 'Update Cart', 'mofluid_message_value' => 'Update Cart', 'mofluid_message_helptext' => 'This text will displayed to update cart', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]
		);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300001,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Display Application Name', 'mofluid_message_value' => '', 'mofluid_message_helptext' =>'If blank, Name of your application will used', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>0 ] );
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300002,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Support Text', 'mofluid_message_value' => 'Need help? 24 X 7 support', 'mofluid_message_helptext' =>'Used when default footer is selected', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300003,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Policy Text', 'mofluid_message_value' => 'Policies', 'mofluid_message_helptext' =>'Used when default footer is selected', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300004,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shop By Departments', 'mofluid_message_value' =>'', 'mofluid_message_helptext' =>'If blank, text Shop by department will used', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>0 ] );
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300005,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Personal Information', 'mofluid_message_value' => 'Personal Information', 'mofluid_message_helptext' =>'This text will display as a heading on my profile page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300006,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Billing Address', 'mofluid_message_value' => 'Billing Address', 'mofluid_message_helptext' =>'This text will display as a heading on my profile page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300007,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'No Default Billing Address Found', 'mofluid_message_value' => 'No Default Billing Address Found', 'mofluid_message_helptext' =>'This text will display on my profile page when no billing address is set to default', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300008,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shipping Address', 'mofluid_message_value' => 'Shipping Address', 'mofluid_message_helptext' =>' This text will appear text for Shipping address', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300009,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'No Default Shipping Address', 'mofluid_message_value' => 'No Default Shipping Address', 'mofluid_message_helptext' =>'This text will display on my profile page when no shipping address is set to default', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300010,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Full Name', 'mofluid_message_value' => 'Full Name', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300011,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'First Name', 'mofluid_message_value' => 'First Name', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300012,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Last Name', 'mofluid_message_value' => 'Last Name', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300013,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Change Account Password', 'mofluid_message_value' => 'Change Account Password', 'mofluid_message_helptext' =>'This text will display on change password screen', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300014,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Old Password', 'mofluid_message_value' => 'Old Password', 'mofluid_message_helptext' =>'This text will display on change password screen', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300015,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'New Password', 'mofluid_message_value' => 'New Password', 'mofluid_message_helptext' =>'This text will display on change password screen', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300016,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Confirm Password', 'mofluid_message_value' => 'Confirm Password', 'mofluid_message_helptext' =>'This text will display on change password screen', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300017,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Address', 'mofluid_message_value' => 'Address', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300018,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Member Since', 'mofluid_message_value' => 'Member Since', 'mofluid_message_helptext' =>'This text will display on my profile page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300019,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Edit Information', 'mofluid_message_value' =>'Edit Information', 'mofluid_message_helptext' =>'This text will display on profile edit page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300020,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Email Address', 'mofluid_message_value' =>'Email Address', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300021,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Contact Number', 'mofluid_message_value' =>'Contact Number', 'mofluid_message_helptext' =>'This text will appear text for contact number field', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300022,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'City', 'mofluid_message_value' =>'City', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300023,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'State', 'mofluid_message_value' =>'State', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300024,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Country', 'mofluid_message_value' =>'Country', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300025,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Zipcode', 'mofluid_message_value' =>'Zipcode', 'mofluid_message_helptext' =>'This text will display on checkout form', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300026,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'You have no Orders', 'mofluid_message_value' =>'You have no Orders', 'mofluid_message_helptext' =>'This text will display on myorder page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300027,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Total Orders', 'mofluid_message_value' =>'You have total {{totalorder}} Orders', 'mofluid_message_helptext' =>'This text will display on myorder page. {{totalorder}} will replace with total number of orders. ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300028,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Order', 'mofluid_message_value' =>'Order', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300029,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Orders', 'mofluid_message_value' =>'Orders', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);    
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300030,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Order Id', 'mofluid_message_value' =>'Order Id', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300031,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Status', 'mofluid_message_value' =>'Status', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300032,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Products', 'mofluid_message_value' =>'Products', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300033,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Payment Method', 'mofluid_message_value' =>'Payment Method', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300034,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shipping Method', 'mofluid_message_value' =>'Shipping Method', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300035,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Total Amount', 'mofluid_message_value' =>'Total Amount', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300036,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'SKU', 'mofluid_message_value' =>'SKU', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300037,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Name', 'mofluid_message_value' =>'Name', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300038,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Qty', 'mofluid_message_value' =>'Qty', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	); 
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300039,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Price', 'mofluid_message_value' =>'Price', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300040,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Total', 'mofluid_message_value' =>'Total', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300041,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Item', 'mofluid_message_value' =>'Item', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300042,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shipping Amount', 'mofluid_message_value' =>'Shipping Amount', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300043,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Grand Total', 'mofluid_message_value' =>'Grand Total', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300044,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Search', 'mofluid_message_value' =>'Search by Name', 'mofluid_message_helptext' =>'This text will appear as place holder for search field', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300045,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Search Result Text', 'mofluid_message_value' =>'Showing search result for {{serachstring}}', 'mofluid_message_helptext' =>'This text will display on search page. {{searchstring}} will replace with actual search string', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300046,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'No Search result found', 'mofluid_message_value' =>'No such product found', 'mofluid_message_helptext' =>'This text will display on search page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300047,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Position (Sort Type)', 'mofluid_message_value' =>'Position', 'mofluid_message_helptext' =>'This text will display on product listing page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300048,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Name (Sort Type)', 'mofluid_message_value' =>'Name', 'mofluid_message_helptext' =>'This text will display on product listing page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300049,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Price (Sort Type)', 'mofluid_message_value' =>'Price', 'mofluid_message_helptext' =>'This text will display on product listing page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300050,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Product Description', 'mofluid_message_value' =>'Product Description', 'mofluid_message_helptext' =>'This text will display on product detail page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300051,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Description', 'mofluid_message_value' =>'Description', 'mofluid_message_helptext' =>'This text will display on product detail page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300052,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Availability', 'mofluid_message_value' =>'Availability', 'mofluid_message_helptext' =>'This text will display on product detail page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300053,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Product SKU', 'mofluid_message_value' =>'Product SKU', 'mofluid_message_helptext' =>'This text will display on product detail page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300054,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shipping Charge', 'mofluid_message_value' =>'Shipping Charge', 'mofluid_message_helptext' =>'This text will display on cart page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300055,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'In Stock', 'mofluid_message_value' =>'In Stock', 'mofluid_message_helptext' =>'This text will display on product detail page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300056,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Out of Stock', 'mofluid_message_value' =>'Out of Stock', 'mofluid_message_helptext' =>'This text will display on product detail page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300057,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Product Options', 'mofluid_message_value' =>'Product Options', 'mofluid_message_helptext' =>'This text will display on product detail page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300058,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Cart Empty Text', 'mofluid_message_value' =>'The Cart is empty now', 'mofluid_message_helptext' =>'This text will display on cart page when cart is empty.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300059,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Discount Codes', 'mofluid_message_value' =>'Discount Codes', 'mofluid_message_helptext' =>' This text will appear when discount coupon is applied', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300060,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Remove', 'mofluid_message_value' =>'Remove', 'mofluid_message_helptext' =>'This text will display on cart page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300061,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Amount Payable', 'mofluid_message_value' =>'Amount Payable', 'mofluid_message_helptext' =>' This text will appear title text for payment method page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300062,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Sender', 'mofluid_message_value' =>'Sender', 'mofluid_message_helptext' =>'This text will display on checkout page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300063,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Receiver', 'mofluid_message_value' =>'Receiver', 'mofluid_message_helptext' =>'This text will display on checkout page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300064,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Order Success', 'mofluid_message_value' =>'Thank You for placing your order with us. We\'ll do our best to deliver it to below address.', 'mofluid_message_helptext' =>'This text will display on Invoice page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300065,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Coupon Code Text', 'mofluid_message_value' =>'Enter your coupon code if you have one.', 'mofluid_message_helptext' =>'This text will display on cart page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300066,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Checkout Form Heading', 'mofluid_message_value' =>'Please fill the form to complete your order.', 'mofluid_message_helptext' =>'This text will display on checkout page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300067,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Billing & Shipping Address', 'mofluid_message_value' =>'Billing & Shipping Address', 'mofluid_message_helptext' =>'  This text will appear when shipping and billing address are same', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300068,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shipping to different address', 'mofluid_message_value' =>'Shipping to different address', 'mofluid_message_helptext' =>' This text will appear to set different address for billing & shipping address', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300069,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Save to address book.', 'mofluid_message_value' =>'Save to address book.', 'mofluid_message_helptext' =>' This text will appear to save address to address book', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300070,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Authorize.Net Redirect Message', 'mofluid_message_value' =>'Please wait, your order is being processed and you will be redirected to the Authorize.Net website.', 'mofluid_message_helptext' =>'This text will display when user checkout using Authorize.Net Payment method', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300071,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Authorize.Net Auto Redirect Message', 'mofluid_message_value' =>'If you are not automatically redirected to authorize.net within 5 seconds...', 'mofluid_message_helptext' =>'This text will display when user checkout using Authorize.Net Payment method and page is being to auto redirect', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300072,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Click Here', 'mofluid_message_value' =>'Click Here', 'mofluid_message_helptext' =>'General Text required whereever a link is clickable', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300073,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Paypal Redirect Message', 'mofluid_message_value' =>'Please wait, your order is being processed and you will be redirected to the paypal website', 'mofluid_message_helptext' =>'This text will display when user checkout using Paypal Payment method', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300074,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Paypal Auto Redirect Message', 'mofluid_message_value' =>'If you are not automatically redirected to paypal within 5 seconds...', 'mofluid_message_helptext' =>'This text will display when user checkout using Paypal Payment method and page is being to auto redirect', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300075,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Forget password Message', 'mofluid_message_value' =>'Please provide your registered email to retrieve your password', 'mofluid_message_helptext' =>'This text will display on forget password page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300076,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Please enter your username', 'mofluid_message_value' =>'Please enter your username', 'mofluid_message_helptext' =>'This text will display on signup/signin page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300077,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Please enter your password', 'mofluid_message_value' =>'Please enter your password', 'mofluid_message_helptext' =>'This text will display on signup/signin page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300078,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Password', 'mofluid_message_value' =>'Password', 'mofluid_message_helptext' =>'This text will display on signup/signin page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300079,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Payment Information', 'mofluid_message_value' =>'Payment Information', 'mofluid_message_helptext' =>'This text will display on order page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300080,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Image', 'mofluid_message_value' =>'Image', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300081,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Unit Price', 'mofluid_message_value' =>'Unit Price', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300082,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shipping & Handling', 'mofluid_message_value' =>'Shipping & Handling', 'mofluid_message_helptext' =>' This text will appear for shipping amount', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300083,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Discount', 'mofluid_message_value' =>'Discount', 'mofluid_message_helptext' =>'This text will display on order list page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300084,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Terms and Conditions', 'mofluid_message_value' =>'Terms and Conditions', 'mofluid_message_helptext' =>'This text will display on order preview page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300085,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Terms and Conditions Message', 'mofluid_message_value' =>'I agree to the above terms and conditions.', 'mofluid_message_helptext' =>'This text will display on order preview page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300086,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'No Shipping Methods Message', 'mofluid_message_value' =>'Sorry, no quotes are available for this order at this time.', 'mofluid_message_helptext' =>'This text will display on shipping method page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300087,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'No Product Found Text', 'mofluid_message_value' =>'Products will be added soon.', 'mofluid_message_helptext' =>'This text will appear when no product found.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300088,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Discount (coupon)', 'mofluid_message_value' =>'Discount (coupon).', 'mofluid_message_helptext' =>'This text will appear on cart page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300089,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Default', 'mofluid_message_value' =>'Default', 'mofluid_message_helptext' =>'This text will appear instead of default', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300090,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Select', 'mofluid_message_value' =>'Select', 'mofluid_message_helptext' =>'This text will appear with default state of drop down.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300091,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'All Products', 'mofluid_message_value' =>'All Products', 'mofluid_message_helptext' =>'This text will appear inside every category to display all products of that category.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300092,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Tax', 'mofluid_message_value' =>'Tax', 'mofluid_message_helptext' =>'This text will appear on cart, my order and order review page to display tax amount.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300093,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'No Payment Required', 'mofluid_message_value' =>'No Payment Method Required', 'mofluid_message_helptext' =>'This text will appear on payment page, when payment is not required. ex: when order amount is zero.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300094,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Retrive Your Password', 'mofluid_message_value' =>'Retrive Your Password', 'mofluid_message_helptext' =>'This text will displayed at Retrive Your Password Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300095,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Check Email', 'mofluid_message_value' =>'Please check your Email address.', 'mofluid_message_helptext' =>'This text will appear when forget password or any link is sent to the registered mail.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300096,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Sign Up ', 'mofluid_message_value' =>'Sign Up ', 'mofluid_message_helptext' =>'This text will appear at Sign Up button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300097,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'My Cart ', 'mofluid_message_value' =>'My Cart ', 'mofluid_message_helptext' =>'This text will appear title text for Cart page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300098,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Checkout ', 'mofluid_message_value' =>'Checkout', 'mofluid_message_helptext' =>'This text will appear at Checkout Button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300099,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Continue Shopping ', 'mofluid_message_value' =>'Continue Shopping', 'mofluid_message_helptext' =>'This text will appear at Continue Shopping Button over whole app', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300100,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Add address', 'mofluid_message_value' =>'Add address', 'mofluid_message_helptext' =>' This text will appear title text for Billing address page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300101,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Submit', 'mofluid_message_value' =>'Submit', 'mofluid_message_helptext' =>'  This text will appear to submit address ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300102,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Order Summary', 'mofluid_message_value' =>'Order Summary', 'mofluid_message_helptext' =>'   This text will appear for order summary', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300103,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Apply Coupon', 'mofluid_message_value' =>'Apply Coupon', 'mofluid_message_helptext' =>'    This text will appear for discount code button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300104,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Cancel Coupon', 'mofluid_message_value' =>'Cancel Coupon', 'mofluid_message_helptext' =>'    This text will appear for cancel discount code button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300105,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Proceed', 'mofluid_message_value' =>'Proceed', 'mofluid_message_helptext' =>'     This text will appear to proceed user to payment page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300106,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Payment Mode', 'mofluid_message_value' =>'Please select mode of payment.', 'mofluid_message_helptext' =>' This message will appear when user checkout without selecting any payment mode.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300107,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Pay', 'mofluid_message_value' =>'Pay', 'mofluid_message_helptext' =>'This text will appear to user to confirm payment', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300108,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Thank You Message for COD mode', 'mofluid_message_value' =>'Thank You for placing order with us. We will do our best to deliver it to below address.', 'mofluid_message_helptext' =>' This text will appear as thank you message for COD orders', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300109,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Order Amount', 'mofluid_message_value' =>'Order Amount', 'mofluid_message_helptext' =>'  This text will appear text for Order Amount', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300110,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'My Account', 'mofluid_message_value' =>'My Account', 'mofluid_message_helptext' =>'   This text will appear title text for My Account page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300111,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Edit', 'mofluid_message_value' =>'Edit', 'mofluid_message_helptext' =>'    This text will appear text for Edit button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300112,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Edit Address', 'mofluid_message_value' =>'Edit Address', 'mofluid_message_helptext' =>'     This text will appear title text for Edit address page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300113,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Save', 'mofluid_message_value' =>'Save', 'mofluid_message_helptext' =>' This text will appear on save button of edit address page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300114,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Back', 'mofluid_message_value' =>'Back', 'mofluid_message_helptext' =>' This text will appear on back button of edit address page', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300115,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'View Details', 'mofluid_message_value' =>'View Order', 'mofluid_message_helptext' =>'  This text will appear to view order details ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300116,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Load More', 'mofluid_message_value' =>'Load More', 'mofluid_message_helptext' =>'  This text will appear to view all order placed by user  ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300117,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Order Details', 'mofluid_message_value' =>'Order Details', 'mofluid_message_helptext' =>'  This text will appear as title text for Order detail page ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300118,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Order Date', 'mofluid_message_value' =>'Order Date', 'mofluid_message_helptext' =>'  This text will appear to display date of placing Order ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300119,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Items in your Order', 'mofluid_message_value' =>'Items in your Order', 'mofluid_message_helptext' =>'   This text will appear as title text for items in cart ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300120,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Grand Total ( Items)', 'mofluid_message_value' =>'Grand Total ( # Count Items)', 'mofluid_message_helptext' =>'This text will appear to display grand total with number of items in cart ', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300121,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'View All Orders', 'mofluid_message_value' =>'View All Orders', 'mofluid_message_helptext' =>'This text will appear to display all order palced by user', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300122,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Shop by Category', 'mofluid_message_value' =>'Shop by Category', 'mofluid_message_helptext' =>'This text will appear for Category heading', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300123,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Featured Products', 'mofluid_message_value' =>'Featured Products', 'mofluid_message_helptext' =>'This text will appear as title for Featured products', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300124,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Name (Sort Type A- Z )', 'mofluid_message_value' =>'Name ( A - Z )', 'mofluid_message_helptext' =>'This text will appear to add text for Sorting type', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300125,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Name (Sort Type Z- A )', 'mofluid_message_value' =>'Name ( Z - A )', 'mofluid_message_helptext' =>'This text will appear to add text for Sorting type', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300126,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Price (Sort Type High - Low)', 'mofluid_message_value' =>'Price ( High - Low )', 'mofluid_message_helptext' =>'This text will appear to add text for Sorting type', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300127,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Price (Sort Type Low - High)', 'mofluid_message_value' =>'Price ( Low - High )', 'mofluid_message_helptext' =>'This text will appear to add text for Sorting type', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300128,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Close', 'mofluid_message_value' =>'Close', 'mofluid_message_helptext' =>' This text will displayed to close popup', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300129,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Try Again', 'mofluid_message_value' =>'There was a temporary error please try again later.', 'mofluid_message_helptext' =>' This text will appear when the app can not recieve the data from the api due to error or site down.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300130,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Internet Connection problem', 'mofluid_message_value' =>'Unable to connect to internet', 'mofluid_message_helptext' =>' This text will appear when app stop responsing due to internet connection error', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300131,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Show Password', 'mofluid_message_value' =>'Show Password', 'mofluid_message_helptext' =>' This text will appear below on password field', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300132,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Invalid Quantity', 'mofluid_message_value' =>'Please provide numeric value for the product quantity.', 'mofluid_message_helptext' =>' This text will appear when invalid quantity value is entered on cart page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300133,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'New User? Sign Up', 'mofluid_message_value' =>'New User? Sign Up', 'mofluid_message_helptext' =>' This text will appear on signup page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300134,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Already have an account?', 'mofluid_message_value' =>'Already have an account?', 'mofluid_message_helptext' =>' This text will appear on signup page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300135,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Incorrect Old Password', 'mofluid_message_value' =>'Incorrect Old Password', 'mofluid_message_helptext' =>' This text will appear on change password page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300136,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Reorder', 'mofluid_message_value' =>'ReOrder', 'mofluid_message_helptext' =>' This text will appear on cart page for Reorder button.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300137,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Payment Canceled', 'mofluid_message_value' =>'Sorry your payment has been canceled.', 'mofluid_message_helptext' =>' This text will appear when payment has been canceled.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300138,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Message for invalid payment amount', 'mofluid_message_value' =>'Invalid amount for payment processing.', 'mofluid_message_helptext' =>' This text will appear on payment page when invalid amount.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);

		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300139,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'including_tax', 'mofluid_message_value' =>'Inc. Tax', 'mofluid_message_helptext' =>' This text will appear on product detail.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300140,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'excluding_tax', 'mofluid_message_value' =>'Ex. Tax', 'mofluid_message_helptext' =>' This text will appear on product detail.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300141,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'checkout header text', 'mofluid_message_value' =>'CHECKOUT AS A GUEST OR REGISTER OR LOGIN', 'mofluid_message_helptext' =>'This text will appear on checkout page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300142,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'login checkout text', 'mofluid_message_value' =>'Checkout As Guest', 'mofluid_message_helptext' =>' This text will appear on checkout page for radio button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300143,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'register login', 'mofluid_message_value' =>'Register Or Login', 'mofluid_message_helptext' =>' This text will appear on checkout page for radio button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300144,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'google login text', 'mofluid_message_value' =>'Login With Google', 'mofluid_message_helptext' =>' This text will appear on checkout page for radio button', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300145,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'New Products', 'mofluid_message_value' =>'New Products', 'mofluid_message_helptext' =>' This text will appear on home page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300146,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Push Notification', 'mofluid_message_value' =>'Push Notification', 'mofluid_message_helptext' =>' This text will appear on home page push notification.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300147,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'aboutus', 'mofluid_message_value' =>'About Us', 'mofluid_message_helptext' =>' This text will appear in sidebar.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300148,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'termcondition', 'mofluid_message_value' =>'Term & Condition', 'mofluid_message_helptext' =>'This text will appear in sidebar.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300149,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'privacypolicy', 'mofluid_message_value' =>'Privacy Policy', 'mofluid_message_helptext' =>' This text will appear in sidebar.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300150,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'returnpolicy', 'mofluid_message_value' =>'Return Policy', 'mofluid_message_helptext' =>' This text will appear in sidebar.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);

		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300151,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'Starting At', 'mofluid_message_value' =>'Starting At', 'mofluid_message_helptext' =>' This text will appear in grouped Products.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);

		$connection->insertForce(
			$installer->getTable('mofluid_themes_messages'),
			[ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0, 'mofluid_message_id' =>2300152,  'mofluid_message_type' => 'text', 'mofluid_message_label' =>'No Grouped Product', 'mofluid_message_value' =>'No options of this product are available', 'mofluid_message_helptext' =>' This text will appear in grouped Products Details page.', 'mofluid_message_helplink' => '', 'mofluid_message_isrequired' =>1 ]	);
			
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200001, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Featured Product List', 'mofluid_color_value' =>'#ffffff', 'mofluid_color_helptext' =>'This color will appear  as background color with listing of featured product.', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
				
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200002, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Slider Menu', 'mofluid_color_value' =>'#ffffff', 'mofluid_color_helptext' =>'This color will appear as background color of the slider menu panel.', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200003, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Category Heading', 'mofluid_color_value' =>'#f8f8f8', 'mofluid_color_helptext' =>'This color will appear as background color with Category and Featured product heading.', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200004, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Sub Category Heading', 'mofluid_color_value' =>'#ffffff', 'mofluid_color_helptext' =>'This color will appear as background color with Sub Category Heading.', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200005, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Default Background', 'mofluid_color_value' =>'#ffffff', 'mofluid_color_helptext' =>'This color will appear as default background color of app', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200006, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Primary button', 'mofluid_color_value' =>'#ff8a00', 'mofluid_color_helptext' =>'This color will appear as background coor for  all primary buttons such as Add to cart , Checkout , Login , Sign Up, Save & Continue , Proceed , Pay , Save , Retrieve Password , Apply Coupon', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200007, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Secondary button', 'mofluid_color_value' =>'#747474', 'mofluid_color_helptext' =>'This color will appear as background color for all Secondary buttons such as Get Price , Continue Shopping , Cancel , Back , View Details , View All Orders , Load More .', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200008, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Application Header', 'mofluid_color_value' =>'#ffffff', 'mofluid_color_helptext' =>'This color will appear as background color of main header of the mobile app', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200009, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Product Image Container', 'mofluid_color_value' =>'#ffffff', 'mofluid_color_helptext' =>'This color will used as background color of a container having product images.', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200010, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Discount Coupon Container', 'mofluid_color_value' =>'#fef7bf', 'mofluid_color_helptext' =>'This color will used as background color for discount coupon container', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200011, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Footer Background Color', 'mofluid_color_value' =>'#ff8b01', 'mofluid_color_helptext' =>'This color will used as background color for ', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
		$connection->insertForce( $installer->getTable('mofluid_themes_colors'), [ 'mofluid_theme_id' => 2, 'mofluid_store_id' => 0 , 'mofluid_color_id' =>2200012, 'mofluid_color_type' =>'background', 'mofluid_color_label' =>'Secondary header background color', 'mofluid_color_value' =>'#ff8b01', 'mofluid_color_helptext' =>'This color will used as background color for search section bar background color ', 'mofluid_color_helplink' => '', 'mofluid_color_isrequired' =>1 ]  );
		
        $installer->endSetup();

    }
}
