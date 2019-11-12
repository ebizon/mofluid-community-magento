<?php
namespace Mofluid\Mofluidapi2\Block\Adminhtml\Logo\Edit\Tab;
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
        \Mofluid\Mofluidapi2\Model\Logo $Banner,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mofluid\Mofluidapi2\Helper\Banner $bannerHelper,
        array $data = array()
    ) {
		$this->_banner = $Banner;
        $this->_systemStore = $systemStore;
        $this->_storeManager = $storeManager;
        $this->_bannerHelper = $bannerHelper;
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
        $model = $this->_coreRegistry->registry('mofluidapi2_logo');
        $bannerId = $this->getRequest()->getParam('id');
        
		$isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');
        
        $htmlIdPrefix = $form->getHtmlIdPrefix();
		
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('General')));
       
        if (isset($bannerId)) {
            $fieldset->addField('mofluid_image_id', 'hidden', array('name' => 'mofluid_image_id'));
        }
		$fieldset->addField(
            'mofluid_image_value',
            'image',
            array(
                'name' => 'mofluid_image_value',
                'label' => __('Banner Image'),
                'title' => __('Banner Image'),
                'note' => 'Upload Image to display as banner image in app (Recommended Size : 1024x500px). Allow image type: jpg, jpeg, gif, png',
                'required' => true,
            )
        );
        
		/*{{CedAddFormField}}*/
        
        if (!$model->getMofluidImageId()) {
            $model->setData('status', $isElementDisabled ? '2' : '1');
        }
        if(isset($bannerId)){
			$bdata = $this->_banner->load($bannerId);
			$form->setValues($bdata->getData());
		}else{
			$form->setValues($model->getData());
		}
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
        return __('LOGO');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Logo');
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
