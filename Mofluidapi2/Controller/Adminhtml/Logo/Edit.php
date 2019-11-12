<?php
namespace Mofluid\Mofluidapi2\Controller\Adminhtml\Logo;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
		
		
		// 1. Get ID and create model
	$this->getRequest()->setParam('id',2300001);
        $id = $this->getRequest()->getParam('id');
	//print_r($id); die;
        $model = $this->_objectManager->create('Mofluid\Mofluidapi2\Model\Logo');
		
		$registryObject = $this->_objectManager->get('Magento\Framework\Registry');
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This row no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
		$registryObject->register('mofluidapi2_logo', $model);
		$this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
