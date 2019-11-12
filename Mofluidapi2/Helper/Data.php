<?php
namespace Mofluid\Mofluidapi2\Helper;
//require_once(dirname(__FILE__) . '/Stripe.php');
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Quote\Model\Quote\Address\ToOrder as ToOrderConverter;
use Magento\Quote\Model\Quote\Address\ToOrderAddress as ToOrderAddressConverter;
use Magento\Quote\Model\Quote\Item\ToOrderItem as ToOrderItemConverter;
use Magento\Quote\Model\Quote\Payment\ToOrderPayment as ToOrderPaymentConverter;
use Magento\Sales\Api\Data\OrderInterfaceFactory as OrderFactory;
use Magento\Sales\Api\OrderManagementInterface as OrderManagement;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use Magento\Sales\Model\Order\Email\Sender;
use Magento\Sales\Model\Order\Invoice\SenderInterface;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	public $CACHE_EXPIRY = 300; //in Seconds
	protected $transactionBuilder;
	public function __construct(
		BuilderInterface $transactionBuilder,
	    EventManager $eventManager,
        OrderFactory $orderFactory,
        OrderManagement $orderManagement,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Framework\DB\Transaction $transaction,
        ToOrderConverter $quoteAddressToOrder,
        ToOrderAddressConverter $quoteAddressToOrderAddress,
        ToOrderItemConverter $quoteItemToOrderItem,
        ToOrderPaymentConverter $quotePaymentToOrderPayment,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mofluid\Mofluidapi2\Model\Catalog\Product $Mproduct,
        \Mofluid\Mofluidapi2\Model\Theme $Mtheme,
        \Mofluid\Mofluidapi2\Model\Themeimage $Mimage,
        \Mofluid\Mofluidapi2\Model\Message $Mmessage,
        \Mofluid\Mofluidapi2\Model\Themecolor $Mcolor,
        \Mofluid\Payment\Model\Index $Mpayment,
        \Magento\Catalog\Model\Category $categorydata,
        \Magento\Framework\App\CacheInterface $cachedata,
        \Magento\Framework\Locale\CurrencyInterface $currencydata,
        \Magento\Cms\Model\Page $pagedata,
        \Magento\Catalog\Model\Product $productData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigData,
        \Magento\Tax\Api\TaxCalculationInterface $taxcalculationData,
        \Magento\Customer\Model\Customer $customerData,
        \Magento\Customer\Model\Address $addressData,
        \Magento\CatalogInventory\Model\StockRegistry $stockRegistry,
        \Magento\Cms\Model\Template\FilterProvider $pagefilterData,
        \Magento\Store\Model\Store $storeData,
        \Magento\Directory\Helper\Data $directoryData,
        \Magento\GroupedProduct\Model\Product\Type\Grouped $groupedProductData,
        \Magento\Customer\Api\AccountManagementInterface $accountManagementInterfaceData,
        \Magento\Customer\Model\Session $sessionData,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Escaper $escaperData,
        \Magento\Directory\Model\Country $country,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableProductData,
        \Magento\Tax\Api\TaxCalculationInterface $taxcalculation,
        \Magento\Directory\Model\Region $region,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Model\Quote\Item $QuoteItem,
        \Magento\Customer\Model\Address\Form $AddressFrom,
        \Magento\Sales\Model\Order $orderData,
        \Magento\GiftMessage\Model\Message $giftMessage,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Customer\Api\Data\CustomerInterface $customerInterface,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Checkout\Model\Session $checkoutSession,
         \Magento\Catalog\Model\ProductFactory $productFactory,
       // \Magedelight\Stripe\Model\Cards $stripeId,
       \Magento\Checkout\Model\Cart $cart,
        InvoiceSender $invoicesender
    ) {
		$this->mproduct = $Mproduct;
		$this->_storeManager = $storeManager;
		$this->_category = $categorydata;
		$this->_cache = $cachedata;
		$this->_currency = $currencydata;
		$this->_page = $pagedata;
		$this->_product = $productData;
		$this->_scopeconfig = $scopeConfigData;
		$this->_taxcalculation = $taxcalculationData;
		$this->customerRepository = $customerRepository;
		$this->_customer = $customerData;
		$this->stock = $stockRegistry;
		$this->_pagefilter = $pagefilterData;
		$this->taxcalculation = $taxcalculation;
		$this->_store = $storeData;
		$this->_directory = $directoryData;
		$this->configurable = $configurableProductData;
		$this->grouped = $groupedProductData;
		$this->_address = $customerData;
		$this->_accountManagementInterface = $accountManagementInterfaceData;
		$this->_session = $sessionData;
		$this->_escaper = $escaperData;
		$this->_date = $date;
		$this->_timezone = $timezone;
		$this->_country = $country;
		$this->_region = $region;
		$this->_address = $addressData;
		$this->_theme = $Mtheme;
		$this->_themeimage = $Mimage;
		$this->_mmessage = $Mmessage;
		$this->_themecolor = $Mcolor;
		$this->_urlinterface = $urlInterface;
		$this->_mpayment = $Mpayment;
		$this->_quote = $quote;
		$this->_quoteitem = $QuoteItem;
		$this->_addressform = $AddressFrom;
		$this->_orderData = $orderData;
		$this->_giftMessage = $giftMessage;
		$this->eventManager = $eventManager;
        $this->orderFactory = $orderFactory;
        $this->orderManagement = $orderManagement;
        $this->quoteAddressToOrder = $quoteAddressToOrder;
        $this->quoteAddressToOrderAddress = $quoteAddressToOrderAddress;
        $this->quoteItemToOrderItem = $quoteItemToOrderItem;
        $this->quotePaymentToOrderPayment = $quotePaymentToOrderPayment;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->quoteRepository = $quoteRepository;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_orderSender = $orderSender;
       // $this->_stripeId = $stripeId;
        $this->transactionBuilder = $transactionBuilder;
        $this->_orderRepository = $orderRepository;
        $this->_invoiceService = $invoiceService;
        $this->_transaction = $transaction;
        $this->invoiceSender = $invoicesender;
        $this->formKey = $formKey;
        $this->_cart = $cart;
        $this->quote = $quoteFactory;
        $this->checkoutSession = $checkoutSession;
         $this->product = $productFactory;
        parent::__construct($context);

    }
    public function stripeData($customer_id)
    {
		//~ $customer_id = 27;

		$customerData = $this->_customer->load($customer_id);
		return $customerData->getMdStripeCustomerId();
		//~ var_dump($tt->getData());die;
		//~ $collection =  $this->_stripeId->getCollection();
		//~ $tt = $collection->addFieldToFilter('md_stripe_customer_id', $customer_id);
		//~ var_dump($tt->getData());die;
		//~ return $collection->addFieldToFilter('md_stripe_customer_id', $customer_id);
	}

    public function ws_storedetails($store, $service, $theme, $currentcurrencycode)
    {
		$storeObj    = $this->_storeManager;
		$scopeConfig = $this->_scopeconfig;
		$media_url   = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$cache       = $this->_cache;
        $cache_key   = "mofluid_" . $service . "_store" . $store;
        $res         = array();
        $date        = $this->_date;
        $timezone    = $this->_timezone;
        $offset      = $date->getGmtOffset($timezone);
        $offset_hour = (int) ($date->getGmtOffset($timezone) / 3600);
        $offset_min  = ($date->getGmtOffset($timezone) % 3600) / 60;
        if($theme == ''){
			$theme = 'modern';
		}

		$mofluidCms = $this->_themeimage->getCollection()->addFieldToFilter('mofluid_theme_id', 2)->getData();

        $themedata             = $this->_theme->getCollection()->addFieldToFilter('mofluid_theme_code', $theme)->getFirstItem();
        $mofluid_theme_id      = $themedata->getMofluidThemeId();
        $google_client_id      = $themedata->getGoogleIosClientid();
		$google_login          = $themedata->getGoogleLogin();
		$cms_pages             = $mofluidCms[0]['cms_pages'];
		$about_us              = $mofluidCms[0]['about_us'];
		$term_condition        = $mofluidCms[0]['term_condition'];
		$privacy_policy        = $mofluidCms[0]['privacy_policy'];
		$return_privacy_policy = $mofluidCms[0]['return_privacy_policy'];
		$tax_flag              = $themedata->getTaxFlag();
		$mofluid_theme_banner_image_type = $themedata->getMofluidThemeBannerImageType();

		$mofluid_theme_data                    = array();
		$cache_array                           = array();
		$res["store"]                          = array();
		$res["store"]                          = $storeObj->getStore($store)->getData();
		$res["store"]["frontname"]             = $storeObj->getStore($store)->getFrontendName(); //getLogoSrc()
		$res["store"]["cache_setting"]         = $cache_array;
		$res["store"]["logo"]                  = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC) . 'frontend/default/default/' .  $scopeConfig ->getValue('design/header/logo_src', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$res["store"]["banner"]                = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC) . 'frontend/default/default/images/banner.png';
		$res["store"]["adminname"]             = $scopeConfig ->getValue('trans_email/ident_sales/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$res["store"]["email"]                 = $scopeConfig ->getValue('trans_email/ident_sales/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$res["store"]["checkout"]              = $scopeConfig ->getValue('trans_email/ident_sales/email', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$res["store"]["google_ios_clientid"]   = $google_client_id;
		$res["store"]["google_login_flag"]     = $google_login;
		$res["store"]["cms_pages"]             = $cms_pages;
		$res["store"]["about_us"]              = $about_us;
		$res["store"]["term_condition"]        = $term_condition;
		$res["store"]["privacy_policy"]        = $privacy_policy;
		$res["store"]["return_privacy_policy"] = $return_privacy_policy;
		$res["store"]["tax_flag"]              = $tax_flag;
		$res["timezone"]                       = array();
		$res["timezone"]["name"]               = $timezone;
		$res["timezone"]["offset"]             = array();
		$res["timezone"]["offset"]["value"]    = $offset;
		$res["timezone"]["offset"]["hour"]     = $offset_hour;
		$res["timezone"]["offset"]["min"]      = $offset_min;
		$res["url"]                            = array();
		$res["url"]["current"]                 = $this->_urlinterface->getCurrentUrl();
		$res["url"]["media"]   				   = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$res["url"]["skin"]    				   = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_STATIC);
		//$res["url"]["js"]      				   = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_JS);
		$res["url"]["root"]   				   = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
		$res["url"]["store"]   				   = $this->_urlinterface->getHomeUrl();
		$res["currency"]                       = array();
		$res["currency"]["base"]["code"]       = $storeObj->getStore($store)->getBaseCurrencyCode();
		$res["currency"]["base"]["name"]       = $this->_currency->getCurrency($storeObj->getStore($store)->getBaseCurrencyCode())->getName();
		$res["currency"]["base"]["symbol"]     = $this->_currency->getCurrency($storeObj->getStore($store)->getBaseCurrencyCode())->getSymbol();
		$res["currency"]["current"]["code"]        = $storeObj->getStore($store)->getCurrentCurrencyCode();
		$res["currency"]["current"]["name"]        = $this->_currency->getCurrency($storeObj->getStore($store)->getCurrentCurrencyCode())->getName();
		$res["currency"]["current"]["symbol"]      = $this->_currency->getCurrency($storeObj->getStore($store)->getCurrentCurrencyCode())->getSymbol();
		$res["currency"]["allow"]                  = $scopeConfig ->getValue('currency/options/allow', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$res["configuration"]                      = array();
		$res["configuration"]["show_out_of_stock"] = $scopeConfig ->getValue('cataloginventory/options/show_out_of_stock', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

		$mofluidbanners = $this->_themeimage->getCollection()->addFieldToFilter('mofluid_theme_id', $mofluid_theme_id)->addFieldToFilter('mofluid_image_type', 'banner')->setOrder('mofluid_image_sort_order', 'ASC')->getData();

		if ($mofluid_theme_banner_image_type == "1") {
			foreach ($mofluidbanners as $banner_key => $banner_value) {
				$mbanner = '';
				$mbanner = $media_url.$banner_value['mofluid_image_value'];
		        $banner_value['mofluid_image_value'] = $mbanner;
				try {
					$mofluid_image_action = json_decode(base64_decode($banner_value['mofluid_image_action']));
				}
				catch (\Exception $ex) {
					$echo= $ex->getMessage();
					$this->getResponse()->setBody($echo);
				}
				if ($banner_value['mofluid_store_id'] == $store) {
					$mofluid_theme_banner_data[] = $banner_value;
				} else if ($banner_value['mofluid_store_id'] == 0) {
					$mofluid_theme_banner_data[] = $banner_value;
				} else {
					continue;
				}
			}
		}else {
			foreach ($mofluidbanners as $banner_key => $banner_value) {
				$mbanner = '';
				$mbanner = $media_url.$banner_value['mofluid_image_value'];
		        $banner_value['mofluid_image_value'] = $mbanner;
				try {
					$mofluid_image_action = json_decode(base64_decode($banner_value['mofluid_image_action']));
				}
				catch (\Exception $ex) {	}
				if ($banner_value['mofluid_image_isdefault'] == '1' && $banner_value['mofluid_store_id'] == $store) {
					$mofluid_theme_banner_data[] = $banner_value;
					break;
				} else if ($banner_value['mofluid_image_isdefault'] == '1' && $banner_value['mofluid_store_id'] == 0) {
					$mofluid_theme_banner_data[] = $banner_value;
					break;
				} else {
					continue;
				}
			}
			if (count($mofluid_theme_banner_data) <= 0) {
				$mofluid_theme_banner_data[] = $mofluid_theme_banner_all_data[0]; //$banner_value;
			}
		}
		$mofluid_theme_logo      = $this->_themeimage->getCollection()->addFieldToFilter('mofluid_image_type', 'logo')->addFieldToFilter('mofluid_theme_id', $mofluid_theme_id);
		$mofluid_theme_logo_data = $mofluid_theme_logo->getData();
		//$echo= "<pre>"; print_r($mofluid_theme_logo_data); die('ddd');
		$mlogo = $media_url.$mofluid_theme_logo_data[0]['mofluid_image_value'];
		$mofluid_theme_logo_data[0]['mofluid_image_value'] = $mlogo;
		$mofluid_theme_data["code"]            = $theme;
		$mofluid_theme_data["logo"]["image"]   = $mofluid_theme_logo_data;
		$mofluid_theme_data["logo"]["alt"]     = $scopeConfig ->getValue('design/header/logo_alt', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$mofluid_theme_data["banner"]["image"] = $mofluid_theme_banner_data;
		$res["theme"]                          = $mofluid_theme_data;
        return ($res);

	}

    public function ws_sidecategory($store, $service)
    {
        $echo= "No category available";
				$this->getResponse()->setBody($echo);
    }

    public function ws_productinfo($store_id, $productid, $currentcurrencycode)
    {
        return $this->mproduct->getCompleteProductInfo($store_id, $productid, $currentcurrencycode);
    }

    public function fetchInitialData($store, $service, $currency)
    {
        $result    = array();
        $storeObj = $this->_storeManager->getStore();
        $rootcatId = $storeObj->getRootCategoryId();
        $result["categories"] = $this->getChildCategories($rootcatId);
        return $result;
    }

    public function rootCategoryData($store, $service)
    {
        $res = array();
        $res["categories"] = $this->ws_category($store, "category");
        return $res;
    }

    function getChildCategories($id){
		$cat = $this->_category->load($id);
		$subcats = $cat->getChildren();
		$all_child = array();
		$counter = 0;
		foreach(explode(',',$subcats) as $subCatid)
		{
		 $_category = $this->_category->load($subCatid);
		 if($_category->getIsActive()) {
			$sub_cat = $this->_category->load($_category->getId());
			$all_child[$counter]["id"]   = $sub_cat->getId();
            $all_child[$counter]["name"] = $sub_cat->getName();
			$sub_subcats = $sub_cat->getChildren();
			$setcount = 0;
			foreach(explode(',',$sub_subcats) as $sub_subCatid)
			{
				 $_sub_category = $this->_category->load($sub_subCatid);
				 if($sub_subCatid) {
					 $all_child[$counter]["children"][$setcount]["id"] = $_sub_category->getId();
					 $all_child[$counter]["children"][$setcount]["name"] = $_sub_category->getName();
				 }
				 $setcount++;
			}
		 }
		 $counter++;
		}

		return $all_child;
	}

	public function ws_category($store, $service)
    {
		$storeObj = $this->_storeManager->getStore();
		$cache = $this->_cache;
		$media_url = $storeObj->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $cache_key = "mofluid_" . $service . "_store" . $store;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));

        $res = array();
        try {
            $storecategoryid = $storeObj->getRootCategoryId();
            $total           = 0;
            $category        = $this->_category;
            $tree            = $category->getTreeModel();
            $tree->load();

            $ids = $tree->getCollection()->getAllIds();
            $arr = array();

            $storecategoryid = $storeObj->getRootCategoryId();
            $cat = $this->_category;
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
            catch (\Exception $ex) {
                $res = $this->ws_subcategory($store, 'subcategory', $storecategoryid);
            }
            array_push($arr, $cat);
        }
        catch (\Exception $ex) {
            $echo=$ex->getMessage();
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);

        return ($res);
    }

    public function ws_subcategory($store_id, $service, $categoryid)
    {
        $storeObj = $this->_storeManager->getStore();
        $cache = $this->_cache;
        $cache_key = "mofluid_" . $service . "_store" . $store_id . "_category" . $categoryid;
        $categoryobj        = $this->_category;
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

    public function ws_products($store_id, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode)
    {
		if($sortType == null || $sortType == 'null'){
			$sortType = 'name';
		}
		if($sortOrder == null || $sortOrder == 'null'){
			$sortOrder = 'ASC';
		}
		if($curr_page == null || $curr_page == 'null'){
			$curr_page = 1;
		}
		if($page_size == null || $page_size == 'null'){
			$page_size = 10;
		}
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$scopeConfig = $this->_scopeconfig;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store_id);
        $res = array();
        $basecurrencycode   = $storeObj->getStore($store_id)->getBaseCurrencyCode();
        $c_id     = $categoryid;
        $category = $this->_category;
        $product = $this->_product;
        $category->load($c_id);
        $collection   = $category->getProductCollection()->addStoreFilter($store_id)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped',
                'downloadable'
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1)->addAttributeToSort($sortType, $sortOrder);
        if($sortType != 'name'){
				$collection->addAttributeToSort('name', $sortOrder);
		}
		//~ var_dump($sortType, $sortOrder);die;
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
            '(' . join(') OR (', $cond) . ')'
        );
        //$echo= $collection->getSelect()->__toString(); die('gcdgdv');
        $res["total"] = $collection->getSize();//count($collection);
        $collection->setPage($curr_page, $page_size);
        //~ var_dump($curr_page, $page_size);die;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        foreach ($collection as $_product) {
           	$gflag=1;
            $mofluid_product            = $product->load($_product->getId());
            $mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
            $defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
            $defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
            $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
			$thumbnailimage = $imagehelper->init($mofluid_product, 'category_page_grid')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
            try {
                $custom_options        = $mofluid_product->getOptions();
                $has_custom_option     = 0;
                foreach ($custom_options as $optionKey => $optionVal) {
                    $has_custom_option = 1;
                }
            }
            catch (\Exception $ee) {
                $has_custom_option = 0;
            }
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

             	//$defaultprice = number_format($this->getGroupedProductPrice($_product->getId(), $currentcurrencycode) , 2, '.', '');
                //$specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
                //$associatedProducts = $_product->getTypeInstance(true)->getAssociatedProducts($_product);
             	//if(count($associatedProducts)) { $gflag=1; }else{ $gflag=0; }
            }
            else
            {
            	 $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
           		 $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
            }
             if ($_product->getTypeID() == 'configurable') {
			 $defaultprice = $specialprice;
			 }
            //End sumit code
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');

           $stock = $this->stock->getStockItem($_product->getId());
           if($gflag)
           {
            $res["data"][] = array(
                "id" => $_product->getId(),
                "name" => $_product->getName(),
                "imageurl" => $thumbnailimage,
                "sku" => $_product->getSku(),
                "type" => $_product->getTypeID(),
                "spclprice" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "currencysymbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
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

    /*   * **********************get featured products*************** */

    public function ws_getFeaturedProducts($currentcurrencycode, $service, $store)
    {
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$scopeConfig = $this->_scopeconfig;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store);
        $res = array();
        $rootcateid = $storeObj->getStore()->getRootCategoryId();
        $category = $this->_category->load($rootcateid);
        $basecurrencycode   = $storeObj->getStore($store)->getBaseCurrencyCode();
        $product = $this->_product;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collection   = $product->getCollection()->addStoreFilter($store)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped'
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1);
        $collection->addCategoryFilter($category);
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
            '(' . join(') OR (', $cond) . ')'
        );
        $i = 0;
        $collection->setPage(1, 10);
        if($collection->getSize()){
			foreach ($collection as $_product) {
				if ($_product->getTypeID() == 'configurable') {
				$gflag=1;
				$mofluid_all_product_images = array();
				$mofluid_non_def_images     = array();
				$mofluid_all_product_images = array();
				$mofluid_non_def_images     = array();
				$mofluid_product            = $product->load($_product->getId());
				$mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
				$defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
				$defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
			    $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
				$thumbnailimage = $imagehelper->init($mofluid_product, 'category_page_list')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
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

			   $stock = $this->stock->getStockItem($_product->getId());
			   $res["products_list"][$i++] = array(
					"id" => $_product->getId(),
					"name" => $_product->getName(),
					"image" => $thumbnailimage,
					"type" => $_product->getTypeID(),
					"price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"special_price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"currency_symbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
					"is_stock_status" => $stock->getIsInStock()
				);
				$res["status"][0] = array(
                'Show_Status' => "1"
                );
               } }

               ###### simple ##################

               foreach ($collection as $_product) {
				if ($_product->getTypeID() == 'simple') {
				$gflag=1;
				$mofluid_all_product_images = array();
				$mofluid_non_def_images     = array();
				$mofluid_all_product_images = array();
				$mofluid_non_def_images     = array();
				$mofluid_product            = $product->load($_product->getId());
				$mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
				$defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
				$defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
			    $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
				$thumbnailimage = $imagehelper->init($mofluid_product, 'category_page_list')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
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

			   $stock = $this->stock->getStockItem($_product->getId());
			   $res["products_list"][$i++] = array(
					"id" => $_product->getId(),
					"name" => $_product->getName(),
					"image" => $thumbnailimage,
					"type" => $_product->getTypeID(),
					"price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"special_price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"currency_symbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
					"is_stock_status" => $stock->getIsInStock()
				);
				$res["status"][0] = array(
                'Show_Status' => "1"
                );
               } }

           }else{
			   $res["status"][0] = array(
                'Show_Status' => "0"
                );
		   }
        return ($res);

	}
    /* ***********************get new products*************** */

    public function ws_getNewProducts($currentcurrencycode, $service, $store, $curr_page, $page_size, $sortType, $sortOrder)
    {
		if($sortType == null || $sortType == 'null'){
			$sortType = 'name';
		}
		if($sortOrder == null || $sortOrder == 'null'){
			$sortOrder = 'DESC';
		}
		if($curr_page == null || $curr_page == 'null'){
			$curr_page = 1;
		}
		if($page_size == null || $page_size == 'null'){
			$page_size = 20;
		}
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$scopeConfig = $this->_scopeconfig;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store);
        $rootcateid = $storeObj->getStore()->getRootCategoryId();
        $category = $this->_category->load($rootcateid);
        $res = array();
        $basecurrencycode   = $storeObj->getStore($store)->getBaseCurrencyCode();
        $product = $this->_product;

         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		$prodCollection = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product\CollectionFactory');
        $collection = $prodCollection->create()
            ->addAttributeToSelect('*')->addFieldToFilter('type_id', array(
           'in' => array(
                'simple',
                'configurable',
                'grouped'
            )
        ));
        $collection->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1)->addAttributeToSort($sortType, $sortOrder);
        if($sortType != 'name' && $sortType != 'created_at'){
				$collection->addAttributeToSort('name', $sortOrder);
		}

        $collection->addCategoryFilter($category);
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
            '(' . join(') OR (', $cond) . ')'
        );
        $i = 0;

        $collection->setPage($curr_page, $page_size);
        //~ var_dump($collection->getSelect()->__toString());die;
        if($collection->getSize()){
			foreach ($collection as $_product) {
				 if ($_product->getTypeID() == 'configurable' ) {
				$gflag=1;
				$mofluid_product            = $this->_product->load($_product->getId());
				$mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
				$defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
				$defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
			    $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
				$thumbnailimage = $imagehelper->init($mofluid_product, 'category_page_list')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
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

			   $stock = $this->stock->getStockItem($_product->getId());
			   $res["products_list"][$i++] = array(
					"id" => $_product->getId(),
					"name" => $_product->getName(),
					"image" => $thumbnailimage,
					"type" => $_product->getTypeID(),
					"price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"special_price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"currency_symbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
					"is_stock_status" => $stock->getIsInStock()
				);
				$res["status"][0] = array(
                'Show_Status' => "1"
                );
               } }
               ####### configurable#############

               foreach ($collection as $_product) {
				   if ($_product->getTypeID() == 'simple' || $_product->getTypeID() == 'grouped') {
				$gflag=1;
				$mofluid_product            = $this->_product->load($_product->getId());
				$mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
				$defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
				$defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
				$imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
				$image = $imagehelper->init($mofluid_product, 'category_page_list')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
			   //~ var_dump($image);die;
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
				 /*if ($_product->getTypeID() == 'grouped') {

					$defaultprice = number_format($this->getGroupedProductPrice($_product->getId(), $currentcurrencycode) , 2, '.', '');
					$specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
				 }else{
					 $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
					 $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
				 }*/
				//End sumit code
				if($defaultprice == $specialprice)
					$specialprice = number_format(0, 2, '.', '');

			   $stock = $this->stock->getStockItem($_product->getId());
			   $res["products_list"][$i++] = array(
					"id" => $_product->getId(),
					"name" => $_product->getName(),
					"image" => $image,
					"type" => $_product->getTypeID(),
					"price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"special_price" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
					"currency_symbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
					"is_stock_status" => $stock->getIsInStock()
				);
				$res["status"][0] = array(
                'Show_Status' => "1"
                );
               } }
           }else{
			   $res["status"][0] = array(
                'Show_Status' => "0"
                );
		   }
        return ($res);

	}
    public function getGroupedProductPrice($product_id, $currency)
    {
		$product = $this->_product;
		$storeObj = $this->_storeManager;
		$directory = $this->_directory;
        //$group_collection            = $this->grouped->getAssociatedProducts($product->load($product_id));
        //$group_collection = $this->grouped->getAssociatedProducts($this->_product->load($product_id));
        $base_currency    = $storeObj->getStore()->getBaseCurrencyCode();
        //$group_collection = $group->getAssociatedProductCollection();
        $prices           = array();
        /*foreach ($group_collection as $group_product) {
            $_product = $product->load($group_product->getId());
            $prices[] = round(floatval($directory->currencyConvert($_product->getFinalPrice(), $base_currency, $currency)), 2);
        }*/
        sort( $prices);
        $prices = array_shift($prices);
        return $prices;
    }

    /*   * *Convert Currency** */

    public function convert_currency($price, $from, $to)
    {
        $newPrice = $this->_directory->currencyConvert($price, $from, $to);
        return $newPrice;
    }

    function ws_validatecurrency($store, $service, $currency, $paymentgateway)
    {
		$msg = '';
        $cache = $this->_cache;
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

    public function ws_createuser($store, $service, $firstname, $lastname, $email, $password)
    {
               $firstname = base64_decode($firstname);
		$lastname  = base64_decode($lastname);
		$password   =base64_decode($password);

        $res                  = array();
        $storeObj = $this->_storeManager;
        $storemodel = $this->_store;
        $websiteId               = $storemodel->load($store)->getWebsiteId();
        $customerObj             = $this->_customer;
        $res["firstname"]        = $firstname;
		$res["lastname"]         = $lastname;
		$res["email"]            = $email;
		$res["password"]         = $password;
		$res["status"]           = 0;
		$res["id"]               = 0;
		//print_r($res);die();
		//print_r($this->_customer->setWebsiteId($websiteId)->loadByEmail($email)->getData());die();
		$cust  = $this->_customer->setWebsiteId($websiteId)->loadByEmail($email);
		if ($cust->getId()) {
			$res["id"]     = $cust->getId();
			$res["status"] = 0;
		} else {
			try{
				$customer = $this->_customer;
				$customer->website_id = $websiteId;
				$customer->setCurrentStore($store);
				// If new, save customer information
				$customer->setWebsiteId($websiteId)->setFirstname($firstname)->setLastname($lastname)->setEmail($email)->setPassword($password)->save();
				$customer->sendNewAccountEmail($type = 'registered', $backUrl = '', $store);
				$res["id"]     = $customer->getId();
				$res["status"] = 1;
				$res["stripecustid"]        = '0';
				if($customer->getMdStripeCustomerId() != null){
					$res["stripecustid"]        = $customer->getMdStripeCustomerId();
				}
			}catch (\Exception $e) {

			}
        }
        return $res;
    }

    public function ws_productdetail($store_id, $service, $productid, $currentcurrencycode)
    {
        $storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$scopeConfig = $this->_scopeconfig;
		$taxcalculation = $this->_taxcalculation;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store_id);
        $custom_attr       = array();
        $product = $this->_product->load($productid);
        $attributes        = $product->getAttributes();
        $stock = $this->stock->getStockItem($product->getId());
        //$echo= count($attributes);
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
            $res["currencysymbol"] = $this->_currency->getCurrency($currentcurrencycode)->getSymbol();


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

            /*Added by Mofluid team to resolve spcl price issue in 1.17*/
            $defaultprice =  number_format($product->getPrice(), 2, '.', '');
            $specialprice =  number_format($product->getFinalPrice(), 2, '.', '');
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');


            $res["price"]    =  number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            $res["sprice"]   = number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', '');
            $res["tax"]      = number_format($b, 2);
            $tax_type   = $scopeConfig->getValue('tax/calculation/price_includes_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $res["tax_type"] = $tax_type;

            $res["has_custom_option"] = $has_custom_option;
            if ($has_custom_option) {
                $res["custom_option"] = $all_custom_option_array;
            }
        $res["custom_attribute"] = $custom_attr;
        return ($res);
    }

/*************
	API Name 	: productdetaildescription
	Description	: 1. Updated API for Product Desscription for Mofluid app Product detail page.
				  2. Common API to support Simple & configureable products.
*************/

    public function ws_productdetailDescription($store_id, $service, $productid, $currentcurrencycode){
    $storeObj = $this->_storeManager;
    $cache = $this->_cache;
    $scopeConfig = $this->_scopeconfig;
    $taxcalculation = $this->_taxcalculation;
    $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    $storeObj->getStore()->setCurrentStore($store_id);
    $custom_attr = array();
    $product = $this->_product->load($productid);
    $attributes = $product->getAttributes();
    $stock = $this->stock->getStockItem($product->getId());
    $custom_attr_count = 0;
    foreach($attributes as $attribute){
        if ($attribute->getIsVisibleOnFront()){
            $attributeCode = $attribute->getAttributeCode();
            $label = $attribute->getFrontend()->getLabel($product);
            $value = $attribute->getFrontend()->getValue($product);
            $custom_attr["data"][$custom_attr_count]["attr_code"] = $attributeCode;
            $custom_attr["data"][$custom_attr_count]["attr_label"] = $label;
            $custom_attr["data"][$custom_attr_count]["attr_value"] = $value;
            ++$custom_attr_count;
            }
        }
    $custom_attr["total"] = $custom_attr_count;
    $res = array();
    $mofluid_all_product_images = array();
    $mofluid_non_def_images = array();
    $mofluid_product = $product;
    $mofluid_baseimage = $media_url . 'catalog/product' . $mofluid_product->getImage();
    $product_thumbimage = $media_url . 'catalog/product' . $mofluid_product->getThumbnail();

    foreach($mofluid_product->getMediaGalleryImages() as $mofluid_image){
        $mofluid_imagecame = $mofluid_image->getUrl();
        if ($mofluid_baseimage == $mofluid_imagecame){
            $mofluid_all_product_images[] = $mofluid_image->getUrl();
            }else{
            $mofluid_non_def_images[] = $mofluid_image->getUrl();
            }
        }

    $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);

    // get base currency from magento

    $basecurrencycode = $storeObj->getStore($store_id)->getBaseCurrencyCode();
    $a = $product;
    $store = $storeObj->getStore($store_id);
    $taxClassId = $product->getTaxClassId();
    $percent = $taxcalculation->getDefaultCalculatedRate($taxClassId, null, $store); //->getRate($request->setProductClassId($taxClassId));
    $b = (($percent) / 100) * ($product->getFinalPrice());
    $all_custom_option_array = array();
    $attVal = $product->getOptions();
    $optStr = "";
    $inc = 0;
    $has_custom_option = 0;
    foreach($attVal as $optionKey => $optionVal){
        $has_custom_option = 1;
        $all_custom_option_array[$inc]['custom_option_name'] = $optionVal->getTitle();
        $all_custom_option_array[$inc]['custom_option_id'] = $optionVal->getId();
        $all_custom_option_array[$inc]['custom_option_is_required'] = $optionVal->getIsRequire();
        $all_custom_option_array[$inc]['custom_option_type'] = $optionVal->getType();
        $all_custom_option_array[$inc]['sort_order'] = $optionVal->getSortOrder();
        $all_custom_option_array[$inc]['all'] = $optionVal->getData();
        if ($all_custom_option_array[$inc]['all']['default_price_type'] == "percent"){
            $all_custom_option_array[$inc]['all']['price'] = number_format((($product->getFinalPrice() * round($all_custom_option_array[$inc]['all']['price'] * 10, 2) / 10) / 100) , 2);
        }else{
            $all_custom_option_array[$inc]['all']['price'] = number_format($all_custom_option_array[$inc]['all']['price'], 2);
        }
		$all_custom_option_array[$inc]['all']['price'] = str_replace(",", "", $all_custom_option_array[$inc]['all']['price']);
        $all_custom_option_array[$inc]['all']['price'] = strval(round($this->convert_currency($all_custom_option_array[$inc]['all']['price'], $basecurrencycode, $currentcurrencycode) , 2));
        //$all_custom_option_array[$inc]['custom_option_value_array'];
        $inner_inc = 0;
        foreach($optionVal->getValues() as $valuesKey => $valuesVal){
            $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['id'] = $valuesVal->getId();
            $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['title'] = $valuesVal->getTitle();
            $defaultcustomprice = str_replace(",", "", ($valuesVal->getPrice()));
            $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode) , 2));
            $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price_type'] = $valuesVal->getPriceType();
            $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sku'] = $valuesVal->getSku();
            $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['sort_order'] = $valuesVal->getSortOrder();
            if ($valuesVal->getPriceType() == "percent"){
                $defaultcustomprice = str_replace(",", "", ($product->getFinalPrice()));
                $customproductprice = strval(round($this->convert_currency($defaultcustomprice, $basecurrencycode, $currentcurrencycode) , 2));
                $all_custom_option_array[$inc]['custom_option_value_array'][$inner_inc]['price'] = str_replace(",", "", round((floatval($customproductprice) * floatval(round($valuesVal->getPrice() , 1)) / 100) , 2));
                }
            $inner_inc++;
            }
        $inc++;
        }
    $config_option = array();
    $res["id"] 			= $product->getId();
    $res["sku"] 		= $product->getSku();
    $res["name"] 		= $product->getName();
    $res["category"] 	= $product->getCategoryIds(); //'category';
    $res["img"] 		= $product_thumbimage;
    $res["url"] 		= $product->getProductUrl();
    $res["description"] = strip_tags($product->getDescription());
    $res["shortdes"] 	= $product->getShortDescription();
    $res["quantity"] 	= (string)$stock->getQty();
    $res["manage_stock"]= $stock->getManageStock() ? 1 : 0;
    $res["is_in_stock"] = $stock->getIsInStock() ? 1 : 0;
    $res["visibility"] 	= $product->isVisibleInSiteVisibility(); //getVisibility();
    $res["type"] 		= $product->getTypeID();
    $res["weight"] 		= $product->getWeight();
    $res["status"] 		= $product->getStatus();

    // convert price from base currency to current currency

    $res["currencysymbol"] = $this->_currency->getCurrency($currentcurrencycode)->getSymbol();
    $defaultprice = str_replace(",", "", ($product->getPrice()));
    $discountprice = str_replace(",", "", number_format($product->getFinalPrice() , 2));
    $res["discount"] = strval(round($this->convert_currency($discountprice, $basecurrencycode, $currentcurrencycode) , 2));
	$defaultshipping = $scopeConfig ->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	$res["shipping"] = strval(round($this->convert_currency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));
    $defaultsprice = str_replace(",", "", ($product->getSpecialprice()));

    // Get the Special Price
    $specialprice = $product->getSpecialPrice();

    // Get the Special Price FROM date
    $specialPriceFromDate = $product->getSpecialFromDate();

    // Get the Special Price TO date
    $specialPriceToDate = $product->getSpecialToDate();

    // Get Current date
    $today = time();
    if ($specialprice){
        if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)){
            $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode) , 2));
        }else{
            $specialprice = 0;
        }
    }else{
        $specialprice = 0;
    }

    if (floatval($discountprice)){
        if (floatval($discountprice) < floatval($defaultprice)){
            $defaultprice = floatval($discountprice);
        }
    }

    /*Added by Mofluid team to resolve spcl price issue in 1.17*/
    $defaultprice = number_format($product->getPrice() , 2, '.', '');
    $specialprice = number_format($product->getFinalPrice() , 2, '.', '');
    if ($defaultprice == $specialprice) $specialprice = number_format(0, 2, '.', '');
    $res["price"] = number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode) , 2, '.', '');
    $res["sprice"] = number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode) , 2, '.', '');
    $res["tax"] = number_format($b, 2);
	$tax_type   = $scopeConfig ->getValue('tax/calculation/price_includes_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	$res["tax_type"] = $tax_type;
    $res["has_custom_option"] = $has_custom_option;
    if ($has_custom_option){
        $res["custom_option"] = $all_custom_option_array;
    }

    $res["custom_attribute"] = $custom_attr;
    $res["config_option"] = $config_option;

    /******** Block starts for Configureable products ************/

    if (($product->getTypeID() == "configurable")){
        $_configurableInstance = $product->getTypeInstance(true);
        $productAttributeOptions = $_configurableInstance->getConfigurableAttributesAsArray($product);
        foreach($productAttributeOptions as $productAttribute){
            $config_option[] = $productAttribute['label'];
        }
        $res1 = $this->getConfigurableProductData($product, $currentcurrencycode, $defaultsprice, $basecurrencycode, $store_id);
        $res = array_merge($res, $res1);
        $simple_collection = $_configurableInstance->getUsedProducts($product);
        $qty = 0;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$StockState = $objectManager->get('\Magento\CatalogInventory\Api\StockStateInterface');
		foreach($simple_collection as $product1){
            $qty = $qty + ($StockState->getStockQty($product1->getId()));
        }
    	$res['quantity'] = (string)$qty;
    }else{
        $configurable_array = [];
    }
    /******** Block ends for Configureable products ************/


$res['config_option'] = $config_option;
return ($res);
}

/*************
	Func. Name 	: getConfigurableProductData
	Description	: 1. Return Details of a configureable product
*************/

public function getConfigurableProductData($_product,$currentcurrencycode,$defaultsprice,$basecurrencycode,$store){
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$locale = $this->_currency;
		$scopeConfig = $this->_scopeconfig;
		$taxcalculation = $this->_taxcalculation;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
		$storeObj->getStore()->setCurrentStore($store);
		$configurable_count = 0;
		$_configurableInstance = $_product->getTypeInstance(true);
		$productAttributeOptions = $_configurableInstance->getConfigurableAttributes($_product);
		 $simple_collection = $_configurableInstance->getUsedProducts($_product);
		$relation_count               = 0;
	$configurable_relation = array();
	foreach ($simple_collection as $product) {
		$data = array();

		$configurable_count = 0;
		$total = Count($productAttributeOptions);
	foreach ($productAttributeOptions as $attribute) {

			$currentcurrencycode;
			$productAttribute                                              = $attribute->getProductAttribute();
			$productAttributeId                                            = $productAttribute->getId();
			$attributeValue                                                = $product->getData($productAttribute->getAttributeCode());
			$attributeLabel                                                = $product->getData($productAttribute->getValue());

			$config_option_attribute =	$this->ws_get_configurable_option_attributes($attributeValue, $attribute->getLabel(), $_product->getId(), $currentcurrencycode,$store);
			$data[$attribute->getLabel()]= $config_option_attribute ;
			$configurable_array1[$configurable_count]["data"]  = $config_option_attribute ;

			try {
				$configurable_curr_arr = (array) $configurable_array1[$configurable_count]["data"];
				if (isset($configurable_relation[$relation_count])) {
					$configurable_relation[$relation_count] = $configurable_relation[$relation_count] . ',' . str_replace(',', '', str_replace(' ','', $configurable_curr_arr["label"]));
				} else {
					$configurable_relation[$relation_count] = str_replace(',', '', str_replace(' ','', $configurable_curr_arr["label"]));
				}
			}
			catch (\Exception $err) {
				$echo= 'Error : ' . $err->getMessage();
				$this->getResponse()->setBody($echo);
			}
			$configurable_count++;
	}
	   $relation_count++;
	$res = array('config_relation'=>$configurable_relation);
	$stockdat                   = $this->stock->getStockItem($product->getId());
	$configurable_array[] = array(   "prod_id"=>$product->getId(),
					"is_required"    		=> $productAttribute->getIsRequired(),
					"sku"  					=> $product->getSku(),
					"name"          		=> $product->getName(),
					"spclprice"    			=> 0,
					"price"        			=>number_format($product->getPrice(), 3),
					"currencysymbol" 		=> $locale->getCurrency($currentcurrencycode)->getSymbol(),
					"created_date"  		=> $product->getCreatedAt(),
					"is_in_stock" 			=> $stockdat->getIsInStock()?1:0,
					"stock_quantity" 		=> $stockdat->getQty(),
					"type"					=> $product->getTypeID(),
					"shipping"   			=> $this->_scopeconfig->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE),
					"Total_config" 			=> $total,
					"data"          		=> $data

			);
	}
 $res['config_attributes']=$configurable_array;
 return ($res);
}

/*************
	Func. Name 	: ws_get_configurable_option_attributes
	Description	: 1. Mapping configureable products option
*************/

	function ws_get_configurable_option_attributes($selectedValue, $label, $productid, $currentcurrencycode,$store){
		$storeObj = $this->_storeManager;
		$basecurrencycode = $storeObj->getStore()->getBaseCurrencyCode();
		$product_data            = $this->_product->load($productid);
		$productAttributeOptions = $product_data->getTypeInstance(true)->getConfigurableAttributesAsArray($product_data);
		$simple_collection            = $this->configurable->getUsedProductIds($product_data);
		$attributeOptions        = array();
		$count                   = 0;
		$colors  =  array( 'aliceblue'=>'F0F8FF', 'antiquewhite'=>'FAEBD7', 'aqua'=>'00FFFF', 'aquamarine'=>'7FFFD4',
		'azure'=>'F0FFFF', 'beige'=>'F5F5DC', 'bisque'=>'FFE4C4', 'black'=>'000000', 'blanchedalmond '=>'FFEBCD',
		'blue'=>'0000FF', 'blueviolet'=>'8A2BE2', 'brown'=>'A52A2A', 'burlywood'=>'DEB887', 'cadetblue'=>'5F9EA0',
		'chartreuse'=>'7FFF00', 'chocolate'=>'D2691E', 'coral'=>'FF7F50', 'cornflowerblue'=>'6495ED', 'cornsilk'=>'FFF8DC',
		'crimson'=>'DC143C', 'cyan'=>'00FFFF', 'darkblue'=>'00008B', 'darkcyan'=>'008B8B', 'darkgoldenrod'=>'B8860B',
		'darkgray'=>'A9A9A9', 'darkgreen'=>'006400', 'darkgrey'=>'A9A9A9', 'darkkhaki'=>'BDB76B', 'darkmagenta'=>'8B008B',
		'darkolivegreen'=>'556B2F', 'darkorange'=>'FF8C00', 'darkorchid'=>'9932CC', 'darkred'=>'8B0000', 'darksalmon'=>'E9967A',
		'darkseagreen'=>'8FBC8F', 'darkslateblue'=>'483D8B', 'darkslategray'=>'2F4F4F', 'darkslategrey'=>'2F4F4F',
		'darkturquoise'=>'00CED1', 'darkviolet'=>'9400D3', 'deeppink'=>'FF1493', 'deepskyblue'=>'00BFFF', 'dimgray'=>'696969',
		'dimgrey'=>'696969', 'dodgerblue'=>'1E90FF', 'firebrick'=>'B22222', 'floralwhite'=>'FFFAF0', 'forestgreen'=>'228B22',
		'fuchsia'=>'FF00FF', 'gainsboro'=>'DCDCDC', 'ghostwhite'=>'F8F8FF', 'gold'=>'FFD700', 'goldenrod'=>'DAA520',
		'gray'=>'808080', 'green'=>'008000', 'greenyellow'=>'ADFF2F', 'grey'=>'808080', 'honeydew'=>'F0FFF0', 'hotpink'=>'FF69B4',
		'indianred'=>'CD5C5C', 'indigo'=>'4B0082', 'ivory'=>'FFFFF0', 'khaki'=>'F0E68C', 'lavender'=>'E6E6FA',
		'lavenderblush'=>'FFF0F5', 'lawngreen'=>'7CFC00', 'lemonchiffon'=>'FFFACD', 'lightblue'=>'ADD8E6', 'lightcoral'=>'F08080',
		'lightcyan'=>'E0FFFF', 'lightgoldenrodyellow'=>'FAFAD2', 'lightgray'=>'D3D3D3', 'lightgreen'=>'90EE90',
		'lightgrey'=>'D3D3D3', 'lightpink'=>'FFB6C1', 'lightsalmon'=>'FFA07A', 'lightseagreen'=>'20B2AA',
		'lightskyblue'=>'87CEFA', 'lightslategray'=>'778899', 'lightslategrey'=>'778899', 'lightsteelblue'=>'B0C4DE',
		'lightyellow'=>'FFFFE0', 'lime'=>'00FF00', 'limegreen'=>'32CD32', 'linen'=>'FAF0E6', 'magenta'=>'FF00FF',
		'maroon'=>'800000', 'mediumaquamarine'=>'66CDAA', 'mediumblue'=>'0000CD', 'mediumorchid'=>'BA55D3',
		'mediumpurple'=>'9370D0', 'mediumseagreen'=>'3CB371', 'mediumslateblue'=>'7B68EE', 'mediumspringgreen'=>'00FA9A',
		'mediumturquoise'=>'48D1CC', 'mediumvioletred'=>'C71585', 'midnightblue'=>'191970', 'mintcream'=>'F5FFFA',
		'mistyrose'=>'FFE4E1', 'moccasin'=>'FFE4B5', 'navajowhite'=>'FFDEAD', 'navy'=>'000080', 'oldlace'=>'FDF5E6',
		'olive'=>'808000', 'olivedrab'=>'6B8E23', 'orange'=>'FFA500', 'orangered'=>'FF4500', 'orchid'=>'DA70D6',
		'palegoldenrod'=>'EEE8AA', 'palegreen'=>'98FB98', 'paleturquoise'=>'AFEEEE', 'palevioletred'=>'DB7093',
		'papayawhip'=>'FFEFD5', 'peachpuff'=>'FFDAB9', 'peru'=>'CD853F', 'pink'=>'FFC0CB', 'plum'=>'DDA0DD',
		'powderblue'=>'B0E0E6', 'purple'=>'800080', 'red'=>'FF0000', 'rosybrown'=>'BC8F8F', 'royalblue'=>'4169E1',
		'saddlebrown'=>'8B4513', 'salmon'=>'FA8072', 'sandybrown'=>'F4A460', 'seagreen'=>'2E8B57', 'seashell'=>'FFF5EE',
		'sienna'=>'A0522D', 'silver'=>'C0C0C0', 'skyblue'=>'87CEEB', 'slateblue'=>'6A5ACD', 'slategray'=>'708090',
		'slategrey'=>'708090', 'snow'=>'FFFAFA', 'springgreen'=>'00FF7F', 'steelblue'=>'4682B4', 'tan'=>'D2B48C',
		'teal'=>'008080', 'thistle'=>'D8BFD8', 'tomato'=>'FF6347', 'turquoise'=>'40E0D0', 'violet'=>'EE82EE', 'wheat'=>'F5DEB3',
		'white'=>'FFFFFF', 'whitesmoke'=>'F5F5F5', 'yellow'=>'FFFF00', 'charcoal'=>'36454F', 'yellowgreen'=>'9ACD32');
		foreach ($productAttributeOptions as $productAttribute) {
			$count = 0;
			foreach ($productAttribute['values'] as $attribute) {
				$attributeOptions[$productAttribute['label']][$attribute['value_index']]["value_index"]                = $attribute['value_index'];
				$attributeOptions[$productAttribute['label']][$attribute['value_index']]["label"]                      = $attribute['label'];
				$attributeOptions[$productAttribute['label']][$attribute['value_index']]["attribute_id"]      = $productAttribute['attribute_id'];
			   // $defaultprice                                                                             = str_replace(",", "", ($attribute['pricing_value']));

				/*if ($attribute['is_percent'] == 1) {
						$defaultproductprice                                                                      = str_replace(",", "", ($product_data->getFinalPrice()));
						$productprice                                                                             = strval(round($this->convert_currency($defaultproductprice, $basecurrencycode, $currentcurrencycode), 2));
						$attributeOptions[$productAttribute['label']][$attribute['value_index']]["pricing_value"] = str_replace(",", "", round(((floatval($productprice) * floatval($attribute['pricing_value'])) / 100), 2));

				}*/
				  if($productAttribute['label'] == 'Color'){

					$cname = strtolower($attribute['label']);
					if(isset($colors[$cname])){
						$attributeOptions[$productAttribute['label']][$attribute['value_index']]["color_code"]                 = '#'.$colors[$cname];
					}else{
						$attributeOptions[$productAttribute['label']][$attribute['value_index']]["color_code"]             =  '#00FFFF';
					}
				}
				$count++;
			}
		}
		return ($attributeOptions[$label][$selectedValue]);
	}

    /********* deprecated function for configurable products *******/
    function get_configurable_products_description($productid, $currentcurrencycode,$store)
    {
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$locale = $this->_currency;
		$scopeConfig = $this->_scopeconfig;
		$taxcalculation = $this->_taxcalculation;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store);
        $custom_attr       = array();
        $product_data = $this->_product->load($productid);
        $attributes        = $product_data->getAttributes();
        $stock = $this->stock->getStockItem($product_data->getId());
        $basecurrencycode = $storeObj->getStore()->getBaseCurrencyCode();
        try {
            if ($product_data->getTypeID() == "configurable") {

				$productAttributeOptions =array();
                $productAttributeOptions      = $product_data->getTypeInstance(true)->getConfigurableAttributes($product_data);
                $simple_collection            = $this->configurable->getUsedProductIds($product_data);
				$simpleproprices              = array();
				foreach ($simple_collection as $simple_product) {
					$price = $this->_product->load($simple_product)->getPrice();
					array_push($simpleproprices, $price);
				}
                $configurable_array_selection = array();
                $configurable_array           = array();
                $configurable_count           = 0;
                $relation_count               = 0;
                foreach ($simple_collection as $simple_product) {
					$product                    = $this->_product->load($simple_product);
					$stockdat                   = $this->stock->getStockItem($product->getId());
                    $a                          = $this->_product->load($product->getId());
                    $taxClassId                 = $a->getData("tax_class_id");
    			    $taxRate                    = $this->taxcalculation->getDefaultCalculatedRate($taxClassId, null, $store);
    			    $b                          = (($taxRate)/100) *  ($a->getPrice());
                    $product_for_custom_options = $a;
                    $all_custom_option_array    = array();
                    $attVal                     = $product_for_custom_options->getOptions();
                    $optStr                     = "";
                    $inc                        = 0;
                    $minsimpleprice             = min($simpleproprices);
                    $pricevalue					= $product->getPrice()-$minsimpleprice;
                    //$echo= "<pre>"; print_r($minsimpleprice); die('dd');
                    $configurable_count = 0;
                    foreach ($productAttributeOptions as $attribute) {
                        $productAttribute                                              = $attribute->getProductAttribute();
                        $productAttributeId                                            = $productAttribute->getId();
                        $attributeValue                                                = $product->getData($productAttribute->getAttributeCode());
                        $attributeLabel                                                = $product->getData($productAttribute->getValue());
                        $configurable_array[$configurable_count]["productAttributeId"] = $productAttributeId;
                        $configurable_array[$configurable_count]["selected_value"]     = $attributeValue;
                        $configurable_array[$configurable_count]["label"]              = $attribute->getLabel();
                        $configurable_array[$configurable_count]["is_required"]        = $productAttribute->getIsRequired();
                        $configurable_array[$configurable_count]["id"]                 = $product->getId();
                        $configurable_array[$configurable_count]["sku"]                = $product->getSku();
                        $configurable_array[$configurable_count]["name"]               = $product->getName();
                        $defaultsplprice                                               = str_replace(",", "", number_format($product->getFinalPrice(), 2));
                        $configurable_array[$configurable_count]["spclprice"]          = strval($this->convert_currency($defaultsplprice, $basecurrencycode, $currentcurrencycode));
                        $configurable_array[$configurable_count]["price"]              = number_format($product->getPrice(), 2);
                        $configurable_array[$configurable_count]["currencysymbol"]     = $locale->getCurrency($currentcurrencycode)->getSymbol();
                        $configurable_array[$configurable_count]["created_date"]       = $product->getCreatedAt();
                        $configurable_array[$configurable_count]["is_in_stock"]        = $stockdat->getIsInStock()?1:0;
                        $configurable_array[$configurable_count]["stock_quantity"]     = $stockdat->getQty();
                        $configurable_array[$configurable_count]["type"]               = $product->getTypeID();
                        $configurable_array[$configurable_count]["shipping"]           = $this->_scopeconfig->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                        $configurable_array[$configurable_count]["data"]               = $this->ws_get_configurable_option_attributes($attributeValue, $attribute->getLabel(), $productid, $currentcurrencycode,$store,$pricevalue);
                        $configurable_array[$configurable_count]["tax"]                = number_format($b, 2);
         				$stock_data = $stockdat->getData();
                        try {
                            $configurable_curr_arr = (array) $configurable_array[$configurable_count]["data"];
                            if (isset($configurable_relation[$relation_count])) {
                                $configurable_relation[$relation_count] = $configurable_relation[$relation_count] . ', ' . str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                            } else {
                                $configurable_relation[$relation_count] = str_replace(',', '', str_replace(' ', '', $configurable_curr_arr["label"]));
                            }
                        }
                        catch (\Exception $err) {
                            $echo= 'Error : ' . $err->getMessage();
														$this->getResponse()->setBody($echo);
                        }
                        $configurable_count++;
                    }
                    $relation_count++;
                    $configurable_array_selection[] = $configurable_array;
                }
                $configurable_array_selection['relation'] = $configurable_relation;
                $configurable_product_parent              = array();
                $parent_a                                 = $product_data;

                $parent_taxClassId                        = $a->getData("tax_class_id");
    			$parenttaxRate                            = $this->taxcalculation->getDefaultCalculatedRate($parent_taxClassId, null, $store);
    		    $parent_b                                 = (($parenttaxRate)/100) *  ($a->getPrice());
                $parent_all_custom_option_array = array();
                $parent_attVal                  = $product_data->getOptions();
                $parent_optStr                  = "";
                $parent_inc                     = 0;
                $has_custom_option              = 0;
                foreach ($parent_attVal as $parent_optionKey => $parent_optionVal) {
                    $parent_all_custom_option_array[$parent_inc]['custom_option_name']        = $parent_optionVal->getTitle();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_id']          = $parent_optionVal->getId();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_is_required'] = $parent_optionVal->getIsRequired();
                    $parent_all_custom_option_array[$parent_inc]['custom_option_type']        = $parent_optionVal->getType();
                    $parent_all_custom_option_array[$parent_inc]['sort_order']                = $parent_optionVal->getSortOrder();
                    $parent_all_custom_option_array[$parent_inc]['all']                       = $parent_optionVal->getData();
                    $parent_all_custom_option_array[$parent_inc]['all']['price']		      = 0;

                    if ($parent_all_custom_option_array[$parent_inc]['all']['default_price_type'] == "percent") {
                        $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format((($product->getPrice() * $parent_all_custom_option_array[$parent_inc]['all']['price']) / 100), 2);
                    } else {
                        $parent_all_custom_option_array[$parent_inc]['all']['price'] = number_format($parent_all_custom_option_array[$inc]['all']['price'], 2);
                    }

                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'];
                    $parent_inner_inc  = 0;
                    $has_custom_option = 1;
                    foreach ($parent_optionVal->getValues() as $parent_valuesKey => $parent_valuesVal) {
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['id']         = $parent_valuesVal->getId();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['title']      = $parent_valuesVal->getTitle();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price']      = number_format($parent_valuesVal->getPrice(), 0);
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['price_type'] = $parent_valuesVal->getPriceType();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sku']        = $parent_valuesVal->getSku();
                        $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'][$parent_inner_inc]['sort_order'] = $parent_valuesVal->getSortOrder();

                        $parent_inner_inc++;
                    }
                    $parent_inc++;
                }
                $configurable_product_parent["id"]       = $product_data->getId();
                $configurable_product_parent["sku"]      = $product_data->getSku();
                $configurable_product_parent["name"]     = $product_data->getName();
                $configurable_product_parent["category"] = $product_data->getCategoryIds();
                $configurable_product_parent["discount"] = number_format($product_data->getFinalPrice(), 2);
                $configurable_product_parent["shipping"] = $this->_scopeconfig->getValue('carriers/flatrate/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                $defaultprice                            = str_replace(",", "", ($product_data->getPrice()));
                $configurable_product_parent["price"]    = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
                $defaultsprice                           = str_replace(",", "", ($product_data->getFinalPrice()));
                if($defaultprice == $defaultsprice){
                	$defaultsprice                       = 0;
                }
                $configurable_product_parent["sprice"] = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                $configurable_product_parent["currencysymbol"]    = $locale->getCurrency($currentcurrencycode)->getSymbol();
                $configurable_product_parent["url"]               = $product_data->getProductUrl();
                $configurable_product_parent["description"]       = $product_data->getDescription();
                $configurable_product_parent["shortdes"]          = $product_data->getShortDescription();
                $configurable_product_parent["type"]              = $product_data->getTypeID();
                $configurable_product_parent["created_date"]      = $product_data->getCreatedAt();
                $configurable_product_parent["is_in_stock"]       = $stock->getIsInStock()?1:0;
                $configurable_product_parent["quantity"]          = $stock->getQty();
                $configurable_product_parent["visibility"]        = $product_data->isVisibleInSiteVisibility();
                $configurable_product_parent["weight"]            = $product_data->getWeight();
                $configurable_product_parent["status"]            = $product_data->getStatus();
                $configurable_product_parent["variation"]         = $product_data->getColor();
                $configurable_product_parent["custom_option"]     = $parent_all_custom_option_array;
                $configurable_product_parent["tax"]               = number_format($parent_b, 2);
                $configurable_product_parent["has_custom_option"] = $has_custom_option;
                $configurable_array_selection["parent"] = $configurable_product_parent;
                $configurable_array_selection["size"]   = sizeof($configurable_array_selection);
                $custom_attr       = array();
                $attributes        = $product_data->getAttributes();
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
                $custom_attr["total"]                             = $custom_attr_count;
                $configurable_array_selection["custom_attribute"] = $custom_attr;

                return $configurable_array_selection;
            } else
                return "Product Id " . $productid . " is not a Configurable Product";
        }
        catch (\Exception $ex) {
            return "Error";
        }
    }

    function get_configurable_products_image($productid, $currentcurrencycode)
    {
		$storeObj = $this->_storeManager;
        $cache     = $this->_cache;
        $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $cache_key = "mofluid_configurable_products_productidimg" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        try {
            $product_data = $this->_product->load($productid);
            if ($product_data->getTypeID() == "configurable") {
                $productAttributeOptions      = $product_data->getTypeInstance(true)->getConfigurableAttributes($product_data);
                $simple_collection = $this->configurable->getUsedProductIds($product_data);
                $configurable_array_selection = array();
                $configurable_array           = array();
                $configurable_count           = 0;
                $relation_count               = 0;
                //load data for children
                foreach ($simple_collection as $product) {
					$product = $this->_product->load($product);
                    $configurable_count = 0;
                    foreach ($productAttributeOptions as $attribute) {
                        $configurable_array[$configurable_count]["id"]    = $product->getId();

                        $configurable_array[$configurable_count]["name"]  = $product->getName();
                        $configurable_array[$configurable_count]["image"] = $media_url.'catalog/product' . $product->getImage();
                        $defaultsplprice                                  = str_replace(",", "", number_format($product->getSpecialprice(), 2));

                        $configurable_count++;
                    }
                    $relation_count++;
                    $configurable_array_selection[] = $configurable_array;
                }
                //load data for parent
                $mofluid_all_product_images = array();
                $mofluid_non_def_images     = array();
                $mofluid_product            = $product_data;
                $mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();

                foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
                    $mofluid_imagecame = $mofluid_image->getUrl();
                    if ($mofluid_baseimage == $mofluid_imagecame) {
                        $mofluid_all_product_images[] = $mofluid_image->getUrl();
                    } else {
                        $mofluid_non_def_images[] = $mofluid_image->getUrl();
                    }
                }
                $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
				$parent_all_custom_option_array = array();
                $parent_attVal                  = $product_data->getOptions();
                $parent_optStr                  = "";
                $parent_inc                     = 0;
                $has_custom_option              = 0;
                foreach ($parent_attVal as $parent_optionKey => $parent_optionVal) {
                    $parent_all_custom_option_array[$parent_inc]['custom_option_value_array'];
                    $parent_inner_inc  = 0;
                    $has_custom_option = 1;
                    $parent_inc++;
                }
                $configurable_product_parent["id"]    = $product_data->getId();
                $configurable_product_parent["name"]  = $product_data->getName();
                $configurable_product_parent["image"] = $mofluid_all_product_images;
                $defaultprice  = str_replace(",", "", ($product_data->getFinalPrice()));
                $defaultsprice = str_replace(",", "", ($product_data->getSpecialprice()));
                $configurable_array_selection["parent"] = $configurable_product_parent;
                $configurable_array_selection["size"]   = sizeof($configurable_array_selection);
                //$custom_attr["total"] = $custom_attr_count;
                $cache->save(json_encode($configurable_array_selection), $cache_key, array(
                    "mofluid"
                ), $this->CACHE_EXPIRY);
                return $configurable_array_selection;
            } else
                return "Product Id " . $productid . " is not a Configurable Product";
        }
        catch (\Exception $ex) {
            return "Error";
        }
    }

     /* =====================get CMS Pages================== */

    public function getallCMSPages($store, $pageId)
    {
        $page_data = array();
        $page = $this->_page->load($pageId);
        $pagehelper = $this->_pagefilter;
        $page_data["title"]   = $page->getTitle();
        $page_data["content"] = $pagehelper->getBlockFilter()->setStoreId($store)->filter($page->getContent());
        return ($page_data);
    }

    public function ws_currency($store_id, $service)
    {
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$locale = $this->_currency;
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
	public function ws_setaddress($store, $service, $customerId, $Jaddress, $user_mail, $saveaction)
    {
        //----------------------------------------------------------------------
        if ($customerId == "notlogin") {
            $result = array();
            $result['billaddress']  = 1;
            $result['shippaddress'] = 1;
        } else {
            $storeObj   = $this->_storeManager;
            $storemodel = $this->_store;
            $websiteId  = $storemodel->load($store)->getWebsiteId();
            $customer   = $this->_customer->setWebsiteId($websiteId)->loadByEmail($user_mail);
            $Jaddress               = str_replace(" ", "+", $Jaddress);
            $address                = json_decode(base64_decode($Jaddress));
            $billAdd                = $address->billing;
            $shippAdd               = $address->shipping;
            $result                 = array();
            $result['billaddress']  = 0;
            $result['shippaddress'] = 0;
            $_bill_address          = array(
                'firstname' => $billAdd->firstname,
                'lastname' => $billAdd->lastname,
                'street' => array(
                    '0' => $billAdd->street
                ),
                'city' => $billAdd->city,
                'region_id' => '',
                'region' => $billAdd->region,
                'postcode' => $billAdd->postcode,
                'country_id' => $billAdd->country,
                'telephone' => $billAdd->phone
            );
            $_shipp_address         = array(
                'firstname' => $shippAdd->firstname,
                'lastname' => $shippAdd->lastname,
                'street' => array(
                    '0' => $shippAdd->street
                ),
                'city' => $shippAdd->city,
                'region_id' => '',
                'region' => $shippAdd->region,
                'postcode' => $shippAdd->postcode,
                'country_id' => $shippAdd->country,
                'telephone' => $shippAdd->phone
            );
            if ($saveaction == 1 || $saveaction == "1") {
                $billAddress = $this->_address;
                $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');

                $shippAddress = $this->_address;
                $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
            } else {
                $billAddress  = $this->_address;
                $shippAddress = $this->_address;
                if ($defaultBillingId = $customer->getDefaultBilling()) {
                    $billAddress->load($defaultBillingId);
                    $billAddress->addData($_bill_address);
                } else {
                    $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
                }
                if ($defaultShippingId = $customer->getDefaultShipping()) {
                    $shippAddress->load($defaultShippingId);
                    $shippAddress->addData($_shipp_address);
                } else {
                    $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
                }
            }

            try {

                if (count($billAdd) > 0) {
                    if ($billAddress->save())
                        $result['billaddress'] = 1;
                }
                if (count($shippAdd) > 0) {
                    if ($shippAddress->save())
                        $result['shippaddress'] = 1;
                }
            }
            catch (\Exception $ex) {
                //Zend_Debug::dump($ex->getMessage());
            }
        }
        return $result;

        //---------------------------------------------------------------------
    }
    public function ws_checkout($store, $service, $theme, $currentcurrencycode)
    {
        $scopeConfig = $this->_scopeconfig;
        $res = array();
        $checkout_type   = $scopeConfig ->getValue('checkout/options/guest_checkout', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $res['checkout'] = $checkout_type;
        return $res;

    }
    public function ws_search($store_id, $service, $search_data, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode)
    {

		if($sortType == null || $sortType == 'null'){
			$sortType = 'name';
		}
		if($sortOrder == null || $sortOrder == 'null'){
			$sortOrder = 'ASC';
		}
		if($curr_page == null || $curr_page == 'null'){
			$curr_page = 1;
		}
		if($page_size == null || $page_size == 'null'){
			$page_size = 10;
		}
		$search_data = base64_decode($search_data);

		$search_condition[]['like'] = '%' . $search_data . '%';
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
		$scopeConfig = $this->_scopeconfig;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store_id);
        $res = array();
        $basecurrencycode   = $storeObj->getStore($store_id)->getBaseCurrencyCode();
        $category = $this->_category;
        $product = $this->_product;
  //   print_r( $product->getCollection()->addAttributeToFilter('description', $search_condition)->getData()); die;
   /*  $collection   = $product->getCollection()->addAttributeToFilter('description', $search_condition)->addStoreFilter($store_id)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped',
		'downloadable'

            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1)->addAttributeToSort($sortType, $sortOrder);


*/
 $collection   = $product->getCollection()->addAttributeToFilter('name', array('like' => '%'.$search_data.'%'))
                ->addStoreFilter($store_id)->addAttributeToSelect(['name', 'price', 'image'])->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped',
		'downloadable'

            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1)->addAttributeToSort($sortType, $sortOrder);
	

   	//print_r(count($collection)); die("BYE!");
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
            '(' . join(') OR (', $cond) . ')'
        );
	$res["total"] = $collection->getSize(); //print_r($res);die("BYE!");	
        //$echo= $collection->getSelect()->__toString(); die('gcdgdv');
        //~ var_dump($curr_page, $page_size);die;
        $res["total"] = $collection->getSize();
	$collection->setPage($curr_page, $page_size);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        foreach ($collection as $_product) {

           	$gflag=1;
            $mofluid_product            = $_product->load($_product->getId());
            $mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getImage();
            $defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
            $defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
            $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
			$thumbnailimage = $imagehelper->init($mofluid_product, 'category_page_list')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
            try {
                $custom_options        = $mofluid_product->getOptions();
                $has_custom_option     = 0;
                foreach ($custom_options as $optionKey => $optionVal) {
                    $has_custom_option = 1;
                }
            }
            catch (\Exception $ee) {
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

             if ($_product->getTypeID() == 'configurable') {

			 $defaultprice = $specialprice;
			 }

            //End sumit code
            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');

           $stock = $this->stock->getStockItem($_product->getId());


           if($gflag)
           {
            $res["data"][] = array(
                "id" => $_product->getId(),
                "name" => $_product->getName(),
                "imageurl" => $thumbnailimage,
                "sku" => $_product->getSku(),
                "type" => $_product->getTypeID(),
                "spclprice" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "currencysymbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
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
    public function ws_productdetailImage($store_id, $service, $productid, $currentcurrencycode)
    {
		$storeObj = $this->_storeManager;
		$cache = $this->_cache;
        $storeObj->getStore()->setCurrentStore($store_id);
        $media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $res = array();
        $product = $this->_product;
        $cache_key = "mofluid_" . $service . "_store" . $store_id . "_productid_img" . $productid . "_currency" . $currentcurrencycode;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));

        $custom_attr       = array();
        $product           = $this->_product->load($productid);
        $res               = array();

        $mofluid_all_product_images = array();
        $mofluid_non_def_images     = array();
        $mofluid_product            = $product;
        $mofluid_baseimage          = $media_url.$mofluid_product->getImage();

        foreach ($mofluid_product->getMediaGalleryImages() as $mofluid_image) {
            $mofluid_imagecame = $mofluid_image->getUrl();
            if ($mofluid_baseimage == $mofluid_imagecame) {
                $mofluid_all_product_images[] = $mofluid_image->getUrl();
            } else {
                $mofluid_non_def_images[] = $mofluid_image->getUrl();
            }
        }
        $mofluid_all_product_images = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
		$res["id"]     = $product->getId();
		$res["image"]  = $mofluid_all_product_images;
		$res["status"] = $product->getStatus();
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return ($res);
    }
    public function ws_verifyLogin($store, $service, $username, $password)
    {
		$customerObj = $this->_customer;
		$storemodel = $this->_store;
	$password = base64_decode($password);
        $websiteId       = $storemodel->load($store)->getWebsiteId();
        $res             = array();
        $res["username"] = $username;
        $res["password"] =  $password;
        $login_status    = 1;
        $login_customer = $customerObj->setWebsiteId($websiteId);
        $login_customer->loadByEmail($username);
        if($login_customer->getId()){
			try {
				$login_customer_result = $login_customer->validatePassword($password);
				if(!$login_customer_result){
					$login_status = 0;
				}else{
					$login_status    = 1;
					$res["firstname"] = $login_customer->getFirstname();
					$res["lastname"]  = $login_customer->getLastname();
					$res["id"]        = $login_customer->getId();
					$res["stripecustid"]        = '0';
					if($login_customer->getMdStripeCustomerId() != null){
						$res["stripecustid"]        = $login_customer->getMdStripeCustomerId();
					}
				}
			}catch (\Exception $e) {
				$login_status = 0;
			}
		}else{
			$login_status = 0;
		}
        $res["login_status"] = $login_status;
        return $res;
    }
    public function ws_forgotPassword($email = "")
    {
		$storemodel = $this->_store;
		$customerObj = $this->_customer;
        $websiteId       = $this->_storeManager->getStore()->getWebsiteId();
        $res = array();
        $res["response"] = "error";
        if ($email) {
            $customer = $customerObj->setWebsiteId($websiteId)->loadByEmail($email);
			if ($customer->getId()) {
				    if (!\Zend_Validate::is($email, 'EmailAddress')) {
						$this->session->setForgottenEmail($email);
						$res["response"] = ['Please correct the email address.'];
					}

					try {
						$this->_accountManagementInterface->initiatePasswordReset(
							$email,
							AccountManagement::EMAIL_RESET
						);
						$res["response"] = "success";
					} catch (\Exception $Exception) {
						$res["response"] = ['We are unable to send the password reset email.'];
					}
			}
        }
        return ($res);
    }
    public function ws_myProfile($cust_id)
    {
		$customerObj = $this->_customer;

        try {
            $customer                    = $customerObj->load($cust_id);
            $customerData                = $customer->getData();
          if(isset($customerData['created_at'])){
            $customerData['membersince'] = $this->_date->date("Y-m-d h:i:s A", $customerData['created_at']);
		}
            $shippingAddress             = $customer->getDefaultShippingAddress();
        }
        catch (\Exception $ex2) {
            $echo= $ex2;
						$this->getResponse()->setBody($echo);
        }
        $shippadd = array();
        $billadd  = array();
        try {
            if ($shippingAddress != null) {
                $shippadd = array(
                    "firstname" => $shippingAddress->getFirstname(),
                    "lastname" => $shippingAddress->getLastname(),
                    "company" => $shippingAddress->getCompany(),
                    "street" => $shippingAddress->getStreetFull(),
                    "region" => $shippingAddress->getRegion(),
                    "city" => $shippingAddress->getCity(),
                    "pincode" => $shippingAddress->getPostcode(),
                    "countryid" => $shippingAddress->getCountry_id(),
                    "contactno" => $shippingAddress->getTelephone()
                );
            }
            $billingAddress = $customer->getDefaultBillingAddress();
            if ($billingAddress != null) {
                $billadd = array(
                    "firstname" => $billingAddress->getFirstname(),
                    "lastname" => $billingAddress->getLastname(),
                    "company" => $billingAddress->getCompany(),
                    "street" => $billingAddress->getStreetFull(),
                    "region" => $billingAddress->getRegion(),
                    "city" => $billingAddress->getCity(),
                    "pincode" => $billingAddress->getPostcode(),
                    "countryid" => $billingAddress->getCountry_id(),
                    "contactno" => $billingAddress->getTelephone()
                );
            }
        }
        catch (\Exception $ex) {
            $echo= $ex;
						$this->getResponse()->setBody($echo);
        }
        $res = array();
        $customerData["stripecustid"]        = '0';
		if($customer->getMdStripeCustomerId() != null){
			$customerData["stripecustid"]        = $customer->getMdStripeCustomerId();
		}
        $res = array(
            "CustomerInfo" => $customerData,
            "BillingAddress" => (object)$billadd,
            "ShippingAddress" => (object)$shippadd
        );
        return $res;
    }

    public function ws_mofluidappcountry($mofluid_store)
    {
        $cache = $this->_cache;
        $cache_key = "mofluid_country_store" . $mofluid_store;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));
        $scopeConfig = $this->_scopeconfig;
        $res                = array();
        $country_sort_array = array();
        try {
            $collection = $this->_country->getCollection()->loadByStore($mofluid_store);
            foreach ($collection as $country) {
                $mofluid_country["country_id"]   = $country->getId();
                $mofluid_country["country_name"] = $country->getName();
                $mofluid_country_arr[]           = $mofluid_country;
                $country_sort_array[]            = $country->getName();
            }

            array_multisort($country_sort_array, SORT_ASC, $mofluid_country_arr);
            $res["mofluid_countries"] = $mofluid_country_arr;

            $res["mofluid_default_country"]["country_id"] = $scopeConfig->getValue('general/country/default', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            return $res;
        }
        catch (\Exception $ex) {
            $echo= $ex->getMessage();
						$this->getResponse()->setBody($echo);
        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return $res;
    }

    public function ws_mofluidappstates($mofluid_store, $countryid)
    {
        $cache = $this->_cache;
        $cache_key = "mofluid_states_store" . $mofluid_store . "_countryid" . $countryid;
        if ($cache->load($cache_key))
            return json_decode($cache->load($cache_key));

        $res = array();
        try {
            $collection = $this->_region->getResourceCollection()->addCountryFilter($countryid)->load();
            foreach ($collection as $region) {
                $mofluid_region["region_id"]   = $region->getCode();
                $mofluid_region["region_name"] = $region->getDefaultName();
                $res["mofluid_regions"][]      = $mofluid_region;
            }
            return $res;
        }
        catch (\Exception $ex) {

        }
        $cache->save(json_encode($res), $cache_key, array(
            "mofluid"
        ), $this->CACHE_EXPIRY);
        return $res;
    }
    public function ws_changeProfilePassword($custid, $username, $oldpassword, $newpassword, $store)
    {
		$storemodel = $this->_store;
		$customerObj= $this->_customer;
        $res        = array();
        $oldpassword = base64_decode($oldpassword);
        $newpassword = base64_decode($newpassword);
        $validate    = 0;
        $websiteId   = $storemodel->load($store)->getWebsiteId();
        $customer = $customerObj->load($custid);
        try {
				$login_customer_result = $customer->validatePassword($oldpassword);
				if(!$login_customer_result){
					$validate = 0;
				}else{
					$validate = 1;
				}

		}
        catch (\Exception $ex) {

        }
        if ($validate == 1) {
            try {
                $customer->setPassword($newpassword);
                $customer->save();
                $res = array(
                    "customerid" => $custid,
                    "oldpassword" => $oldpassword,
                    "newpassword" => $newpassword,
                    "change_status" => 1,
                    "message" => 'Your Password has been Changed Successfully'
                );
            }
            catch (\Exception $ex) {
                $res = array(
                    "customerid" => $custid,
                    "oldpassword" => $oldpassword,
                    "newpassword" => $newpassword,
                    "change_status" => -1,
                    "message" => 'Error : ' . $ex->getMessage
                );
            }
        } else {
            $res = array(
                "customerid" => $custid,
                "oldpassword" => $oldpassword,
                "newpassword" => $newpassword,
                "change_status" => 0,
                "message" => 'Incorrect Old Password.'
            );
        }
        return $res;
    }
     public function mofluidUpdateProfile($store, $service, $customerId, $JbillAdd, $JshippAdd, $profile, $billshipflag)
    {
		$storemodel  = $this->_store;
		$customerObj = $this->_customer;
		$websiteId   = $storemodel->load($store)->getWebsiteId();
		$billAdd  = json_decode(base64_decode($JbillAdd));
        $shippAdd = json_decode(base64_decode($JshippAdd));
        $profile  = json_decode(base64_decode($profile));
        $result                 = array();
        $result['billaddress']  = 0;
        $result['shippaddress'] = 0;
        $result['userprofile']  = 0;

        /* Update User Profile Data */

       $customer = $customerObj->setWebsiteId($websiteId)->loadByEmail($profile->email);
       // if(!isset($billAdd->billstreet2)){
       // 	$billAdd->billstreet2 = '';
       // }

        //check exists email address of users
        if ($customer->getId() && $customer->getId() != $customerId) {
            return $result;
        } else {
            if ($billshipflag == "billingaddress") {
                $_bill_address = array(
                    'firstname' => $billAdd->billfname,
                    'lastname' => $billAdd->billlname,
                    'street' => array(
                        '0' => $billAdd->billstreet1,
                       // '1' => (isset($billAdd->billstreet2)?$billAdd->billstreet2:'')
                    ),
                    'city' => $billAdd->billcity,
                    'region_id' => '',
                    'region' => $billAdd->billstate,
                    'postcode' => $billAdd->billpostcode,
                    'country_id' => $billAdd->billcountry,
                    'telephone' => $billAdd->billphone
                );
                $billAddress   = $this->_address;
                if ($defaultBillingId = $customer->getDefaultBilling()) {
                    $billAddress->load($defaultBillingId);
                    $billAddress->addData($_bill_address);
                } else {
                    $billAddress->setData($_bill_address)->setCustomerId($customerId)->setIsDefaultBilling('1')->setSaveInAddressBook('1');
                }
                try {
                    if ($billAddress->save()){
                      $result['billaddress'] = 1;
                      $obj=new Magento2();
                      $res=$obj->getBillingAddress($customerId);
                     $res_obj= json_decode($res,true);
                     $address_id=$res_obj["id"];
                     $obj->setShippingAddress($customerId,$address_id);
                    }
                }
                catch (\Exception $ex) {
                   	echo $ex->getMessage();
			// Zend_Debug::dump($ex->getMessage());
                }
            } else {
                $_shipp_address = array(
                    'firstname' => $shippAdd->shippfname,
                    'lastname' => $shippAdd->shipplname,
                    'street' => array(
                        '0' => $shippAdd->shippstreet1,
                        //'1' => (isset($shippAdd->shippstreet2)?$shippAdd->shippstreet2:''),
                    ),
                    'city' => $shippAdd->shippcity,
                    'region_id' => '',
                    'region' => $shippAdd->shippstate,
                    'postcode' => $shippAdd->shipppostcode,
                    'country_id' => $shippAdd->shippcountry,
                    'telephone' => $shippAdd->shippphone
                );
                $shippAddress   = $this->_address;
                if ($defaultShippingId = $customer->getDefaultShipping()) {
                    $shippAddress->load($defaultShippingId);
                    $shippAddress->addData($_shipp_address);
                } else {
                    $shippAddress->setData($_shipp_address)->setCustomerId($customerId)->setIsDefaultShipping('1')->setSaveInAddressBook('1');
                }
                try {
                    if ($shippAddress->save()){
                        $result['shippaddress'] = 1;
                         $obj=new Magento2();
                      $res=$obj->getShippingAddress($customerId);
                     $res_obj= json_decode($res,true);
                     $address_id=$res_obj["id"];
                     $obj->setBillingAddress($customerId,$address_id);
                    }
                }
                catch (\Exception $ex) {
                    Zend_Debug::dump($ex->getMessage());
                }
            }



            return $result;
        }
    }
    public function ws_loginwithsocial($store, $username, $firstname, $lastname)
    {
		$storemodel = $this->_store;
		$customerObj= $this->_customer;
        $websiteId       = $storemodel->load($store)->getWebsiteId();
        $res             = array();
        $res["username"] = $username;
        $login_status    = 1;
        try {
            $login_customer = $customerObj->setWebsiteId($websiteId);
            $login_customer->loadByEmail($username);
            if ($login_customer->getId()) {
                $res["firstname"] = $login_customer->getFirstname();
                $res["lastname"]  = $login_customer->getLastname();
                $res["id"]        = $login_customer->getId();
            } else {
                $login_status = 0;
                $res          = $this->ws_registerwithsocial($store, $username, $firstname, $lastname);
                if ($res["status"] == 1) {
                    $login_status = 1;
                }
            }
        }
        catch (\Exception $e) {
            $login_status = 0;
            $res          = $this->ws_registerwithsocial($store, $username, $firstname, $lastname);
            if ($res["status"] == 1) {
                $login_status = 1;
            }
        }
        $res["login_status"] = $login_status;
        return $res;
    }

    /* Function call to register user from its Email address */

    public function ws_registerwithsocial($store, $email, $firstname, $lastname)
    {
		$storemodel = $this->_store;
		$customerObj= $this->_customer;
        $res                  = array();
        $websiteId            = $storemodel->load($store)->getWebsiteId();
        $customer             = $customerObj;
        $customer->website_id = $websiteId;
        $customer->setCurrentStore($store);
        try {
            // If new, save customer information
            $password                = base64_encode(rand(11111111, 99999999));
            $res["email"]            = $email;
            $res["firstname"]        = $firstname;
            $res["lastname"]         = $lastname;
            $res["password"]         = $password;
            $res["status"]           = 0;
            $res["id"]               = 0;
            $cust                    = $customerObj->setWebsiteId($websiteId)->loadByEmail($email);
            $customer->setWebsiteId($websiteId)->setFirstname($firstname)->setLastname($lastname)->setEmail($email)->setPassword(base64_decode($password))->save();
            //check exists email address of users
            if ($cust->getId()) {
                $res["id"]     = $cust->getId();
                $res["status"] = 0;
            } else {
                if ($customer->save()) {
                    $customer->sendNewAccountEmail('confirmed');
                    //$this->send_Password_Mail_to_NewUser($firstname, base64_decode($password), $email);
                    $res["id"]     = $customer->getId();
                    $res["status"] = 1;
                } else {
                    $exist_customer = $customerObj;
                    $exist_customer->setWebsiteId($websiteId);
                    $exist_customer->setCurrentStore($store);
                    $exist_customer->loadByEmail($email);
                    $res["id"]     = $exist_customer->getId();
                    $res["status"] = 1;
                }
            }
        }
        catch (\Exception $e) {
            try {
                $exist_customer = $customerObj;
                $exist_customer->setWebsiteId($websiteId);
                $exist_customer->setCurrentStore($store);
                $exist_customer->loadByEmail($email);
                $res["id"]     = $exist_customer->getId();
                $res["status"] = 1;
            }
            catch (\Exception $ex) {
                $res["id"]     = -1;
                $res["status"] = 0;
            }
        }
        return $res;
    }

    public function ws_getpaymentmethod()
    {
		$mofluid_pay_data = array();
		$mofluid_pay_data = $this->_mpayment->getCollection()->addFieldToFilter('payment_method_status', 1)->getData();

			foreach($mofluid_pay_data as $key=>$mofluid_pay_datas){
				$mofluid_pay_data[$key]['payment_method_id'] = $mofluid_pay_datas['id'];
				unset($mofluid_pay_data[$key]['id']);
			}
      return($mofluid_pay_data);
    }

    public function ws_productQuantity($product)
    {

       $pqty    = array();
       $scopeConfig         = $this->_scopeconfig;
       $config_manage_stock = $scopeConfig ->getValue('cataloginventory/options/show_out_of_stock', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config_max_sale_qty = $scopeConfig ->getValue('cataloginventory/item_options/max_sale_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $product = json_decode($product);
        foreach ($product as $key => $val) {
            try {
                $_product   = $this->_product->load($val);
                $stock = $this->stock->getStockItem($_product->getId());

                $stocklevel = (int) $stock->getQty();
         		$stock_data = $stock->getData();

                if($stock_data['use_config_manage_stock']==0)
                {
                 if($stock_data['manage_stock']==0)
                	{
                			if($stock_data['use_config_max_sale_qty']==0)
                			{
                				$pqty[$val] =$stock_data['max_sale_qty'];
                			 }
                			 else
                			 {
                			 $pqty[$val] = $config_max_sale_qty;

                			 }
                	}
                	else
                	{
                		        $pqty[$val] = $stocklevel;
                	}
                }
                else
                {

                	if($config_manage_stock==0){ $pqty[$val] = $config_max_sale_qty; } else { $pqty[$val] = $stocklevel; }

                }
                        }
            catch (\Exception $ex) {

            }
        }
        return $pqty;
    }

    public function prepareQuote($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, $is_create_quote, $find_shipping)
    {
		$storeObj            = $this->_storeManager;
		$scopeConfig         = $this->_scopeconfig;
		$Jproduct            = str_replace(" ", "+", $Jproduct);
        $orderproduct        = json_decode(base64_decode($Jproduct));
        $address             = str_replace(" ", "+", $address);
        $address             = json_decode(base64_decode($address));
        $config_manage_stock = $scopeConfig->getValue('cataloginventory/options/show_out_of_stock', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config_max_sale_qty = $scopeConfig ->getValue('cataloginventory/item_options/max_sale_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $basecurrencycode = $storeObj->getStore($store)->getBaseCurrencyCode();
        try {
			$addressPrefix = null;
			$addresscompany = null;
			if(isset($address->shipping->prefix))
				$addressPrefix = $address->shipping->prefix;
			if(isset($address->shipping->prefix))
				$addresscompany = $address->shipping->company;
			$addressBPrefix = null;
			$addressBcompany = null;
			if(isset($address->billing->prefix))
				$addressBPrefix = $address->billing->prefix;
			if(isset($address->billing->prefix))
				$addressBcompany = $address->billing->company;
            // get billing and shipping address of customer
            $shippingAddress = array(
                //~ 'prefix' => $addressPrefix,
                'firstname' => $address->shipping->firstname,
                'lastname' => $address->shipping->lastname,
                //~ 'company' => $addresscompany,
                'street' => $address->shipping->street,
                'city' => $address->shipping->city,
                'postcode' => $address->shipping->postcode,
                'telephone' => $address->shipping->phone,
                'country_id' => $address->shipping->country,
                'region' => $address->shipping->region
            );
            $billingAddress  = array(
                //~ 'prefix' => $addressBPrefix,
                'firstname' => $address->billing->firstname,
                'lastname' => $address->billing->lastname,
                //~ 'company' => $addressBcompany,
                'street' => $address->billing->street,
                'city' => $address->billing->city,
                'postcode' => $address->billing->postcode,
                'telephone' => $address->billing->phone,
                'country_id' => $address->billing->country,
                'region' => $address->billing->region
            );

            //Setting Region ID In case of Country is US
            if ($address->billing->country == "US" || $address->billing->country == "USA") {
                $regionModel                 = $this->_region->loadByCode($address->billing->region, $address->billing->country);
                $regionId                    = $regionModel->getId();
                $billingAddress["region_id"] = $regionId;
            }
            if ($address->shipping->country == "US" || $address->shipping->country == "USA") {
                $regionModelShipping          = $this->_region->loadByCode($address->shipping->region, $address->shipping->country);
                $regionIdShipp                = $regionModelShipping->getId();
                $shippingAddress["region_id"] = $regionIdShipp;
            }
            $quote    = $this->_quote;

            if($custid){
				$customer = $this->_customer->load($custid);
				$customerData = $this->customerRepository->getById($custid);
				if ($customerData) {
					$quote->assignCustomer($customerData);
				}
			}else{
				$quote->setCustomerEmail($address->shipping->email);
			}

			$storeobj = $this->_store->load($store);
            $quote->setStore($storeobj);
            $res           = array();
            $stock_counter = 0;
            $flag          = 0;
			foreach ($orderproduct as $key => $item) {
                $product_stock = $this->getProductStock($item->id);
              //  $product = $this->_product->load($item->id);
               $product =$this->product->create()->load($item->id);
                try {
				 if($product_stock['use_config_manage_stock']==0){
						if($product_stock['manage_stock']==0){
							if($product_stock['use_config_max_sale_qty']==0){
								$product_stock_quantity =$product_stock['max_sale_qty'];
								$flag=1;
							 }else {
								$product_stock_quantity = $config_max_sale_qty;
								$flag=1;
							 }
						}else{
							$product_stock_quantity = $product_stock['qty'];
							$flag=0;
						}
				 }else{
						if($config_manage_stock==0){  $product_stock_quantity = $config_max_sale_qty; $flag=1; } else {  $product_stock_quantity = $product_stock['qty']; $flag=0; }

				}
              }catch(\Exception $ex){}
              $manage_stock           = $product_stock['manage_stock'];
              $is_in_stock            = $product_stock['is_in_stock'];
              $res["qty_flag"]        = $flag;

			  if ($item->quantity > $product_stock_quantity) {
				$res["status"]                              = "error";
				$res["type"]                                = "quantity";
				$res["product"][$stock_counter]["id"]       = $item->id;
				$res["product"][$stock_counter]["name"]     = $product->getName();
				$res["product"][$stock_counter]["sku"]      = $product->getSku();
				$res["product"][$stock_counter]["quantity"] = $product_stock_quantity;
				$stock_counter++;
			   }

				$productType = $product->getTypeID();
				$quoteItem   = $this->_quoteitem->setProduct($product);

				$quoteItem->setQuote($quote);

				$quoteItem->setQty($item->quantity);
				//~ var_dump($quoteItem->getProduct()->getName());die;
				$optionch = array();
				if(isset($item->options)){
						$optionch = (array) $item->options;
				}


				//$echo= "<pre>"; print_r($item); die('ccc');

                if (!empty($optionch)) {
                    foreach ($item->options as $ckey => $cvalue) {
                        $custom_option_ids_arr[] = $ckey;
                    }

                    $option_ids = implode(",", $custom_option_ids_arr);
                    $quoteItem->addOption(new Varien_Object(array(
                        'product' => $quoteItem->getProduct(),
                        'code' => 'option_ids',
                        'value' => $option_ids
                    )));

                    foreach ($item->options as $ckey => $cvalue) {
                        if (is_array($cvalue)) {
                            $all_ids = implode(",", array_unique($cvalue));
                        } else {
                            $all_ids = $cvalue;
                        }
                        //Handle Custom Option Time depending upon Timezone
                        if (preg_match('/(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]/', $all_ids)) {
                            $currentTimestamp = $this->_date->timestamp(time());
                            $currentDate      = date('Y-m-d', $currentTimestamp);
                            $test             = new DateTime($currentDate . ' ' . $all_ids);
                            $all_ids          = $test->getTimeStamp();
                        }
                        try {
                            $quoteItem->addOption(new Varien_Object(array(
                                'product' => $quoteItem->getProduct(),
                                'code' => 'option_' . $ckey,
                                'value' => $all_ids
                            )));
                        }
                        catch (\Exception $eee) {
                            $echo= 'Error ' . $eee->getMessage();
														$this->getResponse()->setBody($echo);
                        }
                    } //end inner foreach\

                   // $quote->addItem($quoteItem);
                    $quote->addProduct(
                $product,
                intval($item->quantity));

                } //end if
                else {
					//~ var_dump($quoteItem->getProduct()->getId());die;
                    //$quote->addItem($quoteItem);
                     $quote->addProduct(
                $product,
                intval($item->quantity));
                    continue;
                }
            }

           if ($stock_counter > 0 && $is_create_quote == 1) {
                return $res;
            }
            $addressForm = $this->_addressform;
            $addressForm->setFormCode('customer_address_edit')->setEntityType('customer_address');
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($shippingAddress[$attribute->getAttributeCode()])) {
                    $quote->getShippingAddress()->setData($attribute->getAttributeCode(), $shippingAddress[$attribute->getAttributeCode()]);
                }
            }
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($billingAddress[$attribute->getAttributeCode()])) {
                    $quote->getBillingAddress()->setData($attribute->getAttributeCode(), $billingAddress[$attribute->getAttributeCode()]);
                }
            }
            $quote->setBaseCurrencyCode($basecurrencycode);
            $quote->setQuoteCurrencyCode($currency);
            if ($find_shipping) {
                $quote->getShippingAddress()->setCollectShippingRates(true);
                $quote->save();
            } else {
                $quote->getShippingAddress()->setShippingMethod($shipping_code)->setCollectShippingRates(true);
            }
            //Check if applied for coupon
            //~ var_dump($couponCode);die('asd');
            if ($couponCode != '') {
                $quote->setCouponCode($couponCode)->collectTotals()->save();
                $coupon_status = 1;
            } else {
                $coupon_status = 0;
            }
            //~ die('asd');
            //~ $echo= "<pre>"; print_r($quote->getData());die;
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            //~ die('asd');
            $totals = $quote->getTotals();
            //~ var_dump($totals);die;

            try {
                $test                = $quote->getShippingAddress();
                $shipping_tax_amount = number_format($this->_directory->currencyConvert($test['shipping_tax_amount'], $basecurrencycode, $currency), 2, ".", "");
            }
            catch (\Exception $ex) {
                $shipping_tax_amount = 0;
            }
            $shipping_methods         = array();
            if ($find_shipping) {
                $shipping                 = $quote->getShippingAddress()->getGroupedAllShippingRates();
                $index                    = 0;
                $shipping_dropdown_option = '';
                foreach ($shipping as $shipping_method_id => $shipping_method) {
                    foreach ($shipping_method as $current_shipping_method) {
                        $shipping_methods[$index]["id"]            = $shipping_method_id;
                        $shipping_methods[$index]["code"]          = str_replace(" ", "%20", $current_shipping_method->getCode());
                        $shipping_methods[$index]["method_title"]  = $current_shipping_method->getMethodTitle();
                        $shipping_methods[$index]["carrier_title"] = $current_shipping_method->getCarrierTitle();
                        $shipping_methods[$index]["carrier"]       = $current_shipping_method->getCarrier();
                        $shipping_methods[$index]["price"]         = $this->_directory->currencyConvert($current_shipping_method->getPrice(), $basecurrencycode, $currency);
                        $shipping_methods[$index]["description"]   = $current_shipping_method->getMethodDescription();
                        $shipping_methods[$index]["error_message"] = $current_shipping_method->getErrorMessage();
                        $shipping_methods[$index]["address_id"]    = $current_shipping_method->getAddressId();
                        $shipping_methods[$index]["created_at"]    = $current_shipping_method->getCreatedAt();
                        $shipping_methods[$index]["updated_at"]    = $current_shipping_method->getUpdatedAt();
                        $shipping_option_title                     = $shipping_methods[$index]["carrier_title"];
                        if ($shipping_methods[$index]["method_title"]) {
                            $shipping_option_title .= ' (' . $shipping_methods[$index]["method_title"] . ')';
                        }
                        if ($shipping_methods[$index]["price"]) {
                            $shipping_option_title .= ' + ' . $this->_currency->getCurrency($currency)->getSymbol(). number_format($shipping_methods[$index]["price"], 2);
                        }
                        $shipping_dropdown_option .= '<option id=' . $shipping_methods[$index]["id"] . ' value= ' . $shipping_methods[$index]["code"] . ' price =' . $shipping_methods[$index]["price"] . ' description=' . $shipping_method[0]->getMethodDescription() . '>' . $shipping_option_title . '</option>';
                        $index++;
                    }
                }
               $res["available_shipping_method"] = base64_encode($shipping_dropdown_option);
            }
            $dis = 0;


            //Find Applied Tax
            if (isset($totals['tax']) && $totals['tax']->getValue()) {
                $tax_amount = number_format($this->_directory->currencyConvert($totals['tax']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $tax_amount = 0;
            }
            if (isset($totals['shipping']) && $totals['shipping']->getValue()) {
                $shipping_amount = number_format($this->_directory->currencyConvert($totals['shipping']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $shipping_amount = 0;
            }
            if ($shipping_tax_amount) {
                $shipping_amount += $shipping_tax_amount;
            }
            $quoteData = $quote->getData();
            //~ var_dump($quoteData);die;
            $coupon_discountvalue = $quoteData['subtotal']-$quoteData['subtotal_with_discount'];

            //Find Applied Discount
            if ($coupon_discountvalue !='' && $coupon_discountvalue > 0) {
                $coupon_status   = 1;
                $coupon_discount = number_format($this->_directory->currencyConvert($coupon_discountvalue, $basecurrencycode, $currency), 2, ".", "");
            } else {
                $coupon_discount = 0;
                $coupon_status   = 0;
            }

            $dis                    = $quoteData['grand_total'];
            $grandTotal             = number_format($this->_directory->currencyConvert($totals['grand_total']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            $res["coupon_discount"] = $coupon_discount;
            $res["coupon_status"]   = $coupon_status;
            $res["tax_amount"]      = $tax_amount;
            $res["total_amount"]    = $grandTotal;
            $res["currency"]        = $currency;
            $res["status"]          = "success";
            $res["shipping_amount"] = $shipping_amount;
            $res["shipping_method"] = $shipping_methods;
            if ($is_create_quote == 1) {
                $quote->save();
                $res["quote_id"] = $quote->getId();
            }
            return $res;
        }
        catch (\Exception $ex) {
            $res["coupon_discount"] = 0;
            $res["coupon_status"]   = 0;
            $res["tax_amount"]      = 0;
            $res["total_amount"]    = 0;
            $res["currency"]        = $currency;
            $res["status"]          = "error";
            $res["type"]            = $ex->getMessage();
            $res["shipping_amount"] = $shipping_amount;
            $res["shipping_method"] = $shipping_methods;
            return $res;
        }
	}

    public function prepareQuote1($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, $is_create_quote, $find_shipping)
    {
	var_dump($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, $is_create_quote, $find_shipping);
		$storeObj            = $this->_storeManager;
		$scopeConfig         = $this->_scopeconfig;
		$Jproduct            = str_replace(" ", "+", $Jproduct);
        $orderproduct        = json_decode(base64_decode($Jproduct));
        $address             = str_replace(" ", "+", $address);
        $address             = json_decode(base64_decode($address));
        $config_manage_stock = $scopeConfig->getValue('cataloginventory/options/show_out_of_stock', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $config_max_sale_qty = $scopeConfig ->getValue('cataloginventory/item_options/max_sale_qty', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $basecurrencycode = $storeObj->getStore($store)->getBaseCurrencyCode();
        if(isset($address->shipping->prefix)){
        	 $shippingAddress['prefix'] = $address->shipping->prefix;
        }
         if(isset($billingAddress->billing->prefix)){
        	 $billingAddress['prefix'] = $address->billing->prefix;
        }
        try {
            // get billing and shipping address of customer
            $shippingAddress = array(
                //'prefix' => $address->shipping->prefix,
                'firstname' => $address->shipping->firstname,
                'lastname' => (isset($address->shipping->lastname)?$address->shipping->lastname:''),
                'company' => (isset($address->shipping->company)?$address->shipping->company:''),
                'street' => $address->shipping->street,
                'city' => $address->shipping->city,
                'postcode' => $address->shipping->postcode,
                'telephone' => $address->shipping->phone,
                'country_id' => $address->shipping->country,
                'region' => $address->shipping->region
            );
            $billingAddress  = array(
                //'prefix' => $address->billing->prefix,
                'firstname' => $address->billing->firstname,
                'lastname' => (isset($address->billing->lastname)?$address->billing->lastname:''),
                'company' => (isset($address->billing->company)?$address->billing->company:''),
                'street' => $address->billing->street,
                'city' => $address->billing->city,
                'postcode' => $address->billing->postcode,
                'telephone' => $address->billing->phone,
                'country_id' => $address->billing->country,
                'region' => $address->billing->region
            );
            //Setting Region ID In case of Country is US
            if ($address->billing->country == "US" || $address->billing->country == "USA") {
                $regionModel                 = $this->_region->loadByCode($address->billing->region, $address->billing->country);
                $regionId                    = $regionModel->getId();
                $billingAddress["region_id"] = $regionId;
            }
            if ($address->shipping->country == "US" || $address->shipping->country == "USA") {
                $regionModelShipping          = $this->_region->loadByCode($address->shipping->region, $address->shipping->country);
                $regionIdShipp                = $regionModelShipping->getId();
                $shippingAddress["region_id"] = $regionIdShipp;
            }
            $quote    = $this->_quote;
            if($custid==0){
            //$customer->loadByEmail($email);
           // $customer = $this->_customer->load($custid);
           // $customer = $this->_customer->loadByEmail('anshuman.kumar@ebizontek.com');;
           // $customer->setWebsiteId(Mage::app()->getWebsite('admin')->getId());

           // $customerData = $this->customerRepository->getById($custid);
            $customerData = array();
			} else {
				 //$customer->loadByEmail($email);
           $customer = $this->_customer->load($custid);
           // $customer = $this->_customer->loadByEmail('anshuman.kumar@ebizontek.com');;
           // $customer->setWebsiteId(Mage::app()->getWebsite('admin')->getId());

            $customerData = $this->customerRepository->getById($custid);

				}
			if ($customerData) {
				$quote->assignCustomer($customerData);
			}
			$storeobj = $this->_store->load($store);
            $quote->setStore($storeobj);
            $res           = array();
            $stock_counter = 0;
            $flag          = 0;
			foreach ($orderproduct as $key => $item) {
                $product_stock = $this->getProductStock($item->id);
                $product = $this->_product->load($item->id);
                try {
				 if($product_stock['use_config_manage_stock']==0){
						if($product_stock['manage_stock']==0){
							if($product_stock['use_config_max_sale_qty']==0){
								$product_stock_quantity =$product_stock['max_sale_qty'];
								$flag=1;
							 }else {
								$product_stock_quantity = $config_max_sale_qty;
								$flag=1;
							 }
						}else{
							$product_stock_quantity = $product_stock['qty'];
							$flag=0;
						}
				 }else{
						if($config_manage_stock==0){  $product_stock_quantity = $config_max_sale_qty; $flag=1; } else {  $product_stock_quantity = $product_stock['qty']; $flag=0; }

				}
              }catch(\Exception $ex){}
              $manage_stock           = $product_stock['manage_stock'];
              $is_in_stock            = $product_stock['is_in_stock'];
              $res["qty_flag"]        = $flag;

			  if ($item->quantity > $product_stock_quantity) {
				$res["status"]                              = "error";
				$res["type"]                                = "quantity";
				$res["product"][$stock_counter]["id"]       = $item->id;
				$res["product"][$stock_counter]["name"]     = $product->getName();
				$res["product"][$stock_counter]["sku"]      = $product->getSku();
				$res["product"][$stock_counter]["quantity"] = $product_stock_quantity;
				$stock_counter++;
			   }
				$productType = $product->getTypeID();
				$quoteItem   = $this->_quoteitem->setProduct($product);
				$quoteItem->setQuote($quote);
				$quoteItem->setQty($item->quantity);

				$optionch = (isset($item->options)?(array) $item->options:'');
				//$echo= "<pre>"; print_r($item); die('ccc');
                if (!empty($optionch)) {
                    foreach ($item->options as $ckey => $cvalue) {
                        $custom_option_ids_arr[] = $ckey;
                    }
                    $option_ids = implode(",", $custom_option_ids_arr);
                    $quoteItem->addOption(new Varien_Object(array(
                        'product' => $quoteItem->getProduct(),
                        'code' => 'option_ids',
                        'value' => $option_ids
                    )));
                    foreach ($item->options as $ckey => $cvalue) {
                        if (is_array($cvalue)) {
                            $all_ids = implode(",", array_unique($cvalue));
                        } else {
                            $all_ids = $cvalue;
                        }
                        //Handle Custom Option Time depending upon Timezone
                        if (preg_match('/(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]/', $all_ids)) {
                            $currentTimestamp = $this->_date->timestamp(time());
                            $currentDate      = date('Y-m-d', $currentTimestamp);
                            $test             = new DateTime($currentDate . ' ' . $all_ids);
                            $all_ids          = $test->getTimeStamp();
                        }
                        try {
                            $quoteItem->addOption(new Varien_Object(array(
                                'product' => $quoteItem->getProduct(),
                                'code' => 'option_' . $ckey,
                                'value' => $all_ids
                            )));
                        }
                        catch (\Exception $eee) {
                            $echo= 'Error ' . $eee->getMessage();
														$this->getResponse()->setBody($echo);
                        }
                    } //end inner foreach
                    $quote->addItem($quoteItem);
                } //end if
                else {
                    $quote->addItem($quoteItem);
                    continue;
                }
            }
           if ($stock_counter > 0 && $is_create_quote == 1) {
                return $res;
            }
            $addressForm = $this->_addressform;
            $addressForm->setFormCode('customer_address_edit')->setEntityType('customer_address');
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($shippingAddress[$attribute->getAttributeCode()])) {
                    $quote->getShippingAddress()->setData($attribute->getAttributeCode(), $shippingAddress[$attribute->getAttributeCode()]);
                }
            }
            foreach ($addressForm->getAttributes() as $attribute) {
                if (isset($billingAddress[$attribute->getAttributeCode()])) {
                    $quote->getBillingAddress()->setData($attribute->getAttributeCode(), $billingAddress[$attribute->getAttributeCode()]);
                }
            }
            $quote->setBaseCurrencyCode($basecurrencycode);
            $quote->setQuoteCurrencyCode($currency);
            if ($find_shipping) {
                $quote->getShippingAddress()->setCollectShippingRates(true);
                $quote->save();
            } else {
                $quote->getShippingAddress()->setShippingMethod($shipping_code)->setCollectShippingRates(true);
            }
            //Check if applied for coupon

            if ($couponCode != '') {
                $quote->setCouponCode($couponCode)->collectTotals()->save();
                $coupon_status = 1;
            } else {
                $coupon_status = 0;
            }
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $totals = $quote->getTotals();
            //$echo= "<pre>"; print_r($totals);die;
            try {
                $test                = $quote->getShippingAddress();
                $shipping_tax_amount = number_format($this->_directory->currencyConvert($test['shipping_tax_amount'], $basecurrencycode, $currency), 2, ".", "");
            }
            catch (\Exception $ex) {
                $shipping_tax_amount = 0;
            }
            $shipping_methods         = array();
            if ($find_shipping) {
                $shipping                 = $quote->getShippingAddress()->getGroupedAllShippingRates();
                $index                    = 0;
                $shipping_dropdown_option = '';
                foreach ($shipping as $shipping_method_id => $shipping_method) {
                    foreach ($shipping_method as $current_shipping_method) {
                        $shipping_methods[$index]["id"]            = $shipping_method_id;
                        $shipping_methods[$index]["code"]          = str_replace(" ", "%20", $current_shipping_method->getCode());
                        $shipping_methods[$index]["method_title"]  = $current_shipping_method->getMethodTitle();
                        $shipping_methods[$index]["carrier_title"] = $current_shipping_method->getCarrierTitle();
                        $shipping_methods[$index]["carrier"]       = $current_shipping_method->getCarrier();
                        $shipping_methods[$index]["price"]         = $this->_directory->currencyConvert($current_shipping_method->getPrice(), $basecurrencycode, $currency);
                        $shipping_methods[$index]["description"]   = $current_shipping_method->getMethodDescription();
                        $shipping_methods[$index]["error_message"] = $current_shipping_method->getErrorMessage();
                        $shipping_methods[$index]["address_id"]    = $current_shipping_method->getAddressId();
                        $shipping_methods[$index]["created_at"]    = $current_shipping_method->getCreatedAt();
                        $shipping_methods[$index]["updated_at"]    = $current_shipping_method->getUpdatedAt();
                        $shipping_option_title                     = $shipping_methods[$index]["carrier_title"];
                        if ($shipping_methods[$index]["method_title"]) {
                            $shipping_option_title .= ' (' . $shipping_methods[$index]["method_title"] . ')';
                        }
                        if ($shipping_methods[$index]["price"]) {
                            $shipping_option_title .= ' + ' . $this->_currency->getCurrency($currency)->getSymbol(). number_format($shipping_methods[$index]["price"], 2);
                        }
                        $shipping_dropdown_option .= '<option id=' . $shipping_methods[$index]["id"] . ' value= ' . $shipping_methods[$index]["code"] . ' price =' . $shipping_methods[$index]["price"] . ' description=' . $shipping_method[0]->getMethodDescription() . '>' . $shipping_option_title . '</option>';
                        $index++;
                    }
                }
               $res["available_shipping_method"] = base64_encode($shipping_dropdown_option);
            }
            $dis = 0;


            //Find Applied Tax
            if (isset($totals['tax']) && $totals['tax']->getValue()) {
                $tax_amount = number_format($this->directorydata->currencyConvert($totals['tax']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $tax_amount = 0;
            }
            if (isset($totals['shipping']) && $totals['shipping']->getValue()) {
                $shipping_amount = number_format($this->_directory->currencyConvert($totals['shipping']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            } else {
                $shipping_amount = 0;
            }
            if ($shipping_tax_amount) {
                $shipping_amount += $shipping_tax_amount;
            }
            $quoteData = $quote->getData();
            $coupon_discountvalue = $quoteData['subtotal']-$quoteData['subtotal_with_discount'];
            //Find Applied Discount
            if ($coupon_discountvalue !='' && $coupon_discountvalue > 0) {
                $coupon_status   = 1;
                $coupon_discount = number_format($this->_directory->currencyConvert($coupon_discountvalue, $basecurrencycode, $currency), 2, ".", "");
            } else {
                $coupon_discount = 0;
                $coupon_status   = 0;
            }

            $dis                    = $quoteData['grand_total'];
            $grandTotal             = number_format($this->_directory->currencyConvert($totals['grand_total']->getValue(), $basecurrencycode, $currency), 2, ".", "");
            $res["coupon_discount"] = $coupon_discount;
            $res["coupon_status"]   = $coupon_status;
            $res["tax_amount"]      = $tax_amount;
            $res["total_amount"]    = $grandTotal;
            $res["currency"]        = $currency;
            $res["status"]          = "success";
            $res["shipping_amount"] = $shipping_amount;
            $res["shipping_method"] = $shipping_methods;
            if ($is_create_quote == 1) {
                $quote->save();
                $res["quote_id"] = $quote->getId();
            }
            return $res;
        }
        catch (\Exception $ex) {
            $res["coupon_discount"] = 0;
            $res["coupon_status"]   = 0;
            $res["tax_amount"]      = 0;
            $res["total_amount"]    = 0;
            $res["currency"]        = $currency;
            $res["status"]          = "error";
            $res["type"]            = $ex->getMessage();
            $res["shipping_amount"] = $shipping_amount;
            $res["shipping_method"] = $shipping_methods;
            return $res;
        }
	}
	public function getProductStock($product_id)
    {
        $stock_data    = array();
        $stock_product = $this->stock->getStockItem($product_id);
        $stock_data    = $stock_product->getData();
        return $stock_data;
    }
  public function ws_myOrder($cust_id, $curr_page, $page_size, $store, $currency)
    {
        $media_url = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $basecurrencycode = $this->_storeManager->getStore($store)->getBaseCurrencyCode();
        $res              = array();
        $totorders        = $this->_orderData->getCollection()->addFieldToFilter('customer_id', $cust_id);
        $res["total"]     = count($totorders);
        $orders           = $this->_orderData->getCollection()->addFieldToSelect('*')->addFieldToFilter('customer_id', $cust_id)->setOrder('created_at', 'desc')->setPage($curr_page, $page_size);
        $orderData = array();
        foreach ($orders as $order) {

            $shippingAddress = $order->getShippingAddress();
            if (is_object($shippingAddress)) {
                $shippadd = array();
                $flag     = 0;
                if (count($orderData) > 0)
                    $flag = 1;
                $shippadd = array(
                    "firstname" => $shippingAddress->getFirstname(),
                    "lastname" => $shippingAddress->getLastname(),
                    "company" => $shippingAddress->getCompany(),
                    "street" => implode(" ",$shippingAddress->getStreet()),
                    "region" => $shippingAddress->getRegion(),
                    "city" => $shippingAddress->getCity(),
                    "pincode" => $shippingAddress->getPostcode(),
                    "countryid" => $shippingAddress->getCountry_id(),
                    "contactno" => $shippingAddress->getTelephone(),
                    "shipmyid" => $flag
                );
            }
            $billingAddress = $order->getBillingAddress();
            if (is_object($billingAddress)) {
                $billadd = array();
                $billadd = array(
                    "firstname" => $billingAddress->getFirstname(),
                    "lastname" => $billingAddress->getLastname(),
                    "company" => $billingAddress->getCompany(),
                    "street" =>  implode(" ",$billingAddress->getStreet()),
                    "region" => $billingAddress->getRegion(),
                    "city" => $billingAddress->getCity(),
                    "pincode" => $billingAddress->getPostcode(),
                    "countryid" => $billingAddress->getCountry_id(),
                    "contactno" => $billingAddress->getTelephone()
                );
            }
            $payment = array();
            $payment = $order->getPayment();

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            try {
                $payment_result = array(
                    "payment_method_title" => $payment->getMethodInstance()->getTitle(),
                    "payment_method_code" => $payment->getMethodInstance()->getCode()
                );
                if ($payment->getMethodInstance()->getCode() == "banktransfer") {

                    $payment_result["payment_method_description"] = $payment->getMethodInstance()->getInstructions();
                }
            }
            catch (\Exception $ex2) {

            }

            $items                       = $order->getAllItems();
            $itemcount                   = count($items);
            $name                        = array();
            $unitPrice                   = array();
            $sku                         = array();
            $ids                         = array();
            $qty                         = array();
            $images                      = array();
            $thumbnailimage                      = array();
            $test_p                      = array();
            $itemsExcludingConfigurables = array();
            foreach ($items as $itemId => $item) {
                $name[] = $item->getName();
                if ($item->getOriginalPrice() > 0) {
                    $unitPrice[] = number_format($this->convert_currency(floatval($item->getOriginalPrice()), $basecurrencycode, $currency), 2, '.', '');
                } else {
                    $unitPrice[] = number_format($this->convert_currency(floatval($item->getPrice()), $basecurrencycode, $currency), 2, '.', '');
                }

                $sku[]    = $item->getSku();
                $ids[]    = $item->getProductId();
                $qty[]    = $item->getQtyOrdered();
                $products = $this->_product->load($item->getProductId());
                $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
				$thumbnailimage[] = $imagehelper->init($products, 'category_page_grid')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
                $images[] = $media_url . '/catalog/product' . $products->getThumbnail();
            }
            $product = array();
            $product = array(
                "name" => $name,
                "sku" => $sku,
                "id" => $ids,
                "quantity" => $qty,
                "unitprice" => $unitPrice,
                "image" => $images,
                "small_image" => $thumbnailimage,
                "total_item_count" => $itemcount,
                "price_org" => $test_p,
                "price_based_curr" => 1
            );

            $order_date = $order->getCreatedAt() . '';
            $orderData  = array(
                "id" => $order->getId(),
                "order_id" => $order->getRealOrderId(),
                "status" => $order->getStatus(),
                "order_date" => $order_date,
                "grand_total" => number_format($this->convert_currency(floatval($order->getGrandTotal()), $basecurrencycode, $currency), 2, '.', ''),
                "shipping_address" => $shippadd,
                "billing_address" => $billadd,
                "shipping_message" => $order->getShippingDescription(),
                "shipping_amount" => number_format($this->convert_currency(floatval($order->getShippingAmount()), $basecurrencycode, $currency), 2, '.', ''),
                "payment_method" => $payment_result,
                "tax_amount" => number_format($this->convert_currency(floatval($order->getTaxAmount()), $basecurrencycode, $currency), 2, '.', ''),
                "product" => $product,
                "order_currency" => $order->getOrderCurrencyCode(),
                "order_currency_symbol" => $this->_currency->getCurrency($order->getOrderCurrencyCode())->getSymbol(),
                "currency" => $currency,
                "couponUsed" => 0
            );
            $couponCode = $order->getCouponCode();
            if ($couponCode != "") {
                $orderData["applied"]      = 1;
                $orderData["code"]      = $couponCode;
                $orderData["amount"] = number_format($order->getDiscountAmount() * -1, 2, '.', '');
            }

            $res["data"][] = $orderData;
        }
        return $res;
    }

     public function placeorder($custid, $Jproduct, $store, $address, $couponCode, $is_create_quote, $transid, $payment_code, $shipping_code, $currency, $message)
    {
		$orderData =array();
        $res            = array();
        $quantity_error = array();
        try {
            $quote_data = $this->prepareQuote($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, 1, 0); // self function

            if ($quote_data["status"] == "error") {
                return $quote_data;
            }
            $quote        = $this->_quote->load($quote_data['quote_id']);
            $quote        = $this->setQuoteGiftMessage($quote, $message, $custid); // self function
            $quote        = $this->setQuotePayment($quote, $payment_code, $transid); // self function
            $order        = $this->orderFactory->create();
            $addresses    = [];


           /*      if($payment_code == 'md_stripe_cards'){
				$cardData = json_decode(base64_decode($cardData));
				$additinalD = array(
							'cc_type' => $cardData->cc_type,
							'cc_number' => $cardData->cc_number,
							'expiration' => $cardData->expiration,
							'expiration_yr' => $cardData->expiration_yr,
							'save_card' => 0,
							'md_stripe_card_id' => 'new'
							);
			}else if($payment_code != 'paypal_express'){
				$additinalD = array();
				$quote->getPayment()->setAdditionalInformation($additinalD);
			}

            if($payment_code != 'paypal_express'){
				$data = array(
							'method' => $payment_code,
							'additional_data' => $additinalD
						);
				$quote->setPaymentMethod($payment_code);
				$quote->setInventoryProcessed(false); //not effetc inventory
				$quote->save();
				$quote->getPayment()->importData($data);
			}




            $quote->setInventoryProcessed (false);
			$quote->collectTotals();*/
			//~ die('Done');
			$quote->save();

            $this->dataObjectHelper->mergeDataObjects(
                '\Magento\Sales\Api\Data\OrderInterface',
                $order,
                $this->quoteAddressToOrder->convert($quote->getShippingAddress(), $orderData)
            );
            /**************************/

                        if($custid ){
				$shippingAddress = $this->quoteAddressToOrderAddress->convert(
					$quote->getShippingAddress(),
					[
						'address_type' => 'shipping',
						'email' => $quote->getCustomerEmail()
					]
				);
				//print_r($shippingAddress); die;
				$addresses[] = $shippingAddress;
				$order->setShippingAddress($shippingAddress);
				$order->setShippingMethod($quote->getShippingAddress()->getShippingMethod());
				$billingAddress = $this->quoteAddressToOrderAddress->convert(
				$quote->getBillingAddress(),
					[
						'address_type' => 'billing',
						'email' => $quote->getCustomerEmail()
					]
				);
				$addresses[] = $billingAddress;
			} else {
				$decode_address = json_decode(base64_decode($address));
				$shippingAddress = $this->quoteAddressToOrderAddress->convert(
					$quote->getShippingAddress(),
						[
							'address_type' => 'shipping',
							'email' => $decode_address->shipping->email
						]
					);

				$addresses[] = $shippingAddress;
				$order->setShippingAddress($shippingAddress);
				$order->setShippingMethod($quote->getShippingAddress()->getShippingMethod());
				$billingAddress = $this->quoteAddressToOrderAddress->convert(
				$quote->getBillingAddress(),
					[
						'address_type' => 'billing',
						'email' => $decode_address->billing->email
					]
				);
				$addresses[] = $billingAddress;
			}


            /*****************************/
			$order->setBillingAddress($billingAddress);
			$order->setAddresses($addresses);
			$order->setPayment($this->quotePaymentToOrderPayment->convert($quote->getPayment()));
				 if($payment_code == 'paypal_express'){
			 $order->getPayment()->setMethod('express_checkout');
			 $additinalD = array(
								'paypal_payment_status' => 'completed',
								'paypal_express_checkout_payer_id' => 'H8KVC8QB7ZQVA',
								'paypal_correlation_id' => 'a701543c56689'
							);

			$trans = $this->transactionBuilder;
			$transaction = $trans->setPayment($order->getPayment())
			->setOrder($order)
			->setTransactionId($transid)
			->setAdditionalInformation([\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $additinalD])
			->setFailSafe(true)
			//build method creates the transaction and returns the object
			->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);
			$order->getPayment()->addTransactionCommentsToOrder(
				$transaction,
				'Paypal Express checkout'
			);
			//~ $order->getPayment()->setParentTransactionId(null);
		   }
            $quoteItems = [];
			foreach ($quote->getAllItems() as $quoteItem) {
				/** @var \Magento\Quote\Model\ResourceModel\Quote\Item $quoteItem */
				$quoteItems[$quoteItem->getId()] = $quoteItem;
			}
			$orderItems = [];
			foreach ($quoteItems as $quoteItem) {
				$parentItem = (isset($orderItems[$quoteItem->getParentItemId()])) ?
					$orderItems[$quoteItem->getParentItemId()] : null;
				$orderItems[$quoteItem->getId()] =
					$this->quoteItemToOrderItem->convert($quoteItem, ['parent_item' => $parentItem]);
			}
			$oitems = array_values($orderItems);
			$order->setItems($oitems);

			if($custid!=0){

				if ($quote->getCustomer()) {
					$order->setCustomerId($quote->getCustomer()->getId());
				}
				$order->setQuoteId($quote->getId());
				$order->setCustomerEmail($quote->getCustomerEmail());
				$order->setCustomerFirstname($quote->getCustomerFirstname());
				$order->setCustomerMiddlename($quote->getCustomerMiddlename());
				$order->setCustomerLastname($quote->getCustomerLastname());
			} else {
				$order->setQuoteId($quote->getId());
				$order->setCustomerEmail($decode_address->billing->email);
				$order->setCustomerFirstname($decode_address->billing->firstname);
				$order->setCustomerLastname($decode_address->billing->lastname);
				$order->setCustomerIsGuest(1);
			}




			$this->eventManager->dispatch(
				'sales_model_service_quote_submit_before',
				[
					'order' => $order,
					'quote' => $quote
				]
			);
			try {
				$order = $this->orderManagement->place($order);
				$quote->setIsActive(false);
				$this->eventManager->dispatch(
					'sales_model_service_quote_submit_success',
					[
						'order' => $order,
						'quote' => $quote
					]
				);
				$this->quoteRepository->save($quote);
				$this->_orderSender->send($order, $forceSyncMode = false);
			} catch (\Exception $e) {
				$this->eventManager->dispatch(
					'sales_model_service_quote_submit_failure',
					[
						'order'     => $order,
						'quote'     => $quote,
						'\Exception' => $e
					]
				);
				throw $e;
			}
            if($quote_data['qty_flag']==1)
            {
            $quantity_error         = '';
            } else { $quantity_error         = $this->updateQuantityAfterOrder($Jproduct); } // self function
            $res["status"]          = 1;
            $res["id"]              = $order->getId();
            $res["orderid"]         = $order->getIncrementId();
            $res["transid"]         = $order->getPayment()->getTransactionId();
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"]  = $payment_code;
            $res["quantity_error"]  = $quantity_error;
            $order->addStatusHistoryComment("Order was placed using Mobile App")->setIsVisibleOnFront(false)->setIsCustomerNotified(false);
            if ($res["orderid"] > 0 && ( $payment_code == "banktransfer" || $payment_code == "free" ||$payment_code == "paypal_express" ||$payment_code = "authorizenet_directpost")) {
                //$this->ws_sendorderemail($res["orderid"]);
              try {
		            //get payment object from order object
		           $order->setState("processing")->setStatus("processing");
		           $order->setEmailSent(0);
            		$order->save();


					$paymentData = array(
		            		"id" =>  $transid,

		            	);
		            $payment = $order->getPayment();
		            $payment->setLastTransId($paymentData['id']);
		            $payment->setTransactionId($paymentData['id']);
		            $payment->setAdditionalInformation(
		                [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
		            );

		            $formatedPrice = $order->getBaseCurrency()->formatTxt(
		                $order->getGrandTotal()
		            );

		            $message = __('The authorized amount is %1.', $formatedPrice);
		            //get the object of builder class

		            $trans = $this->transactionBuilder;

		            $transaction = $trans->setPayment($payment)
		            ->setOrder($order)
		            ->setTransactionId($paymentData['id'])
		            ->setAdditionalInformation(
		                [\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS => (array) $paymentData]
		            )
		            ->setFailSafe(true)
		            //build method creates the transaction and returns the object
		            ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);

		            $payment->addTransactionCommentsToOrder(
		                $transaction,
		                $message
		            );
		            $payment->setParentTransactionId(null);

		            $payment->save();
		            $order->save();

					$transaction->save()->getTransactionId();

					$order = $this->_orderRepository->get($order->getId());
					//~ var_dump($res);die;
			        if($order->canInvoice()) {
			            $invoice = $this->_invoiceService->prepareInvoice($order);
			            $invoice->register();
			            $invoice->save();
			            $transactionSave = $this->_transaction->addObject(
			                $invoice
			            )->addObject(
			                $invoice->getOrder()
			            );
			            //~ $transactionSave->save();
			            //~ $this->invoiceSender->send($invoice);
			            //send notification code
			            $order->addStatusHistoryComment(
			                __('Notified customer about invoice #%1.', $invoice->getId())
			            )
			            ->setIsCustomerNotified(true)
			            ->save();
			            $order->setTotalPaid($invoice->getGrandTotal());
     					$order->setBaseTotalPaid($invoice->getBaseGrandTotal());
     					$order->save();
			        }

		        } catch (\Exception $e) {
		           // $echo= "get message : ".$ex->getMessage();
		        }
                $res["order_status"] = "PROCESSING";
            }elseif ($res["orderid"] > 0 && ($payment_code == "cashondelivery")){
                 $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING, true)->save();
                $res["order_status"] = "PENDING_PAYMENT";
            } else {
                $order->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT, true)->save();
                $res["order_status"] = "PENDING_PAYMENT";
            }
        }
        catch (\Exception $except) {
            $res["status"]          = 0;
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"]  = $payment_code;
        }

        return $res;
    }

    public function placeorder1($custid, $Jproduct, $store, $address, $couponCode, $is_create_quote, $transid, $payment_code, $shipping_code, $currency, $message)
    {
		$orderData =array();
        $res            = array();
        $quantity_error = array();
        try {
            $quote_data = $this->prepareQuote($custid, $Jproduct, $store, $address, $shipping_code, $couponCode, $currency, 1, 0); // self function
            if ($quote_data["status"] == "error") {
                return $quote_data;
            }
            $quote        = $this->_quote->load($quote_data['quote_id']);
            $quote        = $this->setQuoteGiftMessage($quote, $message, $custid); // self function
            $quote        = $this->setQuotePayment($quote, $payment_code, $transid); // self function
            $order        = $this->orderFactory->create();
            $addresses    = [];

            $this->dataObjectHelper->mergeDataObjects(
                '\Magento\Sales\Api\Data\OrderInterface',
                $order,
                $this->quoteAddressToOrder->convert($quote->getShippingAddress(), $orderData)
            );

            $shippingAddress = $this->quoteAddressToOrderAddress->convert(
                $quote->getShippingAddress(),
                [
                    'address_type' => 'shipping',
                    'email' => $quote->getCustomerEmail()
                ]
            );

            $addresses[] = $shippingAddress;
            $order->setShippingAddress($shippingAddress);
            $order->setShippingMethod($quote->getShippingAddress()->getShippingMethod());
            $billingAddress = $this->quoteAddressToOrderAddress->convert(
            $quote->getBillingAddress(),
				[
					'address_type' => 'billing',
					'email' => $quote->getCustomerEmail()
				]
			);

			$addresses[] = $billingAddress;
			$order->setBillingAddress($billingAddress);
			$order->setAddresses($addresses);
			$order->setPayment($this->quotePaymentToOrderPayment->convert($quote->getPayment()));
            $quoteItems = [];
			foreach ($quote->getAllItems() as $quoteItem) {
				/** @var \Magento\Quote\Model\ResourceModel\Quote\Item $quoteItem */
				$quoteItems[$quoteItem->getId()] = $quoteItem;
			}
			$orderItems = [];
			foreach ($quoteItems as $quoteItem) {
				$parentItem = (isset($orderItems[$quoteItem->getParentItemId()])) ?
					$orderItems[$quoteItem->getParentItemId()] : null;
				$orderItems[$quoteItem->getId()] =
					$this->quoteItemToOrderItem->convert($quoteItem, ['parent_item' => $parentItem]);
			}
			$oitems = array_values($orderItems);
			$order->setItems($oitems);
            if ($quote->getCustomer()) {
				$order->setCustomerId($quote->getCustomer()->getId());
			}
			$order->setQuoteId($quote->getId());
			$order->setCustomerEmail($quote->getCustomerEmail());
			$order->setCustomerFirstname($quote->getCustomerFirstname());
			$order->setCustomerMiddlename($quote->getCustomerMiddlename());
			$order->setCustomerLastname($quote->getCustomerLastname());

			$this->eventManager->dispatch(
				'sales_model_service_quote_submit_before',
				[
					'order' => $order,
					'quote' => $quote
				]
			);
			try {
				$order = $this->orderManagement->place($order);
				$quote->setIsActive(false);
				$this->eventManager->dispatch(
					'sales_model_service_quote_submit_success',
					[
						'order' => $order,
						'quote' => $quote
					]
				);
				$this->quoteRepository->save($quote);
				$this->_orderSender->send($order, $forceSyncMode = false);
			} catch (\Exception $e) {
				$this->eventManager->dispatch(
					'sales_model_service_quote_submit_failure',
					[
						'order'     => $order,
						'quote'     => $quote,
						'\Exception' => $e
					]
				);
				throw $e;
			}
			$echo= "<pre>";
			$this->getResponse()->setBody($echo);
			print_r($quote_data);
            if($quote_data['qty_flag']==1)
            {
            $quantity_error         = '';
            } else { $quantity_error         = $this->updateQuantityAfterOrder($Jproduct); } // self function
            $res["status"]          = 1;
            $res["id"]              = $order->getId();
            $res["orderid"]         = $order->getIncrementId();
            $res["transid"]         = $order->getPayment()->getTransactionId();
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"]  = $payment_code;
            $res["quantity_error"]  = $quantity_error;
            $order->addStatusHistoryComment("Order was placed using Mobile App")->setIsVisibleOnFront(false)->setIsCustomerNotified(false);
            if ($res["orderid"] > 0 && ($payment_code == "cashondelivery" || $payment_code == "banktransfer" || $payment_code == "free")) {
                //$this->ws_sendorderemail($res["orderid"]);
                $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING, true)->save();
                $res["order_status"] = "PROCESSING";
            } else {
                $order->setState(\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT, true)->save();
                $res["order_status"] = "PENDING_PAYMENT";
            }
        }
        catch (\Exception $except) {
            $res["status"]          = 0;
            $res["shipping_method"] = $shipping_code;
            $res["payment_method"]  = $payment_code;
        }

        return $res;
    }

    public function setQuoteGiftMessage($quote, $message, $custid)
    {
        $message_id = array();
        $message    = json_decode($message, true);
        if(!empty($message)){
			foreach ($message as $key => $value) {
				$giftMessage = $this->_giftMessage;
				$giftMessage->setCustomerId($custid);
				$giftMessage->setSender($value["sender"]);
				$giftMessage->setRecipient($value["receiver"]);
				$giftMessage->setMessage($value["message"]);
				$giftObj                 = $giftMessage->save();
				$message_id["msg_id"][]  = $giftObj->getId();
				$message_id["prod_id"][] = $value["product_id"];
				$quote->setGiftMessageId($giftObj->getId());
				$quote->save();
			}
	    }
        return $quote;
    }

    public function updateQuantityAfterOrder($Jproduct)
    {
        $error    = array();
        $Jproduct = str_replace(" ", "+", $Jproduct);

        $orderproduct = json_decode(base64_decode($Jproduct));
        try {
            foreach ($orderproduct as $key => $item) {
                $productId = $item->id;
                $orderQty  = $item->quantity;
                //get total quantity
                $totalqty  = (int) $this->stock->getStockItem($productId)->getQty();
                //calculate new quantity
                $newqty    = $totalqty - $orderQty;
                //update new quantity
                try {
                    $product = $this->_product->load($productId);
                    $product->setStockData(array(
                        'is_in_stock' => $newqty ? 1 : 0, //Stock Availability
                        'qty' => $newqty //qty
                    ));
                    $product->save();
                }
                catch (\Exception $ee) {
                    $error[] = $ee->getMessage();
                }
            }
        }
        catch (\Exception $ex) {
            $error[] = $ex->getMessage();
        }
        return $error;
    }

    public function setQuotePayment($quote, $pmethod, $transid)
    {
        $quotePayment = $quote->getPayment();
        $quotePayment->setMethod($pmethod)->setIsTransactionClosed(1)->setTransactionAdditionalInfo(\Magento\Sales\Model\Order\Payment\Transaction::RAW_DETAILS, array(
            'TransactionID' => $transid,
            'key2' => 'value2'
        ));
        $quotePayment->setCustomerPaymentId($transid);
        $quote->setPayment($quotePayment);
        return $quote;
    }

    function ws_mofluid_reorder($store, $service, $jproduct, $orderId, $currentcurrencycode)
    {
		$storeObj = $this->_storeManager;
		$scopeConfig = $this->_scopeconfig;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $productids = json_decode($jproduct);
        $countres   = 0;
        $res        = array();
        $order      = $this->_orderData->loadByIncrementId($orderId);
        #get all items
        $items      = $order->getAllItems();
        $itemcount  = count($items);
        $data       = array();
        $i          = 0;
        #loop for all order items
        foreach ($items as $itemId => $product) {
			$stock = $this->stock->getStockItem($product->getProductId());

           $current_product_id         = $product->getProductId();
            $current_product_index      = $itemId;
            $has_custom_option          = 0;
            $custom_attr                = array();
            $current_product            = $this->_product->load($current_product_id);
            $mofluid_all_product_images = array();
            $mofluid_non_def_images     = array();
            $mofluid_baseimage          = $media_url . 'catalog/product' . $current_product->getImage();
            foreach ($current_product->getMediaGalleryImages() as $mofluid_image) {
                $mofluid_imagecame = $mofluid_image->getUrl();
                if ($mofluid_baseimage == $mofluid_imagecame) {
                    $mofluid_all_product_images[] = $mofluid_image->getUrl();
                } else {
                    $mofluid_non_def_images[] = $mofluid_image->getUrl();
                }
            }
            $mofluid_all_product_images              = array_merge($mofluid_all_product_images, $mofluid_non_def_images);
            $basecurrencycode                        = $storeObj->getStore($store)->getBaseCurrencyCode();
            $res[$countres]["id"]                    = $current_product->getId();
            $res[$countres]["sku"]                   = $current_product->getSku();
            $res[$countres]["name"]                  = $current_product->getName();
            $res[$countres]["category"]              = $current_product->getCategoryIds();
            $res[$countres]["image"]                 = $mofluid_baseimage;
            $res[$countres]["url"]                   = $current_product->getProductUrl();
            $res[$countres]["description"]["full"]   = base64_encode($current_product->getDescription());
            $res[$countres]["description"]["short"]  = base64_encode($current_product->getShortDescription());
            $res[$countres]["quantity"]["available"] = $stock->getQty();
            $res[$countres]["quantity"]["order"]     = $product->getQtyOrdered();
            $res[$countres]["visibility"]            = $current_product->isVisibleInSiteVisibility(); //getVisibility();
            $res[$countres]["type"]                  = $current_product->getTypeID();
            $res[$countres]["weight"]                = $current_product->getWeight();
            $res[$countres]["status"]                = $current_product->getStatus();
            $res[$countres]["isInStock"]             = $stock->getIsInStock();

            //convert price from base currency to current currency
            $res[$countres]["currencysymbol"]        = $this->_currency->getCurrency($currentcurrencycode)->getSymbol();
            $defaultprice                            = str_replace(",", "", ($product->getPrice()));
            $res[$countres]["price"]                 = strval(round($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2));
            $discountprice                           = str_replace(",", "", ($product->getFinalPrice()));
            $res[$countres]["discount"]              = strval(round($this->convert_currency($discountprice, $basecurrencycode, $currentcurrencycode), 2));
            $defaultshipping                         = $scopeConfig->getValue(
															'carriers/flatrate/price',
															\Magento\Store\Model\ScopeInterface::SCOPE_STORE
														);
            $res[$countres]["shipping"]              = strval(round($this->convert_currency($defaultshipping, $basecurrencycode, $currentcurrencycode), 2));
            $defaultsprice                           = str_replace(",", "", ($product->getSpecialprice()));
            // Get the Special Price
            $specialprice                            = $current_product->getSpecialPrice();
            // Get the Special Price FROM date
            $specialPriceFromDate                    = $current_product->getSpecialFromDate();
            // Get the Special Price TO date
            $specialPriceToDate                      = $current_product->getSpecialToDate();
            // Get Current date
            $today                                   = time();
            if ($specialprice) {
                if ($today >= strtotime($specialPriceFromDate) && $today <= strtotime($specialPriceToDate) || $today >= strtotime($specialPriceFromDate) && is_null($specialPriceToDate)) {
                    $specialprice = strval(round($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 2));
                } else {
                    $specialprice = 0;
                }
            } else {
                $specialprice = 0;
            }
            $current_product_options  = array();
            $res[$countres]["sprice"] = $specialprice;
            $has_custom_option        = 0;
				foreach ($product->getProductOptions() as $opt) {
					$has_custom_option       = 1;
					if(array_key_exists('options',$opt)){
						$current_product_options = $opt['options'];
						if (!$current_product_options) {
							foreach ($opt as $opt_key => $opt_val) {
								$current_product_options[$opt_val['option_id']] = $opt_val['option_value'];
							}
						}
						break;
					}
				} //foreach
			$res[$countres]["has_custom_option"] = $has_custom_option;
            if ($has_custom_option == 1) {
                $res[$countres]["custom_option"] = $current_product_options;
            }
            $res[$countres]["custom_attribute"] = $custom_attr;
            $countres++;
        }
        return ($res);
    }

    function ws_filter($store_id, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currentcurrencycode,$filterdata){
		// var_dump($curr_page, $page_size, $sortType, $sortOrder);die;
		if($sortType == null || $sortType == 'null'){
			$sortType = 'name';
		}
		if($sortOrder == null || $sortOrder == 'null'){
			$sortOrder = 'ASC';
		}
		if($curr_page == null || $curr_page == 'null'){
			$curr_page = 1;
		}
		if($page_size == null || $page_size == 'null'){
			$page_size = 10;
		}
		$storeObj = $this->_storeManager;
		//~ $cache = $this->_cache;
		$scopeConfig = $this->_scopeconfig;
		$media_url = $storeObj->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $storeObj->getStore()->setCurrentStore($store_id);
        $res = array();
        $basecurrencycode   = $storeObj->getStore($store_id)->getBaseCurrencyCode();
        $c_id     = $categoryid;
        $category = $this->_category;
        $product = $this->_product;
        $category->load($c_id);
        //~ var_dump($category->getData());die;
        $collection   = $category->getProductCollection()->addStoreFilter($store_id)->addAttributeToSelect('*')->addAttributeToFilter('type_id', array(
            'in' => array(
                'simple',
                'configurable',
                'grouped'
            )
        ))->addAttributeToFilter('visibility', 4)->addAttributeToFilter('status', 1)->addAttributeToSort($sortType, $sortOrder);
        //print_r($collection->getData()); die;
        if($sortType != 'name'){
				$collection->addAttributeToSort(trim('name'), trim($sortOrder));
		}
        //~ var_dump($sortType, $sortOrder);die;
        $filterdata2 = json_decode($filterdata, true);
        $sizeflag = '1';
		$colorflag='1';
		$filterArray = array();
        if($filterdata2 != null){//var_dump($filterdata2);die;
			foreach ($filterdata2 as $filterCode => $filterValue) {
				$filterArray = array();
				if($filterValue['code'] != 'price'){
					$code=$filterValue['code'];
					if($code == 'size')
					   $sizeflag = '0';
					if($code == 'color')
					   $colorflag = '2';
					$ids = array_map('intval', explode(',',$filterValue['id']));
					foreach($ids as $key => $value){
						$filterArray[] = array('attribute'=> $code,'finset' => $value);
					}
					$collection->addFieldToFilter($filterArray);
				}else{
					$code = $filterValue['code'];
					$filterValueArr = explode('-',$filterValue['id']);
					$priceArray = array(
										array('attribute'=> 'price',array('from'=>$filterValueArr[0],'to'=>$filterValueArr[1])),
										//array('attribute'=> 'special_price',array('from'=>$filterValueArr[0],'to'=>$filterValueArr[1]))
										);
					$collection->addFieldToFilter($priceArray);
				}
			}
		} //print_r($collection->getData()); die;
		//~ var_dump($collection->getSelect()->__toString());die;
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
            '(' . join(') OR (', $cond) . ')'
        );

        $collection->setPage($curr_page, $page_size);
        $res["category_name"]=$category->getName();
		$res["total"] = $collection->getSize();
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		 foreach ($collection as $_product) {
			 $gflag=1;
            $mofluid_product            = $product->load($_product->getId());
            $mofluid_baseimage          = $media_url.'catalog/product' . $mofluid_product->getThumbnail();
            $defaultprice  = str_replace(",", "", number_format($_product->getPrice(), 2));
            $defaultsprice = str_replace(",", "", number_format($_product->getSpecialprice(), 2));
            $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
			$thumbnailimage = $imagehelper->init($mofluid_product, 'category_page_list')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
            try {
                $custom_options        = $mofluid_product->getOptions();
                $has_custom_option     = 0;
                foreach ($custom_options as $optionKey => $optionVal) {
                    $has_custom_option = 1;
                }
            }
            catch (\Exception $ee) {
                $has_custom_option = 0;
            }
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

             if ($_product->getTypeID() == 'grouped') {

             	//$defaultprice = number_format($this->getGroupedProductPrice($_product->getId(), $currentcurrencycode) , 2, '.', '');
                //$specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
                //$associatedProducts = $_product->getTypeInstance(true)->getAssociatedProducts($_product);
             	//if(count($associatedProducts)) { $gflag=1; }else{ $gflag=0; }
            }
            else
            {
            	 $defaultprice =  number_format($_product->getPrice(), 2, '.', '');
           		 $specialprice =  number_format($_product->getFinalPrice(), 2, '.', '');
            }
             if ($_product->getTypeID() == 'configurable') {
				$defaultprice = $specialprice;
			 }

            if($defaultprice == $specialprice)
                $specialprice = number_format(0, 2, '.', '');

           $stock = $this->stock->getStockItem($_product->getId());
           if($gflag)
           {
            $res["data"][] = array(
                "id" => $_product->getId(),
                "name" => $_product->getName(),
                "imageurl" => $thumbnailimage,
                "sku" => $_product->getSku(),
                "type" => $_product->getTypeID(),
                "spclprice" => number_format($this->convert_currency($specialprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "currencysymbol" => $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
                "price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 2, '.', ''),
                "created_date" => $_product->getCreatedAt(),
                "is_in_stock" => $stock->getIsInStock(),
                "hasoptions" => $has_custom_option,
                "stock_quantity" => $stock->getQty()
            );
            }
		 }
		 return $res;
	}

	function ws_getcategoryfilter($store,$categoryid){
		$storeObj = $this->_storeManager;
		$scopeConfig = $this->_scopeconfig;
		$storeObj->getStore()->setCurrentStore($store);
        $category = $this->_category;
        $product = $this->_product;
        $category->load($categoryid);
        $categoryId = $categoryid;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		$filterableAttributes = $objectManager->get(\Magento\Catalog\Model\Layer\Category\FilterableAttributeList::class);
		$appState = $objectManager->get(\Magento\Framework\App\State::class);
		$layerResolver = $objectManager->get(\Magento\Catalog\Model\Layer\Resolver::class);
		$filterList = $objectManager->create(
		\Magento\Catalog\Model\Layer\FilterList::class,
			[
				'filterableAttributes' => $filterableAttributes
			]
		);

		$layer = $layerResolver->get();
		$layer->setCurrentCategory($categoryId);
		$filters = $filterList->getFilters($layer);

		$finalFilters = [];
		$count = 0;
		foreach ($filters as $filter) {
			if($filter->getItemsCount()){
				$finalFilters[$count]['code'] = $filter->getRequestVar();
				$finalFilters[$count]['label'] = $filter->getName();
				$finalFilters[$count]['type'] = 'select';
				$attributeCode = array();
				foreach ($filter->getItems() as $item) {
					if($item->getName() == 'Price'){
						$priceLabel = $item->getLabel()->getArguments();
						$_priceLabel = $item->getValue();
						if(isset($priceLabel[0])){
							$_priceLabel = strip_tags($priceLabel[0]);
						}
						if(isset($priceLabel[1])){
							if(!isset($priceLabel[0])){
								$_priceLabel = '';
							}
							$_priceLabel.= ' - '.strip_tags($priceLabel[1]);
						}
						$attributeCode[] = array(
											'label' => $_priceLabel,
											'id' => $item->getValue(),
											'count' => (string)$item->getCount(),
										);
					}else{
						$attributeCode[] = array(
											'label' => $item->getLabel(),
											'id' => $item->getValue(),
											'count' => (string)$item->getCount(),
										);

					}

				}
				$finalFilters[$count]['values'] = $attributeCode;
				$count++;
			}
		}
		return $finalFilters;
	}
	public function getProductStock1($store_id,$service,$product_id)
    {
		$res = array();
        $i =0;
		$product=   explode(",",$product_id);
		foreach($product as  $productkey => $productvalue){
			$stock_data    = array();
			$stock_product = $this->stock->getStockItem($productvalue);
			$stock_data    = $stock_product->getData();

		        $res[$i] = array( "Product id" =>$stock_data['product_id'],
			                  "Quantity" =>$stock_data['qty'],"isInStock" =>$stock_data['is_in_stock']
			                           );
			                           $i++;
			   }
	  return $res;
    }


	 /*====================== stripe payment ======================  */

    public function ws_retrieveCustomerStripe($customer_id)
	{
		$stripeData = $this->getStripeKey();
		$apiKey = $stripeData[0]['payment_method_account_key'];
		try {
		   $customer =  \Stripe\Customer::retrieve($customer_id,$apiKey);
	       return ($customer);
		} catch(\Exception $e) {
		  return $e ;
		}

	}
	 /*====================== stripe payment End ======================  */
	  /*====================== stripe payment card ======================  */
	      public function ws_createCardStripe($customer_id,$token_id)
			  {  //var_dump($customer_id,$token_id); die;
				  $stripeData = $this->getStripeKey();
				$apiKey = $stripeData[0]['payment_method_account_key'];
				\Stripe\Stripe::setApiKey($stripeData[0]['payment_method_account_key']);
			try {
			   $customer = \Stripe\Customer::retrieve($customer_id,$apiKey);

			   if($customer){
				   /*$result   =  \Stripe\Token::create(array(
												"card" => array(
												"number" => "4242424242424242",
												"exp_month" => 12,
												"exp_year" => 2017,
												"cvc" => "314"
																)
												));
	            $token_id = $result['id']; */
				 //~ $card = $customer->sources->retrieve($card_id);
				 //~ return ($card);
				//~ }
				//~ else{
					 $customer = $customer->sources->create(array("source" => $token_id));
				 }
			  return $customer;
			} catch(\Exception $e) {
			  return $e;
			}
		  }

	   /*====================== stripe payment card End ======================  */
	    	  /*====================== stripe customer create ======================  */
	       public function stripecustomercreate($mofluid_Custid,$token_id,$email,$name)
	      {

			  $stripeData = $this->getStripeKey();

			 //var_dump($mofluid_Custid,$token_id,$email,$name);die;
			 //~ $stripeData = $this->stripeData($mofluid_Custid);
			 //~ var_dump($mofluid_Custid,$stripeData);die;
			//~ if($stripeData != null && false){
				//~ $res["id"] = $stripeData;
				//~ return $res;
			//~ }
			//~ var_dump($mofluid_Custid);
			//~ $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			//~ $model = $objectManager->create('Magedelight\Stripe\Model\Cards');
			//~ $data = ['customer_id' => 30, 'stripe_customer_id' => '204ss8'];
			//~ $model->setData($data);
			//~ $model->save();

			$customerData = $this->_customer->load($mofluid_Custid);

			try {

				\Stripe\Stripe::setApiKey($stripeData[0]['payment_method_account_key']);

			//~ $result   =  \Stripe\Token::create(array(
												//~ "card" => array(
												//~ "number" => "4242424242424242",
												//~ "exp_month" => 12,
												//~ "exp_year" => 2017,
												//~ "cvc" => "314"
																//~ )
												//~ ));
	           //~ $token_id = $result['id'];
				$customer = \Stripe\Customer::create(array(
										"description" =>$name,// " kaleshwar",
										"source" => $token_id,
										"email" => $email//"kaleshwar@jaiswal.com"
										));
				$stripeCus = json_decode(json_encode($customer));

				if($stripeCus->id != null && !empty($stripeCus->id)){
					$customerData->setMdStripeCustomerId($stripeCus->id);
					$customerData->save();
				}

			   return ($customer);

			} catch(\Exception $e) {
			  return $e;
			}
		  }

	   /*======================  stripe customer create  End ======================  */
	   	  /*====================== stripe payment Update ======================  */
	      public function ws_customerUpdateStripe($customer_id, $discription)
	      {
			  $stripeData = $this->getStripeKey();

			  $apiKey=$stripeData[0]['payment_method_account_key'];

			try {
				\Stripe\Stripe::setApiKey($apiKey);

				$result   =  \Stripe\Token::create(array(
													"card" => array(
													"number" => "4242424242424242",
													"exp_month" => 12,
													"exp_year" => 2017,
													"cvc" => "314"
																	)
													));
                $token = $result['id'];
				$customer = \Stripe\Customer::retrieve($customer_id,$apiKey);
			    $customer->description =  $discription;
				$customer->source = $token;
				$customer->save();
				return $customer;
			} catch(\Exception $e) {
			  return $e;
			}
		  }
		    public function chargeStripe($customer_id,$price,$currency,$card_id)
	      {
			  $stripeData = $this->getStripeKey();
			  //~ $customer_id = "cus_9pMHnFjnQm1CFt";
				try {
					  //~ $currency = "usd";
					 //~ $price = 23;
					  $price = $price * 100;
				\Stripe\Stripe::setApiKey($stripeData[0]['payment_method_account_key']);
				 $result = \Stripe\Charge::create(array(
							  "amount" => $price, // Amount in cents
							  "currency" => $currency,
							  "customer" => $customer_id,
							  "source" => $card_id)
							);
					return ($result);
				} catch(\Exception $e) {
				  return $e;
				}
		  }

		public function getStripeKey(){
			$mofluid_pay_data = $this->_mpayment->getCollection()->addFieldToFilter('payment_method_status', 1)->addFieldToFilter('payment_method_code', 'stripe')->getData();
			return $mofluid_pay_data;
		}
	   /*====================== stripe payment  ======================  */
      /*--------------- cart sync webservice start---------------------- */
      public function ws_addCartItem($store_id, $service, $custid, $product_id, $qty)
    {          //var_dump($store_id, $service, $custid, $product_id, $qty); die;

				try {
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$parentIds = $objectManager->create('Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable')->getParentIdsByChild($product_id);//print_r($parentIds); die;
				if (!empty(array_filter($parentIds))) {
					$pid = $parentIds[0];
					$child_id = $product_id;
					$product_id = $pid;
					$product = $this->_product->load($product_id);
					//print_r($product->getData());
					$configurableProduct = $this->_product->load($pid);
					$productAttributeOptions      = $configurableProduct->getTypeInstance(true)->getConfigurableAttributes($configurableProduct);
					$options = array();
						foreach ($productAttributeOptions as $productAttribute) {
						$allValues = array_column($productAttribute['values'], 'value_index');
						$currentProductValue = $product->getData($productAttribute['attribute_code']);
								if (in_array($currentProductValue, $allValues)) {
									$options[$productAttribute['attribute_id']] = $currentProductValue;
								}

						}

					}
					$product = $this->_product->load($product_id);//print_r($product->getTypeId()); die;
					$customerObj= $this->_customer;
					$customer = $customerObj->load($custid);
					$session =$this->_session;
					$session->loginById($customer);
					$quote = $this->_quote->loadByCustomer($customer);
					//print_r($quote->getData()); die;
					if($product->getTypeId() == "configurable"){
							 $productQuantity =$this->stock->getStockItem($child_id);
						 	if($productQuantity->getQty() < $qty){
										$result = array(
													"status" => "The maximum quantity available for product is ".$productQuantity->getQty()."."
													);
										return $result;
									}

						$params = array(
						'product' => $product->getId(),
						'super_attribute' => $options,
						'qty' => $qty,

					);
						}
				else{
				$productQuantity = $this->stock->getStockItem($product_id);
				//print_r($productQuantity->getQty()); die;
				if($productQuantity->getQty() < $qty){
										$result = array(
													"status" => "The maximum quantity available for product is ".$productQuantity->getQty()."."
													);
										return $result;
									}
				$params = array(
						'product' => $product->getId(),
						'qty' => $qty,
						'form_key'=>$this->formKey->getFormKey(),


					);
			}
			 $collection = $quote->getItemsCollection(false);
			//print_r($collection->getData()); die;
             $searchcounter = 0;
                      if ($collection->count() > 0) { //die('hello2');
						foreach ($collection as $item) {
							if ($item && $item->getId()) {
								if($product->getTypeId() == "configurable"){ //die('hello2');
									$productId = '';
									if ($option = $item->getOptionByCode('simple_product')) {
										$productId = $option->getProduct()->getId();
									}
									if ($productId == $child_id) {
										$searchcounter++;

										if($productQuantity->getUseConfigMinSaleQty() == 1){
											if($productQuantity->getMaxSaleQty() < $qty){
												$result = array(
															"status" => "The maximum quantity allowed for purchase is ".$productQuantity->getMaxSaleQty()."."
															);
												return $result;
											}
											//print_r($productQuantity->getQty());
											if($productQuantity->getQty() < $qty){
												$result = array(
															"status" => "The maximum quantity available in stock is ".$productQuantity->getQty()."."
															);
												return $result;
											}
										}
										//~ $quote->removeItem($item->getId());
										$item->setQty($qty);
										if ($quote->collectTotals()->save()) {
											$this->_session->setCartWasUpdated(true);
											$result = array(
												'status' => 'success'
											);
											return $result;
										}
									}
								}
								else{ //print_r($item->getProduct()->getId()); die;
									if ($item->getProduct()->getId() == $product_id) {
										$searchcounter++;

										if($productQuantity->getUseConfigMinSaleQty() == 1){
											if($productQuantity->getMaxSaleQty() < $qty){
												$result = array(
															"status" => "The maximum quantity allowed for purchase is ".$productQuantity->getMaxSaleQty()."."
															);
												return $result;
											}

											if($productQuantity->getQty() < $qty){
												$result = array(
															"status" => "The maximum quantity available in stock is ".$productQuantity->getQty()."."
															);
												return $result;
											}
										}
										//~ $quote->removeItem($item->getId());
										//~ var_dump($item->getQty());die;
										$item->setQty($qty);

										if ($quote->collectTotals()->save()) {

											$this->_session->setCartWasUpdated(true);
											$result = array(
												'status' => 'success'
											);
											return $result;
										}
									}
								}
							}
						}
            } else {
            	//$echo= "<pre>"; print_r($params);
                //~ $quote->addProduct($product,$params);
                //~ $quote->collectTotals()->save();
				$this->_cart->addProduct($product,$params);
				$this->_cart->save();
               $this->checkoutSession->setCartWasUpdated(true);
                $result = array(
                            'status' => 'success'
                        );
                        //$this->checkoutSession->setCartWasUpdated(true);
                return $result;
            }
             if ($searchcounter == 0) {
              //  $quote->addProduct($product,$params);
				$this->_cart->addProduct($product,$params);
				$this->_cart->save();
                $this->checkoutSession->setCartWasUpdated(true);
                $result = array(
                'status' => 'success'
            );
           // $this->checkoutSession->setCartWasUpdated(true);

             }
            ############################################


         }
                catch (\Exception $e) {
            $e->getMessage();
            $result = array(
                'status' => $e->getMessage()
            );
        }

        return $result;


    }
    public function ws_getCartItem($store_id,$service,$custid,$currency){
		$currentcurrencycode=$currency;
        $res = array();
        $basecurrencycode = $this->_storeManager->getStore($store_id)->getBaseCurrencyCode();
        $totalCount = 0;
    if ($custid) {
        $quote = $this->_quote->loadByCustomer($custid);
        if ($quote) {
            $collection = $quote->getItemsCollection();
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            if (true || $collection->count() > 0) {
                foreach( $collection as $item ) {
			if ($item->getParentItemId()) {
			        continue;
			}
			$totalCount++;
            $pid = $item->getProduct()->getId();  //print_r($pid); die;
			$defaultprice  = str_replace(",", "", number_format($item->getProduct()->getPrice(), 3));
            $defaultsprice = str_replace(",", "", number_format($item->getProduct()->getFinalPrice(), 3));
           //$cartItem = Mage::getSingleton('checkout/cart')->getQuote()->getItemByProduct($pid);
          // $cartItem->getQty();

			// $mofluid_product            = Mage::getModel('catalog/product')->load($pid);
			$mofluid_product= $this->_product->load($pid); //print_r($mofluid_product->getId()); die;
            $imagehelper = $objectManager->create('Magento\Catalog\Helper\Image');
			$productImage = $imagehelper->init($mofluid_product, 'category_page_grid')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
	     	$childProductData = $item->getOptionByCode('simple_product');
	    	if($childProductData == null){
			$childProduct = $mofluid_product;
		   }else{
			  $childProduct = $childProductData->getProduct();
		}
		   $mofluid_child_product            = $childProduct;
//~
//~ print_r($mofluid_product->getData()); die;
		//~ $childproductImage = $imagehelper->init($mofluid_child_product, 'category_page_grid')->constrainOnly(FALSE)->keepAspectRatio(TRUE)->keepFrame(FALSE)->resize(200)->getUrl();
  $stock = $this->stock->getStockItem($mofluid_product->getId());
  //~ print_r($mofluid_product->getId());
  //~ print_r( $stock->getIsInStock()); die;
//print_r($childproductImage); die;
		//var_dump($childProduct->getImageUrl());die;
				$res["data"][] = array(
                "id" => $childProduct->getId(),
                "name" => $item->getProduct()->getName(),
              //  "imageurl" => Mage::getModel('catalog/product_media_config')->getMediaUrl($childProduct->getImage()),
              //  "img" => (string)$childproductImage,
                "sku" => $item->getProduct()->getSku(),
                "type" => $childProduct->getTypeID(),
                "spclprice" => number_format($this->convert_currency($defaultsprice, $basecurrencycode, $currentcurrencycode), 3, '.', ''),
                "currencysymbol" =>  $this->_currency->getCurrency($currentcurrencycode)->getSymbol(),
                "price" => number_format($this->convert_currency($defaultprice, $basecurrencycode, $currentcurrencycode), 3, '.', ''),
                "created_date" => $item->getProduct()->getCreatedAt(),
                "is_in_stock" =>  $stock->getIsInStock(),
                 "stock_quantity" =>$stock->getQty(),
                "quantity" => $item->getQty()
                //~ "max_sale_qty" => $childProduct->getStockItem()->getMaxSaleQty(),
                //~ "min_sale_qty" => $childProduct->getStockItem()->getMinSaleQty()
            );


                }   //print_r($res); die;
            } else{
				$res["data"] = array();

				}
        }else{
		$res["data"] = array();
	}
    }
	if(!isset($res["data"])){
		$res["data"] = array();
	}

	//~ $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
	//~ $resource = Mage::getResourceModel('sales/quote');
	//~ $connection->update(
	    //~ $resource->getMainTable(),
	    //~ array('is_active' => 1),
	    //~ array('entity_id = ?' => $quote->getId() ));
	//~ $res['total'] = $totalCount;
	//~ $res['quoteID'] = $quote->getId();
    return $res;
    }
      /*--------------- cart sync webservice End---------------------- */

 /**************************************Authorize.net********************/
    	public function authorizepayment()
    	{

		   	$mofluid_pay_data = array();
		    $mofluid_pay_data = $this->_mpayment->getCollection()->addFieldToFilter('payment_method_status', 1)->getData();
			foreach($mofluid_pay_data as $key=>$mofluid_pay_datas)
			{
			    if($mofluid_pay_datas['payment_method_title'] == "Authorize.Net")
			    {
					$name = $mofluid_pay_datas['payment_method_account_id'];
					$transactionkey = $mofluid_pay_datas['payment_method_account_key'];
				}

			}
			//var_dump($name, $transactionkey);
			$card = str_replace(' ', '',$this->getRequest()->getParam('card'))	;
			$cardNumber = (int)$card ;
			 //var_dump($cardNumber); die;
			$uri = str_replace(' ', '',$this->getRequest()->getParam('date'));
			$date =   str_replace('/', '',$uri);
			$expirationDate = (int)$date;
			$cvv = $this->getRequest()->getParam('cvv');
			$cardCode = (int)$cvv;
			$amount =$this->getRequest()->getParam('amount');
			$plateform =$this->getRequest()->getParam('plateform');
			$amount = (float)$amount ;
			$id =$this->getRequest()->getParam('customer_id');
			$id = (int)$id;
			$refid= $this->getRequest()->getParam('orderId');
			$refId = (int)$refid;
			$curl = curl_init();
			//~ $transactionkey="8882mgAXcAQ322w3";
			//~ $refId="1534";
			//~ $amount=5;
			//~ $cardNumber=4012888888881881;
			//~ $expirationDate=1220;
			//~ $cardCode=999;
			//~ $id=99999456699;
			//~ $name="64v5LRbfW";
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://api.authorize.net/xml/v1/request.api",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS =>'{"createTransactionRequest": {"merchantAuthentication": {"name": "'.$name.'","transactionKey":"'.$transactionkey.'"},"refId":"'.$refId.'","transactionRequest": {"transactionType":"authCaptureTransaction","amount":"'.$amount.'","payment":{"creditCard":{"cardNumber": "'.$cardNumber.'","expirationDate": "'.$expirationDate.'","cardCode":"'.$cardCode.'"}},"customer":{"id":"'.$id.'"}}}}',
			CURLOPT_HTTPHEADER => array("cache-control: no-cache",
						"content-type: application/json",
						"postman-token: f545d40b-be26-4c2e-2f85-c269c378de47"
					  ),
					));

			$response1 = curl_exec($curl);
					//print_r($response1); die;
			$err = curl_error($curl);
			curl_close($curl);

			if ($err) 
			{
				$echo= "cURL Error #:" . $err;
				$this->getResponse()->setBody($echo);
			} 
			else
			{
				$response = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response1), true );
				//$echo= $response['transactionResponse']['responseCode'] ; die;
				//print_r($response); die;
		      	if($plateform =='android')	
		      	{ 
?>
					<script>
						var android  = '<?php $echo= $response1 ?>' ;
 						androidInterfaceCallback.getFormData(android);
					</script>
<?php	
				} 

				else
				{
					if($response['transactionResponse']['responseCode']== '1' ) 
					{
									//$echo= $response['transactionResponse']['responseCode']; die;
									//  $echo=   $response1;
						
?>
						<script>
					    	$echo= $response;
							var xyz = '<?php $this->getResponse()->setBody($echo) ?>' ;

							window.location = 'ios:webToNativeCall_transid=<?php $echo= $response['transactionResponse']['transId']; $this->getResponse()->setBody($echo);?>';

							iosInterfaceCallback.getFormData(xyz);
						</script>

<?php						
				    }

				    if($response['transactionResponse']['responseCode']== '2' )
				    
					{
									//	$echo= $response['transactionResponse']['responseCode']; die;
?>	
						<script>

					   		var xyz = '<?php $echo= $response;$this->getResponse()->setBody($echo); ?>' ;
					   		//console.log('XYZ'+xyz); die;
							function locationChange()
							{
								window.location = 'ios:webToNativeCall';
							}
							
							iosInterfaceCallback.getFormData(xyz);
						</script>

<?php	
				   	}
					
					if($response['transactionResponse']['responseCode']== '3' )
				 
					{
						//$echo= $response['transactionResponse']['transId']; die;
?>		
						<script>

							var xyz = '<?php $echo= $response;$this->getResponse()->setBody($echo); ?>' ;
							window.location = 'ios:webToNativeCall_transid=<?php $echo= $response['transactionResponse']['transId']; $this->getResponse()->setBody($echo);?>';

							iosInterfaceCallback.getFormData(xyz);
						</script>

<?php	
					}

					if($response['transactionResponse']['responseCode']== '4' ) 
				    {
						//$echo= $response['transactionResponse']['transId']; die;
?>						
						<script>
							var xyz = '<?php $echo= $response;$this->getResponse()->setBody($echo); ?>' ;
						   	//console.log('XYZ'+xyz);
							function locationChange()
							{
								window.location = 'ios:webToNativeCall';
							}
							iosInterfaceCallback.getFormData(xyz);
						</script>
<?php
					}
			}
		}
	}	

	public function authorizecheckout($payment_data)    
	{
		      $data =  base64_decode($payment_data);
		      $datadecode = json_decode($data);
		   //   print_r($datadecode->orderid); die;
		      // $echo= $datadecode->orderId;  die;
		   //   $echo= $datadecode->id; die;
		  // var_dump($datadecode->id); die;
		      //~ {
					//~ "id"		:"56565456",
					//~ "amount"      	:"340"
					//~
				//~ }
		     //~
		          $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
				$storeManager->getStore()->getBaseUrl();
									//$echo= $storeManager->getStore()->getBaseUrl(); die;
?>

	<html>
		<head>
			<head><meta name='viewport' content='user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height'></meta></head>
			<title>Authorize.Net Payment</title>


		<script>
			function validateForm() 
			{
			    var x = document.forms["myForm"]["card"].value;
			    var y = document.forms["myForm"]["date"].value;
			    var z = document.forms["myForm"]["cvv"].value;


			    if (x == "") {
			        //alert("Card must be filled out");
			        return false;
			    }
			   if (y == "") {
			       // alert("Date must be filled out");
			        return false;
			    }
			   if (z == "") {
			        //alert("Cvv must be filled out");
			        return false;
			    }
			}
		</script>
		<style>
					/* NOTE: The styles were added inline because Prefixfree needs access to your styles and they must be inlined if they are on local disk! */
					@import url("http://fonts.googleapis.com/css?family=Open+Sans:400,600");
					@import url("http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
					* {
							margin: 0;
							padding: 0;
							box-sizing: border-box;
					}

					html, body {

					height: 100%;
						
					}

					body {
							font: 16px/1 'Open Sans', sans-serif;
							color: #555;
							background-image:url('<?php $echo= $storeManager->getStore()->getBaseUrl();$this->getResponse()->setBody($echo);?>js/jack.png');
					}

					.title {
					  padding: 35px 0 40px;
					  text-align: center;
					}

					.title span {
					  display: block;
					  margin: 0 0 15px;
					  font-size: 30px;
					}

					.payment {
					  width: 100%;
					  margin: 0 auto;
					  padding: 35px 0;

					}

					[placeholder]::-webkit-input-placeholder {
					  color: rgba(0, 0, 0, 0.3);
					}

					[placeholder]:hover::-webkit-input-placeholder {
					  color: rgba(0, 0, 0, 0.15);
					}

					[placeholder]:focus::-webkit-input-placeholder {
					  color: transparent;
					}

					[placeholder]::-moz-placeholder {
					  color: rgba(0, 0, 0, 0.3);
					}

					[placeholder]:hover::-moz-placeholder {
					  color: rgba(0, 0, 0, 0.15);
					}

					[placeholder]:focus::-moz-placeholder {
					  color: transparent;
					}

					[placeholder]:-ms-input-placeholder {
					  color: rgba(0, 0, 0, 0.3);
					}

					[placeholder]:hover:-ms-input-placeholder {
					  color: rgba(0, 0, 0, 0.15);
					}

					[placeholder]:focus:-ms-input-placeholder {
					  color: transparent;
					}

					button::-moz-focus-inner,
					input::-moz-focus-inner {
					  border: 0;
					  padding: 0;
					}

					label, input, button {
					  display: block;
					  width: 350px;
					  margin: 0 auto 20px;
					}

					label {

					  font-weight: 600;
					  color: #FFFFFF;
					}

					input {
					  padding: 10px 15px;
					  font-size: 14px;
					  color: inherit;
					  border: 1px solid #aaa;
					  outline: 0;
					}

					button {
					  padding: 10px 15px;
					  font-size: 14px;
					  font-weight: 600;
					  color: #fff;
					  border: 0;

					  background: #2284a1;
					}

					input.invalid {
					  border: 1px solid red;
					}

					.validation.failed:after {
					  color: red;
					  content: 'Validation failed';
					}
					.validation{
				    	height: 1%;
					}

					.validation + button{
						border-radius: 0.5em;
					}
					input
					{
						border-radius: 0.5em;
					}
		</style>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>

		</head>

					<body>
					  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.0/jquery.min.js'></script>
					<script src='http://stripe.github.io/jquery.payment/lib/jquery.payment.js'></script>
					<script src="<?php $echo= $storeManager->getStore()->getBaseUrl();$this->getResponse()->setBody($echo);?>js/index.js"></script>
					<div class="title">
					  <!--span>Authorize.Net Payment</span-->

					</div>

					<div class="payment">

					  <form name="myForm" action="<?php $echo= $storeManager->getStore()->getBaseUrl();$this->getResponse()->setBody($echo);?>/mofluidapi2?service=authorizepayment" onsubmit="return validateForm()" method="POST" novalidate autocomplete="on">

						<label>Card number:</label>
						<input type="text" class="cc-number" pattern="\d*" x-autocompletetype="cc-number" placeholder="•••• •••• •••• ••••" name ="card" required />

						<label>Expires:</label>
						<input type="text" class="cc-exp" pattern="\d*" x-autocompletetype="cc-exp" placeholder="MM / YY" required maxlength="7" name = "date" />

						<label>CVC:</label>
						<input type="text" class="cc-cvc" pattern="\d*" x-autocompletetype="cc-csc" placeholder="123" name ="cvv" required maxlength="4" autocomplete="off" />

						<label class="validation"></label>

						<button type="submit">Submit</button>
						 <input id="customer_id" name="customer_id" type="hidden" value="<?php $echo= $datadecode->userid;$this->getResponse()->setBody($echo); ?>" />
						 <input id="amount" name="amount" type="hidden" value="<?php $echo= $datadecode->amount; $this->getResponse()->setBody($echo);?>" />
						 <input id="orderId" name="orderId" type="hidden" value="<?php $echo= $datadecode->orderid; $this->getResponse()->setBody($echo);?>" />                                                <input id="plateform" name="plateform" type="hidden" value="<?php $echo= $datadecode->plateform;$this->getResponse()->setBody($echo); ?>" />
					  </form>

					</div>



	</body>
</html>

<?php
	}
    /***************************************Authorize.net*******************/
}
