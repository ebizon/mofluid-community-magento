<?php
    function ws_category($store, $service)
    {
		$storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
		$cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
		$media_url = $storeObj->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $cache_key = "mofluid_" . $service . "_store" . $store;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));

        $res = array();
        try {
            $storecategoryid = $storeObj->getRootCategoryId();
            $total           = 0;
            $category        = $this->_objectManager->get('Magento\Catalog\Model\Category');
            $tree            = $category->getTreeModel();
            $tree->load();

            $ids = $tree->getCollection()->getAllIds();
            $arr = array();

            $storecategoryid = $storeObj->getRootCategoryId();
            $cat = $this->_objectManager->get('Magento\Catalog\Model\Category');
            $cat->load($storecategoryid);
            $categories = $cat->getCollection()->addAttributeToSelect(array(
                'name',
                'thumbnail',
                'image',
                'description',
                'store'
            ))->addIdFilter($cat->getChildren());
            try {
                foreach ($categories as $tmp) {
                    $res[] = array(
                        "id" => $tmp->getId(),
                        "name" => $tmp->getName(),
                        "image" => $category->load($tmp->getId())->getImageUrl(),
                        "thumbnail" => $media_url . 'catalog/category/' . $category->load($tmp->getId())->getThumbnail()
                    );
                    $total = $total + 1;
                }
            }
            catch (Exception $ex) {
                $res = $this->ws_subcategory($store, 'subcategory', $storecategoryid);
            }
            array_push($arr, $cat);
        }
        catch (Exception $ex) {

        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);

        return ($res);
    }

    function ws_subcategory($store_id, $service, $categoryid)
    {
        $storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
        $cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
        $cache_key = "mofluid_" . $service . "_store" . $store_id . "_category" . $categoryid;
        $categoryobj        = $this->_objectManager->get('Magento\Catalog\Model\Category');
        $media_url = $storeObj->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->setCurrentStore($store_id);
        $res      = array();
        $children = $categoryobj->getCategories($categoryid);
        foreach ($children as $current_category) {
            $category = $categoryobj->load($current_category->getId());
            $res[]    = array(
                "id" => $category->getId(),
                "name" => $category->getName(),
                "image" => $category->getImageUrl(),
                "thumbnail" => $media_url . 'catalog/category/' . $category->getThumbnail()
            );
        }
        $result["id"]         = $categoryid;
        $result["title"]      = $categoryobj->load($categoryid)->getName();
        $result["images"]     = $categoryobj->load($categoryid)->getImageUrl();
        $result["thumbnail"]  = $media_url . 'catalog/category/' . $categoryobj->load($categoryid)->getThumbnail();
        $result["categories"] = $res;
        return ($result);
    }

     function ws_products($store_id, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode)
    {
		$storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
		$scopeConfig = $this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store_id);
        $res = array();
        $basecurrencycode   = $storeObj->getStore($store_id)->getBaseCurrencyCode();
        $c_id     = $categoryid;
        $category = $this->_objectManager->get('Magento\Catalog\Model\Category');
        $product = $this->_objectManager->get('Magento\Catalog\Model\Product');
        $category->load($c_id);
        $collection   = $category->getProductCollection()->addStoreFilter($store_id)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped'
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1)->addAttributeToSort($sortType, $sortOrder);

        $manageStock = $scopeConfig->getValue(
            \Magento\CatalogInventory\Model\Configuration::XML_PATH_MANAGE_STOCK,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $cond = [
            '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=1 AND {{table}}.is_in_stock=1',
            '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=0'
        ];

        if ($manageStock) {
            $cond[] = '{{table}}.use_config_manage_stock = 1 AND {{table}}.is_in_stock=1';
        } else {
            $cond[] = '{{table}}.use_config_manage_stock = 1';
        }

        $collection->joinField(
            'inventory_in_stock',
            'cataloginventory_stock_item',
            'is_in_stock',
            'product_id=entity_id',
             join(') OR (', $cond) . ')'
        );
        //echo $collection->getSelect()->__toString(); die('gcdgdv');
        $res["total"] = count($collection);
        $collection->setPage($curr_page, $page_size);

        foreach ($collection as $_product) {
           	$gflag=1;
            $mofluid_product            = $product->load($_product->getId());
            $mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
            $defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
            $defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));

            try {
                $custom_options        = $mofluid_product->getOptions();
                $has_custom_option     = 0;
                foreach ($custom_options as $optionKey => $optionVal) {
                    $has_custom_option = 1;
                }
            }
            catch (Exception $ee) {
                $has_custom_option = 0;
            }

            // Get the Special Price
            $specialprice         = $mofluid_product->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate = $mofluid_product->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate   = $mofluid_product->getSpecialToDate();
            // Get Current date
            $today                = time();

            if ($specialprice) {

                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {

                    $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }

            //Code added by sumit
             if ($_product->getTypeID() == 'grouped') {

             	$defaultprice = number_format($this->getGroupedProductPrice($_product->getId(), $currentcurrencycode) , 2, '.', '');
                $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
              // 	$mofluid_all_product_images[0] = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . 'media/catalog/product' . $mofluid_product->getImage();
             $associatedProducts = $_product->getTypeInstance(true)->getAssociatedProducts($_product);
             	if(count($associatedProducts))
             	{
             		$gflag=1;
             	}
             	else
             	{
             		$gflag=0;
             	}
            }
            else
            {
            	 $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
           		 $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
            }
            //End sumit code
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');

           $stock = $this->_objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($_product->getId());
           if($gflag)
           {
            $res["data"][] = array(
                "id" => $_product->getId(),
                "name" => $_product->getName(),
                "imageurl" => $mofluid_baseimage,
                "sku" => $_product->getSku(),
                "type" => $_product->getTypeID(),
                "spclprice" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "currencysymbol" => $this->_objectManager->get('Magento\Framework\Locale\CurrencyInterface')->getCurrency($currentcurrencycode)->getSymbol(),
                "price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "created_date" => $_product->getCreatedAt(),
                "is_in_stock" => $stock->getIsInStock(),
                "hasoptions" => $has_custom_option,
                "stock_quantity" => $stock->getQty()
            );
            }

        }
        return ($res);
    }



     function ws_getFeaturedProducts($currentcurrencycode, $service, $store)
    {
		$storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
		$scopeConfig = $this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store);
        $res = array();
        $basecurrencycode   = $storeObj->getStore($store)->getBaseCurrencyCode();
        $product = $this->_objectManager->get('Magento\Catalog\Model\Product');
        $collection   = $product->getCollection()->addStoreFilter($store)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped'
            )
        ))->addFieldToFilter(array(
            array(
                'attribute' => 'featured',
                'eq' => true
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1);

        $manageStock = $scopeConfig->getValue(
            \Magento\CatalogInventory\Model\Configuration::XML_PATH_MANAGE_STOCK,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $cond = [
            '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=1 AND {{table}}.is_in_stock=1',
            '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=0'
        ];

        if ($manageStock) {
            $cond[] = '{{table}}.use_config_manage_stock = 1 AND {{table}}.is_in_stock=1';
        } else {
            $cond[] = '{{table}}.use_config_manage_stock = 1';
        }

        $collection->joinField(
            'inventory_in_stock',
            'cataloginventory_stock_item',
            'is_in_stock',
            'product_id=entity_id',
             join(') OR (', $cond) . ')'
        );
        $i = 0;
        $collection->setPage(1, 10);
        if($collection->getSize()){
			foreach ($collection as $_product) {
				$gflag=1;
				$mofluid_all_product_images = array();
				$mofluid_non_def_images     = array();
				$mofluid_all_product_images = array();
				$mofluid_non_def_images     = array();
				$mofluid_product            = $product->load($_product->getId());
				$mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
				$defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
				$defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));

				// Get the Special Price
				$specialprice         = $product->load($_product->getId())->getSpecialPrice();
				// Get the Special Price FROM date
				$specialPriceFromDate = $product->load($_product->getId())->getSpecialFromDate();
				// Get the Special Price TO date
				$specialPriceToDate   = $product->load($_product->getId())->getSpecialToDate();
				// Get Current date
				$today                = time();

				if ($specialprice) {

					if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {

						$specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
					} else {
						$specialprice = 0;
					}
				} else {
					$specialprice = 0;
				}

				//Code added by sumit
				 if ($_product->getTypeID() == 'grouped') {

					$defaultprice = number_format($this->getGroupedProductPrice($_product->getId(), $currentcurrencycode) , 2, '.', '');
					$specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
				 }else{
					 $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
					 $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
				 }
				//End sumit code
				if($defaultprice == $specialprice)
					$specialprice = number_format(0, 2, '.', '');

			   $stock = $this->_objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($_product->getId());
			   $res["products_list"][$i++] = array(
					"id" => $_product->getId(),
					"name" => $_product->getName(),
					"image" => $mofluid_baseimage,
					"type" => $_product->getTypeID(),
					"price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"special_price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"currency_symbol" => $this->_objectManager->get('Magento\Framework\Locale\CurrencyInterface')->getCurrency($currentcurrencycode)->getSymbol(),
					"is_stock_status" => $stock->getIsInStock()
				);
				$res["status"][0] = array(
                'Show_Status' => "1"
                );
               }
           }else{
			   $res["status"][0] = array(
                'Show_Status' => "0"
                );
		   }
        return ($res);

	}

     function ws_getNewProducts($currentcurrencycode, $service, $store)
    {
		$storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
		$scopeConfig = $this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store);
        $res = array();
        $basecurrencycode   = $storeObj->getStore($store)->getBaseCurrencyCode();
        $product = $this->_objectManager->get('Magento\Catalog\Model\Product');
        $collection   = $product->getCollection()->addStoreFilter($store)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped'
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1)->setOrder('created_at', 'desc');

        $manageStock = $scopeConfig->getValue(
            \Magento\CatalogInventory\Model\Configuration::XML_PATH_MANAGE_STOCK,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $cond = [
            '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=1 AND {{table}}.is_in_stock=1',
            '{{table}}.use_config_manage_stock = 0 AND {{table}}.manage_stock=0'
        ];

        if ($manageStock) {
            $cond[] = '{{table}}.use_config_manage_stock = 1 AND {{table}}.is_in_stock=1';
        } else {
            $cond[] = '{{table}}.use_config_manage_stock = 1';
        }

        $collection->joinField(
            'inventory_in_stock',
            'cataloginventory_stock_item',
            'is_in_stock',
            'product_id=entity_id',
             join(') OR (', $cond) . ')'
        );
        $i = 0;
        $collection->setPage(1, 10);
        if($collection->getSize()){
			foreach ($collection as $_product) {
				$gflag=1;
				$mofluid_product            = $product->load($_product->getId());
				$mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
				$defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
				$defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));

				// Get the Special Price
				$specialprice         = $mofluid_product->getSpecialPrice();
				// Get the Special Price FROM date
				$specialPriceFromDate = $mofluid_product->getSpecialFromDate();
				// Get the Special Price TO date
				$specialPriceToDate   = $mofluid_product->getSpecialToDate();
				// Get Current date
				$today                = time();

				if ($specialprice) {

					if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {

						$specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
					} else {
						$specialprice = 0;
					}
				} else {
					$specialprice = 0;
				}

				//Code added by sumit
				 if ($_product->getTypeID() == 'grouped') {

					$defaultprice = number_format($this->getGroupedProductPrice($_product->getId(), $currentcurrencycode) , 2, '.', '');
					$specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
				 }else{
					 $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
					 $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
				 }
				//End sumit code
				if($defaultprice == $specialprice)
					$specialprice = number_format(0, 2, '.', '');

			   $stock = $this->_objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($_product->getId());
			   $res["products_list"][$i++] = array(
					"id" => $_product->getId(),
					"name" => $_product->getName(),
					"image" => $mofluid_baseimage,
					"type" => $_product->getTypeID(),
					"price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"special_price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"currency_symbol" => $this->_objectManager->get('Magento\Framework\Locale\CurrencyInterface')->getCurrency($currentcurrencycode)->getSymbol(),
					"is_stock_status" => $stock->getIsInStock()
				);
				$res["status"][0] = array(
                'Show_Status' => "1"
                );
               }
           }else{
			   $res["status"][0] = array(
                'Show_Status' => "0"
                );
		   }
        return ($res);

	}
     function getGroupedProductPrice($product_id, $currency)
    {
		$product = $this->_objectManager->get('Magento\Catalog\Model\Product');
		$storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$directory = $this->_objectManager->get('Magento\Directory\Helper\Data');
        $group            = $this->_objectManager->get('Magento\Catalog\Model\Product\type\group')->setProduct($product->load($product_id));
        $base_currency    = $storeObj->getStore()->getBaseCurrencyCode();
        $group_collection = $group->getAssociatedProductCollection();
        $prices           = array();
        foreach ($group_collection as $group_product) {
            $_product = $product->load($group_product->getId());
            $prices[] = round(floatval($directory->currencyConvert($_product->getFinalPrice(), $base_currency, $currency)), 2);
        }
        sort( $prices);
        $prices = array_shift($prices);
        return $prices;
    }



     function convert_currency($price, $from, $to)
    {
        $newPrice = $this->_objectManager->get('Magento\Directory\Helper\Data')->currencyConvert($price, $from, $to);
        return $newPrice;
    }

    function ws_validatecurrency($store, $service, $currency, $paymentgateway)
    {
        $cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
        $cache_key = "mofluid_service" . $service . "_store" . $store . "_currency" . $currency . "_paymentmethod" . $paymentgateway;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        if ($paymentgateway == 'secureebs_standard' || $paymentgateway == 'paypal_standard' || $paymentgateway == 'authorizenet' || $paymentgateway == 'authorize' || $paymentgateway == 'moto' || $paymentgateway == 'moneris' || $paymentgateway == 'banorte' || $paymentgateway == 'payucheckout_shared' || $paymentgateway == 'sisowde' || $paymentgateway == 'sisow_ideal') {
            $payment_types['paypal']              = array(
                "0" => 'AUD',
                "1" => 'BRL',
                "2" => 'CAD',
                "3" => 'CZK',
                "4" => 'DKK',
                "5" => 'EUR',
                "6" => 'HKD',
                "7" => 'HUF',
                "8" => 'ILS',
                "9" => 'JPY',
                "10" => 'MYR',
                "11" => 'MXN',
                "12" => 'NOK',
                "13" => 'NZD',
                "14" => 'PHP',
                "15" => 'PLN',
                "16" => 'GBP',
                "17" => 'RUB',
                "18" => 'SGD',
                "19" => 'SEK',
                "20" => 'CHF',
                "21" => 'TWD',
                "22" => 'TRY',
                "23" => 'THB',
                "24" => 'USD'
            );
            $payment_types['paypal_standard']     = $payment_types['paypal'];
            $payment_types['authorizenet']        = array(
                "0" => 'GBP',
                "1" => 'USD',
                "2" => 'EUR',
                "3" => 'AUD'
            );
            $payment_types['secureebs_standard']  = array(
                "0" => 'INR'
            );
            $payment_types['moto']                = array(
                "0" => 'INR'
            );
            $payment_types['moneris']             = array(
                "0" => 'USD'
            );
            $payment_types['banorte']             = array(
                "0" => 'MXN'
            );
            $payment_types['payucheckout_shared'] = array(
                "0" => 'INR'
            );
            $payment_types['sisowde']             = array(
                "0" => 'EUR'
            );
            $payment_types['sisow_ideal']         = array(
                "0" => 'EUR'
            );
            $size_of_array                        = sizeof($payment_types[$paymentgateway]);
            if ($size_of_array > 0) {
                if (in_array($currency, $payment_types[$paymentgateway]))
                    $status = "1";
                else {
                    $msg    = "Currency Code " . $currency . " is not supported with this Payment Type. Please Select different Payment Mode.";
                    $status = "0";
                }
            }
        } else
            $status = "1";
        $res["status"] = $status;
        $res["msg"]    = $msg;
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return $res;
    }

     function ws_createuser($store, $service, $firstname, $lastname, $email, $password)
    {
        // Website and Store details
        $res                  = array();
        $storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $storemodel = $this->_objectManager->get('Magento\Store\Model\Store');
        $websiteId            = $storemodel->load($store)->getWebsiteId();
        $customerObj          = $this->_objectManager->get('Magento\Customer\Model\Customer');

		$res["email"]            = $email;
		$res["firstname"]        = $firstname;
		$res["lastname"]         = $lastname;
		$res["password"]         = $password;
		$res["status"]           = 0;
		$res["id"]               = 0;
		$cust  = $this->_objectManager->get('Magento\Customer\Model\Customer')->setWebsiteId($websiteId)->loadByEmail($email);
		if ($cust->getId()) {
			$res["id"]     = $cust->getId();
			$res["status"] = 0;
		} else {
			try{
				$customer = $this->_objectManager->get('Magento\Customer\Model\Customer');
				$customer->website_id = $websiteId;
				$customer->setCurrentStore($store);
				// If new, save customer information
				$customer->setWebsiteId($websiteId)->setFirstname($firstname)->setLastname($lastname)->setEmail($email)->setPassword($password)->save();
				$customer->sendNewAccountEmail($type = 'registered', $backUrl = '', $store);
				$res["id"]     = $customer->getId();
				$res["status"] = 1;
			}catch (Exception $e) {

			}
        }
        return $res;
    }

     function ws_productdetail($store_id, $service, $productid, $currentcurrencycode)
 {
        $storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
		$scopeConfig = $this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface');
		$taxcalculation = $this->_objectManager->get('Magento\Tax\Api\TaxCalculationInterface');
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store_id);
        $custom_attr       = array();
        $product = $this->_objectManager->get('Magento\Catalog\Model\Product')->load($productid);
        $attributes        = $product->getAttributes();
        $stock = $this->_objectManager->get('Magento\CatalogInventory\Model\StockRegistry')->getStockItem($product->getId());
        //echo count($attributes);
        $custom_attr_count = 0;

        foreach ($attributes as $attribute) {
			if ($attribute->getIsVisibleOnFront()) {
					$attributeCode = $attribute->getAttributeCode();
					$label = $attribute->getFrontend()->getLabel($product);
					$value = $attribute->getFrontend()->getValue($product);
					$custom_attr["data"][$custom_attr_count]["attr_code"]  = $attributeCode;
					$custom_attr["data"][$custom_attr_count]["attr_label"] = $label;
					$custom_attr["data"][$custom_attr_count]["attr_value"] = $value;
					++$custom_attr_count;
			}
		}

        $custom_attr["total"] = $custom_attr_count;
        $res = array();

        $mofluid_all_product_images = array();
        $mofluid_non_def_images     = array();
        $mofluid_product            = $product;
        $mofluid_baseimage          = $media_url. 'catalog/product' . $mofluid_product->getImage();

        foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
            $mofluid_imagecame = $mofluid_image->getUrl();
            if ($mofluid_baseimage == $mofluid_imagecame) {
                $mofluid_all_product_images[] = $mofluid_image->getUrl();
            } else {
                $mofluid_non_def_images[] = $mofluid_image->getUrl();
            }
        }
        $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
        //get base currency from magento
        $basecurrencycode = $storeObj->getStore($store_id)->getBaseCurrencyCode();
		$a = $product;
		$store = $storeObj->getStore($store_id);
		$taxClassId = $product->getTaxClassId();
		$percent = $taxcalculation->getDefaultCalculatedRate($taxClassId, null, $store);//->getRate($request->setProductClassId($taxClassId));

		$b  = (($percent) / 100) * ($product->getFinalPrice());
		$all_custom_option_array = array();
		$attVal                  = $product->getOptions();
		$optStr                  = "";
		$inc                     = 0;
		$has_custom_option       = 0;
		foreach ($attVal as $optionKey => $optionVal) {

			$has_custom_option                                          = 1;
			$all_custom_option_array[$inc]['custom_option_name']        = $optionVal->getTitle();
			$all_custom_option_array[$inc]['custom_option_id']          = $optionVal->getId();
			$all_custom_option_array[$inc]['custom_option_is_required'] = $optionVal->getIsRequire();
			$all_custom_option_array[$inc]['custom_option_type']        = $optionVal->getType();
			$all_custom_option_array[$inc]['sort_order']                = $optionVal->getSortOrder();
			$all_custom_option_array[$inc]['all']                       = $optionVal->getData();
			if ($all_custom_option_array[$inc]['all']['default_price_type'] == "percent") {
				$all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice() * round($all_custom_option_array[$inc]['all']['price'] * 10, 2) / 10) / 100), 2);
				//$all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice()*$all_custom_option_array[$inc]['all']['price'])/100),2);
			} else {
				$all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'], 2);
			}

			$all_custom_option_array[$inc]['all']['price'] = str_replace(",", "", $all_custom_option_array[$inc]['all']['price']);
			$all_custom_option_array[$inc]['all']['price'] = strval(round($this->convert_currency($all_custom_option_array[$inc]['all']['price'], $basecurrencycode, $currentcurrencycode), 2));

			$all_custom_option_array[$inc]['custom_option_value_array'];
			$inner_inc = 0;
			foreach ($optionVal->getValues() as $valuesKey => $valuesVal) {
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id']    = $valuesVal->getId();
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $valuesVal->getTitle();

				$defaultcustomprice                                                              = str_replace(",", "", ($valuesVal->getPrice()));
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));

				//$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format($valuesVal->getPrice(),2);
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $valuesVal->getPriceType();
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku']        = $valuesVal->getSku();
				$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $valuesVal->getSortOrder();
				if ($valuesVal->getPriceType() == "percent") {

					$defaultcustomprice                                                              = str_replace(",", "", ($product->getFinalPrice()));
					$customproductprice                                                              = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode), 2));
					$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",", "", round((floatval($customproductprice) * floatval(round($valuesVal->getPrice(), 1)) / 100), 2));
					//$all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = number_format((($product->getPrice()*$valuesVal->getPrice())/100),2);
				}
				$inner_inc++;
			}
			$inc++;
            }

            $res["id"]          = $product->getId();
            $res["sku"]         = $product->getSku();
            $res["name"]        = $product->getName();
            $res["category"]    = $product->getCategoryIds(); //'category';
            $res["image"]       = $mofluid_all_product_images;
            $res["url"]         = $product->getProductUrl();
            $res["description"] = $product->getDescription();
            $res["shortdes"]    = $product->getShortDescription();
            $res["quantity"]    = $stock->getQty();
            $res["visibility"]  = $product->isVisibleInSiteVisibility(); //getVisibility();
            $res["type"]        = $product->getTypeID();
            $res["weight"]      = $product->getWeight();
            $res["status"]      = $product->getStatus();

            //convert price from base currency to current currency
            $res["currencysymbol"] = $this->_objectManager->get('Magento\Framework\Locale\CurrencyInterface')->getCurrency($currentcurrencycode)->getSymbol();


            $defaultprice  = str_replace(",", "", ($product->getPrice()));
            $discountprice = str_replace(",", "", number_format($product->getFinalPrice(), 2));
            //  $discountprice = str_replace(",","",($product->getFinalPrice()));

            $res["discount"] = strval(round($this->convert_currency($discountprice, $basecurrencycode, $currentcurrencycode), 2));


            $defaultshipping = $scopeConfig ->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $res["shipping"] = strval(round($this->convert_currency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));

            $defaultsprice = str_replace(",", "", ($product->getSpecialprice()));


            // Get the Special Price
            $specialprice         = $product->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate = $product->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate   = $product->getSpecialToDate();
            // Get Current date
            $today                = time();

            if ($specialprice) {
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                    $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }



            if (floatval($discountprice)) {
                if (floatval($discountprice) < floatval($defaultprice)) {
                    $defaultprice = floatval($discountprice);
                }
            }


            $defaultprice =  number_format($product->getPrice(), 2, '.', '');
            $specialprice =  number_format($product->getFinalPrice(), 2, '.', '');
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');


            $res["price"]    =  number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            $res["sprice"]   = number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            $res["tax"]      = number_format($b, 2);
            $tax_type   = $scopeConfig ->getValue('tax/calculation/price_includes_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $res["tax_type"] = $tax_type;

            $res["has_custom_option"] = $has_custom_option;
            if ($has_custom_option) {
                $res["custom_option"] = $all_custom_option_array;
            }
        $res["custom_attribute"] = $custom_attr;
        return ($res);
    }


     function getallCMSPages($store, $pageId)
    {
        $page_data = array();
        $page = $this->_objectManager->get('Magento\Cms\Model\Page')->load($pageId);
        $pagehelper = $this->_objectManager->get('Magento\Cms\Model\Template\FilterProvider');
        $page_data["title"]   = $page->getTitle();
        $page_data["content"] = $pagehelper->getBlockFilter()->setStoreId($store)->filter($page->getContent());
        return ($page_data);
    }

     function ws_currency($store_id, $service)
    {
		$storeObj = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
		$cache = $this->_objectManager->get('Magento\Framework\App\CacheInterface');
		$locale = $this->_objectManager->get('Magento\Framework\Locale\CurrencyInterface');
        $cache_key = "mofluid_currency_store" . $store_id;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        $res                    = array();
        $res["currentcurrency"] = $storeObj->getStore($store_id)->getCurrentCurrencyCode();
        $res["basecurrency"]    = $storeObj->getStore($store_id)->getBaseCurrencyCode();
        $res["currentsymbol"]   = $locale->getCurrency($res["currentcurrency"])->getSymbol();
        $res["basesymbol"]      = $locale->getCurrency($res["basecurrency"])->getSymbol();
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return ($res);
    }
