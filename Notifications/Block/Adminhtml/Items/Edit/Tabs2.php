<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Notifications\Block\Adminhtml\Items\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('mofluid_notifications_items_edit_tabs2');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Apple Notification2'));
    }
}
