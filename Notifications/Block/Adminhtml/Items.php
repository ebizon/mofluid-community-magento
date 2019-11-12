<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Notifications\Block\Adminhtml;

class Items extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		$this->_controller = 'items';
		$this->_headerText = __('Items');
		$this->_addButtonLabel = __('Add New Item');
		$this->_removeButton('delete');
		$this->_removeButton('save');
		$this->_removeButton('back');
        parent::__construct(); 
    }
}
