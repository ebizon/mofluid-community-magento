<?php
namespace Mofluid\Mofluidapi2\Block\Adminhtml\Banner;


class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory]
     */
    protected $_setsFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_type;

    /**
     * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_status;
	protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_visibility;

    /**
     * @var \Magento\Store\Model\WebsiteFactory
     */
    protected $_websiteFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Store\Model\WebsiteFactory $websiteFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory $setsFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Model\Product\Type $type
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $status
     * @param \Magento\Catalog\Model\Product\Visibility $visibility
     * @param \Magento\Framework\Module\Manager $moduleManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Store\Model\WebsiteFactory $websiteFactory,
		\Mofluid\Mofluidapi2\Model\ResourceModel\Banner\Collection $collectionFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Mofluid\Mofluidapi2\Helper\Banner $bannerHelper,
        array $data = []
    ) {

		$this->_collectionFactory = $collectionFactory;
        $this->_websiteFactory = $websiteFactory;
        $this->moduleManager = $moduleManager;
        $this->_bannerHelper = $bannerHelper;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('productGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);

    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
		try{


			$collection =$this->_collectionFactory->addFieldToFilter('mofluid_image_type', array('eq' => 'banner'))->load();



			$this->setCollection($collection);

			parent::_prepareCollection();

			return $this;
		}
		catch(\Exception $e)
		{
			$echo= $e->getMessage();
      $this->getResponse()->setBody($echo);
		}
    }

    /**
     * @param \Magento\Backend\Block\Widget\Grid\Column $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                    'websites',
                    'catalog_product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left'
                );
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'mofluid_image_id',
            [
                'header' => __('Banner Id'),
                'type' => 'number',
                'index' => 'mofluid_image_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn(
            'mofluid_store_id',
            [
                'header' => __('Store'),
                'index' => 'mofluid_store_id',
                'class' => 'mofluid_store_id',
                'type'=>'options',
                'options' => $this->_bannerHelper->getGridStoresArray()
            ]
        );
		$this->addColumn(
            'mofluid_image_value',
            [
                'header' => __('Banner Image'),
                'index' => 'mofluid_image_value',
                'class' => 'mofluid_image_value',
                'width' => '50px',
                'filter' => false,
                'renderer' => 'Mofluid\Mofluidapi2\Block\Adminhtml\Banner\Helper\Renderer\Image'
            ]
        );
		$this->addColumn(
            'mofluid_image_sort_order',
            [
                'header' => __('Sort Order'),
                'index' => 'mofluid_image_sort_order',
                'class' => 'mofluid_image_sort_order'
            ]
        );
		$this->addColumn(
            'mofluid_image_isdefault',
            [
                'header' => __('Default'),
                'index' => 'mofluid_image_isdefault',
                'class' => 'mofluid_image_isdefault',
                'type'=>'options',
                'options' => array('1' => 'Yes', '0' => 'No')
            ]
        );
		$this->addColumn(
            'mofluid_image_action',
            [
                'header' => __('Frontend Action'),
                'index' => 'mofluid_image_action',
                'class' => 'mofluid_image_action',
                'renderer' => 'Mofluid\Mofluidapi2\Block\Adminhtml\Banner\Helper\Renderer\Imageaction'
            ]
        );
        $this->addColumn(
            'edit',
            [
                'header' => __('Action'),
                'type' => 'action',
                'getter' => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => ['base' => '*/*/edit'],
                        'field' => 'id',
                    ],
                ],
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
		/*{{CedAddGridColumn}}*/

        $block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }

        return parent::_prepareColumns();
    }

     /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label' => __('Delete'),
                'url' => $this->getUrl('mofluidapi2/*/massDelete'),
                'confirm' => __('Are you sure?')
            )
        );
        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('mofluidapi2/*/grid', ['_current' => true]);
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Framework\Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            'mofluidapi2/*/edit',
            ['store' => $this->getRequest()->getParam('store'), 'id' => $row->getId()]
        );
    }
}
