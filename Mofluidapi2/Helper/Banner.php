<?php
namespace Mofluid\Mofluidapi2\Helper;

class Banner extends \Magento\Framework\App\Helper\AbstractHelper
{
	public function __construct(

        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
		$this->_productCollectionFactory  = $productCollectionFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);

    }

	public function getCategoriesArray()
    {
        $categoriesArray = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('name')
            ->addAttributeToSort('path', 'asc')
            ->load()
            ->toArray();

        $categories = array();
        foreach ($categoriesArray as $categoryId => $category) {
            if (isset($category['name']) && isset($category['level'])) {
                $categories[] = array(
                    'label' => $category['name'],
                    'level' => $category['level'],
                    'value' => $categoryId,
                );
            }
        }

        return $categories;
    }

    public function getProductsArray()
    {
        $productsArray = $this->_productCollectionFactory->create()
             ->addAttributeToSelect('name')
             ->load()
             ->toArray();

        $products = array();
        foreach ($productsArray as $productId => $product) {
			$products[] = array(
				'label' => $product['name'],
				'level' => $product['name'],
				'value' => $productId,
			);
        }

        return $products;
    }

    public function getStoresArray()
    {
        $storesArray = $this->_storeManager->getStores($withDefault = false);

        $stores = array('0' => "All Store View");
        foreach ($storesArray as $storeId => $store) {
			$stores[] = array(
				'label' => $store['name'],
				'level' => $store['name'],
				'value' => $storeId,
			);
        }

        return $stores;
    }
    public function getGridStoresArray()
    {
        $storesArray = $this->_storeManager->getStores($withDefault = false);

        $stores = array('0' => "All Store View");
        foreach ($storesArray as $storeId => $store) {
			$stores[$storeId] = $store['name'];
        }
        return $stores;
    }

}
