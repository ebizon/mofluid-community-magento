<?php
namespace Mofluid\Mofluidapi2\Block\Adminhtml\Logo\Edit\Tab;
class Cms extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
        \Magento\Cms\Model\Page $pagedata,
        array $data = array()
    ) { 
		$this->_page = $pagedata;
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
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		    $collection = $objectManager->get('\Magento\Cms\Model\PageFactory')->create()->getCollection();
		    
		   
		    $collection->addFieldToFilter('is_active' , \Magento\Cms\Model\Page::STATUS_ENABLED);
		    $cmspage_array[]=array("label"=>"Select Page", "value"=>"0");
        foreach($collection as $tdata)
        {
        $cmspage_array[]=array("label"=>$tdata->getTitle()." (Id:".$tdata->getId().")", "value"=>$tdata->getId());
        }
        $model = $this->_coreRegistry->registry('mofluidapi2_logo');
        $bannerId = $this->getRequest()->getParam('id');
        
		$isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');
        
        $htmlIdPrefix = $form->getHtmlIdPrefix();
		
        $fieldset = $form->addFieldset('base_fieldset', array('legend' => __('CMS Page')));
       
        $fieldset->addField(
            'cms_pages',
            'select',
            array(
                'name' => 'cms_pages',
                'label' => __('CMS Page Status'),
                'title' => __('CMS Page Status'),
                'values' => array('0' => 'Disable','1' => 'Enable'), 
                'required' => true,
            )
        );
        
        $fieldset->addField(
            'about_us',
            'select',
            array(
                'name' => 'about_us',
                'values'    => $cmspage_array,
                'label' => __('About Us Page Id'),
                'title' => __('About Us Page Id'),
                'required' => true,
            )
        );
        $fieldset->addField(
            'term_condition',
            'select',
            array(
                'name' => 'term_condition',
                'values'    => $cmspage_array,
                'label' => __('Term Condition Page Id'),
                'title' => __('Term Condition Page Id'),
                'required' => true,
            )
        );
        $fieldset->addField(
            'privacy_policy',
            'select',
            array(
                'name' => 'privacy_policy',
                'values'    => $cmspage_array,
                'label' => __('Privacy Policy Page Id'),
                'title' => __('Privacy Policy Page Id'),
                'required' => true,
            )
        );
        $fieldset->addField(
            'return_privacy_policy',
            'select',
            array(
                'name' => 'return_privacy_policy',
                'values'    => $cmspage_array,
                'label' => __('Return Privacy Policy Page Id'),
                'title' => __('Return Privacy Policy Page Id'),
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
        return __('CMS Pages');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('CMS Pages');
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
