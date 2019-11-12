<?php
namespace Mofluid\Mofluidapi2\Block\Adminhtml\Banner\Edit\Tab;
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
        \Mofluid\Mofluidapi2\Model\Banner $Banner,
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
        $model = $this->_coreRegistry->registry('mofluidapi2_banner');
        $bannerId = $this->getRequest()->getParam('id');
        
        //echo "<pre>"; print_r($model->getData()); die('ddd');
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
            'mofluid_image_type',
            'select',
            array(
                'name' => 'mofluid_image_type',
                'label' => __('banner type'),
                'title' => __('banner type'),
                'display' => 'none',
                'values' => array('banner' => 'banner'),
                /*'required' => true,*/
            )
        );
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
		$fieldset->addField(
            'mofluid_image_isdefault',
            'select',
            array(
                'name' => 'mofluid_image_isdefault',
                'label' => __('Default'),
                'title' => __('Default'),
                'values' => array('0' => 'No', '1' => 'Yes'),
                'note'  => __('Select store to display uploaded banner image.When selecting banner style as single, default banner will display for that store.'),
                /*'required' => true,*/
            )
        );
        $fieldset->addField(
            'mofluid_store_id',
            'select',
            array(
                'name' => 'mofluid_store_id',
                'label' => __('Store'),
                'title' => __('Store'),
                'note'  => __('Select store to display uploaded banner image.'),
                'values' => $this->_bannerHelper->getStoresArray(),
                /*'required' => true,*/
            )
        );
		$fieldset->addField(
            'mofluid_image_action',
            'select',
            array(
                'name' => 'mofluid_image_action',
                'label' => __('Frontend Action'),
                'title' => __('Frontend Action'),
                'values' => array('0' => 'No Action', '1' => 'Open Category', '2' => 'Open Product'),
                'note'   => __('Enable Action to link banner with a category or product.'),
            )
        );
        $fieldset->addField(
            'mofluid_image_action_category',
            'select',
            array(
                'name' => 'mofluid_image_action_category',
                'label' => __('Category'),
                'title' => __('Category'),
                'display' => 'none',
                'values' => $this->_bannerHelper->getCategoriesArray(),
            )
        );
        $fieldset->addField(
            'mofluid_image_action_product',
            'select',
            array(
                'name' => 'mofluid_image_action_product',
                'label' => __('Product'),
                'title' => __('Product'),
                'display' => 'none',
                'values' => $this->_bannerHelper->getProductsArray(),
            )
        );
		$fieldset->addField(
            'mofluid_image_sort_order',
            'text',
            array(
                'name' => 'mofluid_image_sort_order',
                'label' => __('Sort Order'),
                'title' => __('Sort Order'),
                /*'required' => true,*/
            )
        );
        
       $this->setChild(
        'form_after',
        $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Form\Element\Dependence'
        )->addFieldMap(
            "{$htmlIdPrefix}mofluid_image_action",
            'mofluid_image_action'
        )
        ->addFieldMap(
            "{$htmlIdPrefix}mofluid_image_action_category",
            'mofluid_image_action_category'
        )
        ->addFieldMap(
            "{$htmlIdPrefix}mofluid_image_action_product",
            'mofluid_image_action_product'
        )
        ->addFieldDependence(
            'mofluid_image_action_category',
            'mofluid_image_action',
            '1'
        )
        ->addFieldDependence(
            'mofluid_image_action_product',
            'mofluid_image_action',
            '2'
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
