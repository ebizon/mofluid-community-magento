<?php
namespace Mofluid\Mofluidapi2\Block\Adminhtml\Logo\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_logo_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Configuration'));
    }
}
