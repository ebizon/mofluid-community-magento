<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

// @codingStandardsIgnoreFile

namespace Mofluid\Notifications\Block\Adminhtml\Items\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;



class Main extends Generic implements TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('SetUp Push Notification');
    }
	
    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Item Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_mofluid_notifications_items');
        $id = $this->getRequest()->getParam('id');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Set Up  Notification')]);
        if ($model->getId()) { 
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
       if($id == 1){
        $fieldset->addField(
			'pemfile',
			'file',
			[
				'name' => 'pemfile',
				'label' => __('Upload Certificate with Private Key (.pem file): '),
				'required' => true
			]
		);
        
         $fieldset->addField(
            'passphrase',
            'text',
            [            
				'name' => 'passphrase', 
				'label' => __('Passphrase :'),
				'title' => __('Passphrase '), 'required' => true
            
            ]
        );
          $fieldset->addField(
            'message',
            'text',
            [
				'name' => 'message',
				'label' => __('Message :'),
				'title' => __('message '), 'required' => true
            ]
        );
	   
	 }
	 if($id ==2){
		 	$fieldset->addField(
				'gcm_id',
				'text',
				array(
					'name' => 'gcm_id',
					'label' => __('gcm_id'),
					'title' => __('gcm_id'),
					'required' => true,
				)
			);
				$fieldset->addField(
				'gcm_key',
				'text',
				array(
					'name' => 'gcm_key',
					'label' => __('gcm_key'),
					'title' => __('gcm_key'),
					'required' => true,
				)
			);
		      $fieldset->addField(
            'message',
            'text',
            [
				'name' => 'message',
				'label' => __('Message :'),
				'title' => __('message '), 'required' => true
            ]
        );
        
	 }
        
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
