<?php

namespace Mofluid\Mofluidapi2\Block\Adminhtml\Banner\Helper\Renderer;

/**
 * Image renderer.
 * @category Magestore
 * @package  Magestore_Bannerslider
 * @module   Bannerslider
 * @author   Magestore Developer
 */
class Imageaction extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * banner factory.
     *
     * @var \Mofluid\Mofluidapi2\Model\BannerFactory
     */
    protected $_bannerFactory;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Context              $context
     * @param \Magento\Store\Model\StoreManagerInterface  $storeManager
     * @param \Mofluid\Mofluidapi2\Model\BannerFactory $bannerFactory
     * @param array                                       $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product $productData,
        \Magento\Catalog\Model\Category $categoryData,
        \Mofluid\Mofluidapi2\Model\BannerFactory $bannerFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;
        $this->_product = $productData;
        $this->_category = $categoryData;
        $this->_bannerFactory = $bannerFactory;
    }

    /**
     * Render action.
     *
     * @param \Magento\Framework\DataObject $row
     *
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $banner = $this->_bannerFactory->create()->load($row->getId());
        $mofluid_banner_action = "No Action";
        if($banner->getMofluidImageAction()!=''){
			$mofluid_banner_action_data = json_decode(base64_decode($banner->getMofluidImageAction()));
			if($mofluid_banner_action_data->base == "product") {
				$_product = $this->_product->load($mofluid_banner_action_data->id);
				$target_action_name = $_product->getName().' ('.$_product->getSku().')';
			}else {
				 $_category = $this->_category->load($mofluid_banner_action_data->id);
				$target_action_name = $_category->getName();
			}
			$mofluid_banner_action = ucfirst($mofluid_banner_action_data->action).'  '.ucfirst($mofluid_banner_action_data->base).' "'.trim($target_action_name).'"';
		}
        
        return $mofluid_banner_action;
    }
}
