<?php
namespace Mofluid\Mofluidapi2\Model\Catalog;

class Product extends \Magento\Framework\Model\AbstractModel{

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storemanager;

    /**
     * @var \Magento\Framework\Locale\CurrencyInterface
     */
    protected $locale;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @var \Magento\CatalogInventory\Model\StockRegistry
     */
    protected $stockRegistry;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Downloadable\Model\Product\Type
     */
    protected $downloadableProductData;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Store\Model\StoreManagerInterface $storemanager
     * @param \Magento\Framework\Locale\CurrencyInterface $locale
     * @param \Magento\Framework\App\CacheInterface $cache
     * @param \Magento\CatalogInventory\Model\StockRegistry $stockRegistry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Downloadable\Model\Product\Type $downloadableProductData
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product $product,
        \Magento\Store\Model\StoreManagerInterface $storemanager,
        \Magento\Framework\Locale\CurrencyInterface $locale,
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\CatalogInventory\Model\StockRegistry $stockRegistry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Downloadable\Model\Product\Type $downloadableProductData,
        \Magento\Tax\Api\TaxCalculationInterface $taxcalculation,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\Review\Model\Review $preveiwdata,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProductData,
        \Magento\GroupedProduct\Model\Product\Type\Grouped $groupedProductData
    ) {
		$this->_product = $product;
        $this->stockRegistry = $stockRegistry;
        $this->storemanager = $storemanager;
        $this->scopeConfig = $scopeConfig;
        $this->taxcalculation = $taxcalculation;
        $this->directorydata = $directoryData;
        $this->preveiwdata = $preveiwdata;
        $this->configurable = $configurableProductData;
        $this->grouped = $groupedProductData;
        $this->downloadableProductData = $downloadableProductData;
        parent::__construct(
            $context,
            $registry
        );
    }

    public function getCompleteProductInfo($_store, $product_id, $_currency) {
	    $productDescription = array();

		$product_type = $this->_product->load($product_id)->getTypeId();
		switch($product_type) {
		    case 'simple':
		        $productDescription = $this->getSimpleProductInfo($_store, $product_id, $_currency);
		    break;
		    case 'configurable':
		        $productDescription = $this->getConfigurableProductInfo($_store, $product_id, $_currency);
		    break;
		    case 'bundle':
		        $productDescription = $this->getBundleProductInfo($_store, $product_id, $_currency);
		    break;
		    case 'grouped':
		        $productDescription = $this->getGroupedProductInfo($_store, $product_id, $_currency);
		    break;
		    case 'virtual':
		        $productDescription = $this->getVirtualProductInfo($_store, $product_id, $_currency);
		    break;
		    case 'downloadable':
		        $productDescription = $this->getDownloadableProductInfo($_store, $product_id, $_currency);
		    break;
		    default:
		        $productDescription = $this->getSimpleProductInfo($_store, $product_id, $_currency);
		    break;
		}
		return $productDescription;
	}

	public function getSimpleProductInfo($_store, $product_id, $_currency) {

          $productDescription = array();
          $product = $this->_product->load($product_id);
          $productDescription =  $this->getBasicProductInfo($product, $_currency, $_store);
          $productDescription["description"]['short'] =  base64_encode($product->getShortDescription());
	      $productDescription["description"]['full'] =  base64_encode($product->getDescription());
	      $productDescription["stock"] = $this->getProductStockInfo($product_id);
          $productDescription["custom"]["options"] = $this->getProductCustomOptions($product);
          $productDescription["custom"]["attributes"] = $this->getProductCustomAttributes($product);
          $productDescription["products"]["related"] = $this->getRelatedProducts($product);
//print_r($productDescription);

       	  return $productDescription;
    }

    public function getBasicProductInfo($_product, $_currency, $_store) {
       $productDescription = array();
       $productDescription["pro_status"] =  $_product->getStatus();
       $productDescription["id"] =  $_product->getId();
       $productDescription["sku"] =  $_product->getSku();
       $productDescription["type"] =  $_product->getTypeId();
	   $productDescription["url"] =  $_product->getProductUrl();
	   $productDescription["visibility"] =  $_product->isVisibleInSiteVisibility();//getVisibility();
	   $productDescription["weight"] =  $_product->getWeight();
	   $productDescription["status"] =  $_product->getStatus();
	   $productDescription["category"] =  $_product->getCategoryIds();
	   $productDescription["general"]["name"] = $_product->getName();
	   $productDescription["general"]["sku"] =  $_product->getSku();
	   $productDescription["general"]["weight"] = number_format((float)$_product->getWeight(), 2);
	   $productDescription["price"] =  $this->getProductPrice($_product, $_currency);
	   $productDescription["image"] = $this->getMofluidMediaGalleryImages($_product);
	   $productDescription["reviews"] = $this->getProductReview($_product->getId(), $_store);
	   return $productDescription;
    }

    public function getConfigurableProductInfo($_store, $product_id, $_currency) {

          $productDescription = array();
          $configurable_products = array();
          $custom_attr = array();
          $attribute_dropdown = array();
          $product = $this->_product->load($product_id);
          $simple_product_counter  = 0;
          $productDescription =  $this->getSimpleProductInfo($_store, $product_id, $_currency);
          $simple_collection = $this->configurable->getUsedProductIds($product);
          $id=0;
          foreach($simple_collection as  $key => $simple_product){
			     $tax_type = $this->scopeConfig->getValue('tax/calculation/price_includes_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
				 $a = $this->_product->load($simple_product);
    			 $taxClassId = $a->getData("tax_class_id");
    			 $taxRate = $this->taxcalculation->getDefaultCalculatedRate($taxClassId, null, $_store);
    			 $tax_price = (($taxRate)/100) *  ($a->getPrice());
    			 $fprice=$a->getFinalPrice();
                 $configurable_products[$simple_product_counter] =  $this->getBasicProductInfo($a, $_currency, $_store);
				 if($tax_type==0){
						$configurable_products[$simple_product_counter]['price']['final']=$fprice;
				 }else{
						$configurable_products[$simple_product_counter]['price']['final']=$fprice-$tax_price;
				 }
				 $configurable_products[$simple_product_counter]["stock"] = $this->getProductStockInfoById($a->getId());
				 $attributes =  $a->setPositionOrder('desc')->getAttributes();
				 $custom_attr_count = 0;
				 foreach ($attributes as $attribute) {
              	   if ($attribute->getIsVisibleOnFront()) {
					   $attribute_value = $attribute->getFrontend()->getValue($a);
					   if($attribute_value == null || $attribute_value == "" || $attribute_value == "No") {
						   continue;
					   }else{
							 $product_attribute_set = array();
							 $configProd=$product;
							 $AllowAttributes=$configProd->getTypeInstance(true)->getConfigurableAttributes($configProd);
							 $basePrice = $configProd->getFinalPrice();
							 $optionData = array();
							 $_attribute_id =  $attribute->getId();
							 foreach ($AllowAttributes as $current_attribute) {
								   $productAttribute = $current_attribute->getProductAttribute();
								   $attributeId = $productAttribute->getId();
								   $product_attribute_set["id"] = $productAttribute->getId();
								   $product_attribute_set["code"]  = $productAttribute->getAttributeCode();
								   $product_attribute_set["label"] =  $productAttribute->getFrontend()->getLabel($a);
								   $product_attribute_set["value"] = $productAttribute->getFrontend()->getValue($a);
								   $product_attribute_set["value_id"] = $productAttribute->getSource()->getOptionId($productAttribute->getFrontend()->getValue($a));
								   $product_attribute_set["price"] = $a->getFinalPrice();
							 }
							 $custom_attr["options"][$custom_attr_count] = $product_attribute_set;
							 $attribute_dropdown[$simple_product_counter][]= $product_attribute_set;
							 ++$custom_attr_count;
					   }
			    }

              }
              $configurable_products[$simple_product_counter]["attributes"] = $custom_attr;
              $simple_product_counter++;
          }
          $productDescription["products"]["associated"]["childs"] = $configurable_products;
          $productDescription["products"]["associated"]["attributes"] = $attribute_dropdown;
          return $productDescription;
     }

     public function getBundleProductInfo($_store, $product_id, $_currency) {
        $productDescription = array();
        $product = $this->_product->load($product_id);
        $product_counter = 0;
        $bundled_products =  $product->getTypeInstance(true);
        $productDescription = $this->getBasicProductInfo($product, $_currency, $_store);
        $productDescription["description"]['short'] =  base64_encode($product->getShortDescription());
		$productDescription["description"]['full'] =  base64_encode($product->getDescription());
		$productDescription["stock"] = $this->getProductStockInfo($product_id);
        $productDescription["custom"]["options"] = $this->getProductCustomOptions($product);
        $productDescription["custom"]["attributes"] = $this->getProductCustomAttributes($product);
        $productDescription["products"]["related"] = $this->getRelatedProducts($product);
        $all_bundled_products = $bundled_products->getSelectionsCollection($bundled_products->getOptionsIds($product), $product);
        foreach($all_bundled_products as $current_bundled_product){
			$current_bundled_product =  $this->_product->load($current_bundled_product->getId());
            $productDescription["products"]["bundle"][$product_counter] = $this->getBasicProductInfo($current_bundled_product, $_currency, $_store);
            $product_counter++;
        }
        return $productDescription;
    }

    public function getGroupedProductInfo($_store, $product_id, $_currency) {
    	$productDescription = array();
		$product_counter = 0;
		$stock_counter=0;
		$product = $this->_product->load($product_id);
		$productDescription = $this->getBasicProductInfo($product, $_currency, $_store);
		$productDescription["description"]['short'] =  base64_encode($product->getShortDescription());
		$productDescription["description"]['full'] =  base64_encode($product->getDescription());
		$productDescription["stock"] = $this->getProductStockInfo($product_id);
        $productDescription["custom"]["options"] = $this->getProductCustomOptions($product);
        $productDescription["custom"]["attributes"] = $this->getProductCustomAttributes($product);
        $productDescription["products"]["related"] = $this->getRelatedProducts($product);
		$group_collection = $this->grouped->getAssociatedProducts($product);
        foreach ($group_collection as $group_product) {
			$group_product = $this->_product->load($group_product->getId());
            $productDescription["products"]["grouped"][$product_counter] = $this->getBasicProductInfo($group_product, $_currency, $_store);
            $productDescription["products"]["grouped"][$product_counter]["stock"] = $this->getProductStockInfoById($group_product->getId());
       		if($productDescription["products"]["grouped"][$product_counter]["stock"]["is_in_stock"]>0 && $productDescription["products"]["grouped"][$product_counter]["stock"]["qty"]>0){
       			$stock_counter=1;
       		}
       		$product_counter++;
        }
        if($stock_counter){
        	$productDescription["show_stock_status"]=1;
        }else{
        	$productDescription["show_stock_status"]=0;
        }
        return $productDescription;
    }

    public function getVirtualProductInfo($_store, $product_id, $_currency) {
    	$productDescription = array();
        $productDescription = $this->getSimpleProductInfo($_store, $product_id, $_currency);
        return $productDescription;
    }

    public function getDownloadableProductInfo($_store, $product_id, $_currency) {
        $productDescription = array();
        $product = $this->_product->load($product_id);
        $productDescription = $this->getBasicProductInfo($product, $_currency, $_store);
        $download_product_info = array();
        $link_counter =0;
        $download_product_info["default"]["id"] = $product->getId();
        $download_product_info["default"]["links_title"] = $product->getLinksTitle();
        $download_product_info["default"]["samples_title"] = $product->getSamplesTitle();
        $download_product_info["default"]["links_purchased_separately"] = $product->getLinksPurchasedSeparately();
	   $product_links = $this->downloadableProductData->getLinks($product);
	   $product_samples = $this->downloadableProductData->getSamples($product);
	    foreach ($product_samples as $sample){
		   $download_product_info["samples"][$link_counter]["id"] = $sample->getId();
		   $download_product_info["samples"][$link_counter]["product_id"] = $sample->getProductId();
		   $download_product_info["samples"][$link_counter]["sample"]["url"] = $sample->getSampleUrl();
		   $download_product_info["samples"][$link_counter]["sample"]["file"]= $sample->getSampleFile();
		   $download_product_info["samples"][$link_counter]["sample"]["type"] = $sample->getSampleType();
		   $download_product_info["samples"][$link_counter]["sort_order"] = $sample->getSortOrder();
		   $download_product_info["samples"][$link_counter]["title"] = $sample->getTitle();
		   $download_product_info["samples"][$link_counter]["default_title"] = $sample->getDefaultTitle();
		   $download_product_info["samples"][$link_counter]["store_title"] = $sample->getStoreTitle();
		   $link_counter++;
	    }
	    $link_counter = 0;
	    foreach ($product_links as $link){
		   $download_product_info["links"][$link_counter]["id"] = $link->getId();
		   $download_product_info["links"][$link_counter]["product_id"] = $link->getProductId();
		   $download_product_info["links"][$link_counter]["number_of_downloads"] = $link->getNumberOfDownloads();
		   $download_product_info["links"][$link_counter]["is_shareable"] = $link->getIsShareable();
		   $download_product_info["links"][$link_counter]["sort_order"] = $link->getSortOrder();
		   $download_product_info["links"][$link_counter]["title"] = $link->getTitle();
		   $download_product_info["links"][$link_counter]["default_title"] = $link->getDefaultTitle();
		   $download_product_info["links"][$link_counter]["store_title"] = $link->getStoreTitle();
		   $download_product_info["links"][$link_counter]["link"]["url"] = $link->getLinkUrl();
		   $download_product_info["links"][$link_counter]["link"]["file"] = $link->getLinkFile();
		   $download_product_info["links"][$link_counter]["link"]["type"] = $link->getLinkType();
		   $download_product_info["links"][$link_counter]["sample"]["url"] = $link->getSampleUrl();
		   $download_product_info["links"][$link_counter]["sample"]["file"] = $link->getSampleFile();
		   $download_product_info["links"][$link_counter]["sample"]["type"] = $link->getSampleType();
		   $download_product_info["links"][$link_counter]["price"]["regular"] = $link->getPrice();
		   $download_product_info["links"][$link_counter]["price"]["default"] = $link->getDefaultPrice();
		   $download_product_info["links"][$link_counter]["price"]["website"] = $link->getWebsitePrice();
		   $link_counter++;
	    }
	    $productDescription["products"]["download"]["info"] = $download_product_info;
	    return $productDescription;
     }

    public function getProductStockInfo($id) {
         $stock_data = array();
         $stock_product = $this->stockRegistry->getStockItem($id);;
         $stock_data = $stock_product->getData();
         return $stock_data;
	}

	public function getProductCustomOptions($product) {
         //Get Product Custom Options
	    $has_custom_option = 0;
	    $all_custom_option_array = array();
	    $basecurrencycode = $this->storemanager->getStore()->getBaseCurrencyCode();
	     $this->_product = $product;
         $product_custom_options =  $this->_product->getOptions();
         $optStr = ""; $inc=0;
           foreach($product_custom_options as $optionKey => $optionVal) {
              $has_custom_option = 1;
              $all_custom_option_array[$inc]['all'] = $optionVal->getData();
              $all_custom_option_array[$inc]['custom_option_name']= $all_custom_option_array[$inc]['all']["default_title"];
              $all_custom_option_array[$inc]['custom_option_id']=$all_custom_option_array[$inc]['all']["option_id"];
              $all_custom_option_array[$inc]['custom_option_is_required']=$all_custom_option_array[$inc]['all']["is_require"];
              $all_custom_option_array[$inc]['custom_option_type']=$all_custom_option_array[$inc]['all']["type"];
              $all_custom_option_array[$inc]['sort_order'] = $all_custom_option_array[$inc]['all']["sort_order"];
              if($all_custom_option_array[$inc]['all']['default_price_type'] == "percent") {
                  $all_custom_option_array[$inc]['all']['price'] = number_format((($this->_product->getFinalPrice()*round($all_custom_option_array[$inc]['all']['price']*10,2)/10)/100),2);
              }
              else {
                  $all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'],2);
              }
              $inner_inc =0;
		    foreach($optionVal->getValues() as $valuesKey => $valuesVal) {
                 $options_values = $valuesVal->getData();
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id'] = $options_values["option_type_id"];
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $options_values["title"];
                 $defaultcustomprice = str_replace(",","", $options_values["price"]);
	  	 	  $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = $defaultcustomprice;
			  $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $options_values["price_type"];
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku'] = $options_values["sku"];
                 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $options_values["sort_order"];
                 if($options_values["price_type"] == "percent") {
			      $defaultcustomprice = $this->_product->getFinalPrice(); //$valuesVal->getPrice();
				 $customproductprice =$defaultcustomprice;
				 $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",","", round((floatval($customproductprice)  * floatval(round(floatval($options_values["price"]),1))/100),2));
                 }
                 $inner_inc++;
              }
          $inc++;
        }
        $custom_options_results = array();
        $custom_options_results["status"] = $has_custom_option;
        if($has_custom_option) {
            $custom_options_results["data"] = $all_custom_option_array;
        }
        return  $custom_options_results;
     }
     public function getProductCustomAttributes($product) {
	    $custom_attr = array();
		$attributes = $product->getAttributes();
		$custom_attr_count = 0;
		foreach ($attributes as $attribute) {
			if ($attribute->getIsVisibleOnFront()) {
					$attributeCode = $attribute->getAttributeCode();
					$label = $attribute->getFrontend()->getLabel($product);
					$value = $attribute->getFrontend()->getValue($product);
					$custom_attr["data"][$custom_attr_count]["code"]  = $attributeCode;
					$custom_attr["data"][$custom_attr_count]["label"] = $label;
					$custom_attr["data"][$custom_attr_count]["value"] = $value;
					++$custom_attr_count;
			}
		}
		$custom_attr["total"] = $custom_attr_count;
		return  $custom_attr;
	}

	public function getRelatedProducts($product) {
		// Get all related product ids of $product.
		$relatedproducts = array();
		$results = array();
		$incr = 0;
		$allRelatedProductIds = $product->getRelatedProductIds();
		foreach ($allRelatedProductIds as $id) {
			$relatedproducts[$incr] =  $this->getBasicProductInfo($id);
            	$incr++;
        }
        if($incr) {
        	 $results["status"] = 1;
         	 $results["all"] = $relatedproducts;
        }
        else {
         	 $results["status"] = 0;
        }
        return $results;
	}
	public function getProductPrice($_product, $_currency) {
		try{
			$prices = array();
			$tax_type = $this->scopeConfig->getValue('tax/calculation/price_includes_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			$taxClassId = $_product->getTaxClassId();
			$taxRate = $this->taxcalculation->getDefaultCalculatedRate($taxClassId, null, null);
			$tax_price_regular = (($taxRate)/100) *  ($this->getFormattedProductPrice($_product->getPrice(), $_currency));
			if($tax_type==0){
				$prices["regular"] = $this->getFormattedProductPrice($_product->getPrice(), $_currency);
			}else{
				$prices["regular"] = $this->getFormattedProductPrice($_product->getPrice(), $_currency)-$tax_price_regular;
			}
			if($_product->getTypeId()=='grouped'){
				if($prices["regular"] <= 0) {
					$tax_price_regular_1 = (($taxRate)/100) *  ($this->prepareGroupedProductPrice($_product, $_currency));
					if($tax_type==0){
						$prices["regular"] = $this->prepareGroupedProductPrice($_product, $_currency);
					}else{
					$prices["regular"] = $this->prepareGroupedProductPrice($_product, $_currency)- $tax_price_regular_1;
					}
				}
		    }
			$tax_price_final = (($taxRate)/100) *  ($this->getFormattedProductPrice($_product->getFinalPrice(), $_currency));
			if($tax_type==0){
				$prices["final"] = $this->getFormattedProductPrice($_product->getFinalPrice(), $_currency);
			}else{
				$prices["final"] = $this->getFormattedProductPrice($_product->getFinalPrice(), $_currency)-$tax_price_final;
			}
		    return $prices;
		  }catch(\Exception $e){
		  }
	}

	public function getFormattedProductPrice($price, $_currency) {
        // Currency conversion rates have to be available in the target currency
        $basecurrency = $this->storemanager->getStore()->getBaseCurrencyCode();
        $converted_price = $this->directorydata->currencyConvert($price, $basecurrency, $_currency);
        // if you want it rounded:
        /*if($round) {
        	$converted_price = $this->storemanager->getStore()->roundPrice($converted_price);
        }*/
        return $converted_price;
    }

    public function getMofluidMediaGalleryImages($_product) {
		 $media_url = $this->storemanager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
         $mofluid_all_product_images = array();
         $mofluid_non_def_images = array();
         $mofluid_product = $_product;
         $mofluid_baseimage =  $media_url.'media/catalog/product'.$mofluid_product->getImage();
	    foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
	     	$mofluid_imagecame =   $mofluid_image->getUrl();
	     	if($mofluid_baseimage == $mofluid_imagecame){
	     	    $mofluid_all_product_images[] = $mofluid_image->getUrl();
         	     }
         	     else{
         		    $mofluid_non_def_images[] =  $mofluid_image->getUrl();
         	     }
         }
         $mofluid_all_product_images = array_merge($mofluid_all_product_images,$mofluid_non_def_images);
         return $mofluid_all_product_images;
     }

     public function prepareGroupedProductPrice($groupedProduct, $_currency) {

        $aProduct = $this->grouped->getAssociatedProducts($groupedProduct);
        $prices = array();
        foreach ($aProduct as $pro) {
			$aProduct = $this->_product->load($pro->getId());
			$prices[] =  $this->getFormattedProductPrice($aProduct->getFinalprice(), $_currency);
		}
        sort($prices);
        return $prices[0];
    }

     public function getProductReview($productId, $_store) {
	    $product_review = array();
	    $reviews = $this->preveiwdata
				->getResourceCollection()
				->addStoreFilter($_store)
				->addEntityFilter('product', $productId)
				->addStatusFilter(1)
				->setDateOrder()
				->addRateVotes();
		/**
		 * Getting average of ratings/reviews
		 */
		$avg = 0;
		$review_counter = 0;
		$ratings = array();
		$product_review["total"] = count($reviews);
		if (count($reviews) > 0) {
			foreach ($reviews->getItems() as $review) {
			     $product_review["all"][$review_counter]["id"] = $review->getId();
			     $product_review["all"][$review_counter]["createdat"] = $review->getCreatedAt();
			     $product_review["all"][$review_counter]["statusid"] = $review->getStatusId();
			     $product_review["all"][$review_counter]["detailid"] = $review->getDetailId();
			     $product_review["all"][$review_counter]["detail"] = $review->getDetail();
			     $product_review["all"][$review_counter]["title"] = $review->getTitle();
			     $product_review["all"][$review_counter]["nickname"] = $review->getNickname();
			     $vote_counter =0;
				foreach( $review->getRatingVotes() as $vote ) {
					$ratings[] = $vote->getPercent();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["id"] =  $vote->getId();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["name"]=  $vote->getRatingCode();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["percent"] =  $vote->getPercent();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["value"]=  $vote->getValue();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["option_id"]=  $vote->getOptionId();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["remote_ip"]=  $vote->getRemoteIp();
					$product_review["all"][$review_counter]["vote"][$vote_counter ]["store_id"]=  $vote->getStoreId();
					$vote_counter ++;
				}
				$review_counter++;
			}
			$avg = array_sum($ratings)/count($ratings);
			$product_review["average"] = $avg;
		}
		return $product_review;
	}
}
