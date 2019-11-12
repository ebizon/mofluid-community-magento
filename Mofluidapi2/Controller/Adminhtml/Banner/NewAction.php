<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Banner;
use Magento\Backend\App\Action;
class NewAction extends \Magento\Backend\App\Action
{
     public function execute()
    {
		$this->_forward('edit');
    }
}
