<?php
namespace Mofluid\Payment\Block\Adminhtml\Index\Edit\Tab;

use Mofluid\Payment\Model\Status;

class General extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = array()
    ) {
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
		/* @var $model \Magento\Cms\Model\Page */
        $model = $this->_coreRegistry->registry('payment_index');
        $id = $this->getRequest()->getParam('id');
        
		$isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('General')));

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array('name' => 'id'));
        }

		$fieldset->addField(
            'payment_method_title',
            'hidden',
            array(
                'name' => 'payment_method_title',
                'label' => __('title'),
                'title' => __('title'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'payment_method_code',
            'hidden',
            array(
                'name' => 'payment_method_code',
                'label' => __('code'),
                'title' => __('code'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'payment_method_order_code',
            'hidden',
            array(
                'name' => 'payment_method_order_code',
                'label' => __('order code'),
                'title' => __('order code'),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'payment_method_status',
            'select',
            array(
                'name' => 'payment_method_status',
                'label' => __('status'),
                'title' => __('status'),
                'values' => array('0' => 'Disable','1' => 'Enable'), 
                'required' => true,
            )
        );
        if($id == 3 ||$id == 10){
			$fieldset->addField(
				'payment_account_email',
				'text',
				array(
					'name' => 'payment_account_email',
					'label' => __('email'),
					'title' => __('email'),
					'required' => true,
				)
			);
	    }
	    if($id == 2){
				$fieldset->addField(
					'payment_method_account_id',
					'text',
					array(
						'name' => 'payment_method_account_id',
						'label' => __('account id'),
						'title' => __('account id'),
						'required' => true,
					)
				);
	   }
	   if($id == 2 || $id == 3 || $id == 9 || $id == 10){
			$fieldset->addField(
				'payment_method_account_key',
				'text',
				array(
					'name' => 'payment_method_account_key',
					'label' => __('account key'),
					'title' => __('account key'),
					'required' => true,
				)
			);
			$fieldset->addField(
				'payment_method_mode',
				'select',
				array(
					'name' => 'payment_method_mode',
					'label' => __('mode'),
					'title' => __('mode'),
					'values' => array('0' => 'Test','1' => 'Live'), 
					'required' => true,
				)
			);
		}
		if($id == 6){
			$fieldset->addField(
				'payment_environment',
				'select',
				array(
					'name' => 'payment_environment',
					'label' => __('Environment'),
					'title' => __('Environment'),
					'values' => array('sandbox' => 'Sandbox','production' => 'Production'), 
					'required' => true,
				)
			);
			$fieldset->addField(
				'payment_merchant_id',
				'text',
				array(
					'name' => 'payment_merchant_id',
					'label' => __('Merchant_id'),
					'title' => __('Merchant_id'),
					 'required' => true,
				)
			);
			$fieldset->addField(
				'payment_public_key',
				'text',
				array(
					'name' => 'payment_public_key',
					'label' => __('Public_key'),
					'title' => __('Public_key'),
					 'required' => true,
				)
			);
			$fieldset->addField(
				'payment_private_key',
				'text',
				array(
					'name' => 'payment_private_key',
					'label' => __('Private_key'),
					'title' => __('Private_key'),
					 'required' => true,
				)
			);
		}
        if($id == 10){
			
		}
        if (!$model->getId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();   
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('General');
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
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
