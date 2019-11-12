<?php
namespace Mofluid\Payment\Block\Adminhtml\Index\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {
		
        parent::_construct();
        $this->setId('checkmodule_index_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Index Information'));
    }
}