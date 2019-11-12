<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Banner;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		$id = $this->getRequest()->getParam('mofluid_image_id');
		//echo $id; die('dd');
		try {
				$banner = $this->_objectManager->get('Mofluid\Mofluidapi2\Model\Banner')->load($id);
				$banner->delete();
                $this->messageManager->addSuccess(
                    __('Delete successfully !')
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
	    $this->_redirect('*/*/');
    }
}
