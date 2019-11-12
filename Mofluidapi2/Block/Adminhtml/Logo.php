<?php
namespace Mofluid\Mofluidapi2\Block\Adminhtml;
class Logo extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
		
        $this->_controller = 'adminhtml_logo';/*block grid.php directory*/
        $this->_blockGroup = 'Mofluid_Mofluidapi2';
        $this->_headerText = __('Logo');
        $this->_addButtonLabel = __('Add New Entry'); 
        $this->_removeButton('add'); 
        parent::_construct();
		
    }
}
