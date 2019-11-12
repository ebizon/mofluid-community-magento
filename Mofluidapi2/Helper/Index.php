<?php
namespace Mofluid\Mofluidapi2\Controller\Index;
use \Mofluid\Mofluidapi2\Helper\Data;
use \Mofluid\Mofluidapi2\Helper\Magento;
use \Mofluid\Mofluidapi2\Helper\Magento1;
use \Mofluid\Mofluidapi2\Helper\Magento2;
class Index extends \Magento\Framework\App\Action\Action {
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;
    /** @var  \Mofluid\Mofluidapi2\Model\Catalog\Product */
    protected $Mproduct;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Mofluid\Mofluidapi2\Model\Catalog\Product $Mproduct
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mofluid\Mofluidapi2\Model\Catalog\Product $Mproduct,
        Data $helper
    ){
        $this->resultPageFactory = $resultPageFactory;
        $this->mproduct = $Mproduct;
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
		$request = $this->_objectManager->get('Magento\Framework\App\RequestInterface');
		$store = $request->getParam("store");
		if ($store == null || $store == '') {
			$store = 1;
		}
		$service = $request->getParam("service");
		$categoryid = $request->getParam("categoryid");
		$pageId                   = $request->getParam("pageId");
		$service                  = $request->getParam("service");
		$categoryid               = $request->getParam("categoryid");
		$firstname                = $request->getParam("firstname");
		$lastname                 = $request->getParam("lastname");
		$email                    = $request->getParam("email");
		$password                 = $request->getParam("password");
		$oldpassword              = $request->getParam("oldpassword");
		$newpassword              = $request->getParam("newpassword");
		$productid                = $request->getParam("productid");
		$custid                   = $request->getParam("customerid");
		$billAdd                  = $request->getParam("billaddress");
		$shippAdd                 = $request->getParam("shippaddress");
		$pmethod                  = $request->getParam("paymentmethod");
		$smethod                  = $request->getParam("shipmethod");
		$transid                  = $request->getParam("transactionid");
		$product                  = $request->getParam("product");
		$shippCharge              = $request->getParam("shippcharge");
		$searchdata               = $request->getParam("search_data");
		$search_data              = base64_decode($searchdata);
		$username                 = $request->getParam("username");
		// Get Requested Data for Push Notification Request
		$deviceid                 = $request->getParam("deviceid");
		$pushtoken                = $request->getParam("pushtoken");
		$platform                 = $request->getParam("platform");
		$appname                  = $request->getParam("appname");
		$description              = $request->getParam("description");
		$profile                  = $request->getParam("profile");
		$paymentgateway           = $request->getParam("paymentgateway");
		$couponCode               = $request->getParam("couponCode");
		$orderid                  = $request->getParam("orderid");
		$pid                      = $request->getParam("pid");
		$products                 = $request->getParam("products");
		$address                  = $request->getParam("address");
		$country                  = $request->getParam("country");
		$grand_amount             = $request->getParam("grandamount");
		$order_sub_amount         = $request->getParam("subtotal_amount");
		$discount_amount          = $request->getParam("discountamount");
		$mofluidpayaction         = $request->getParam("mofluidpayaction");
		$postdata                 = $this->getRequest()->getParam();
		$mofluid_payment_mode     = $request->getParam("mofluid_payment_mode");
		$product_id               = $request->getParam("product_id");
		$gift_message             = $request->getParam("message");
		$mofluid_paymentdata      = $request->getParam("mofluid_paymentdata");
		$mofluid_ebs_pgdata       = $request->getParam("DR");
		$curr_page                = $request->getParam("currentpage");
		$page_size                = $request->getParam("pagesize");
		$sortType                 = $request->getParam("sorttype");
		$sortOrder                = $request->getParam("sortorder");
		$saveaction               = $request->getParam("saveaction");
		$mofluid_orderid_unsecure = $request->getParam("mofluid_order_id");
		$currency                 = $request->getParam("currency");
		$price                    = $request->getParam("price");
		$from                     = $request->getParam("from");
		$to                       = $request->getParam("to");
		$is_create_quote          = $request->getParam("is_create_quote");
		$find_shipping            = $request->getParam("find_shipping");
		$messages                 = $request->getParam("messages");
		$theme                    = $request->getParam("theme");
		$timeslot                 = $request->getParam("timeslot");
		$billshipflag             = $request->getParam("shipbillchoice");
		$currency='USD';
		$stripeToken		  = $request->getParam("stripeToken");
		if ($service == "sidecategory") {
                 //       die;
			$res = $this->helper->ws_sidecategory($store, $service);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "initial") {
			$res = $this->helper->fetchInitialData($store, $service, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "category") {
			$res = $this->helper->ws_category($store, $service);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		} elseif ($service == "subcategory") {
			$res = $this->helper->ws_subcategory($store, $service, $categoryid);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "products") {
			$res = $this->helper->ws_products($store, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "productdetaildescription") {
			$res = $this->helper->ws_productdetailDescription($store, $service, $productid, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "get_configurable_product_details_description") {
			$res = $this->helper->get_configurable_products_description($productid, $currency,$store);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "getFeaturedProducts") {
			$res = $this->helper->ws_getFeaturedProducts($currency, $service, $store);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "get_configurable_product_details_image") {
			$res = $this->helper->get_configurable_products_image($productid, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "getNewProducts") {
			$res = $this->helper->ws_getNewProducts($currency, $service, $store);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "convert_currency") {
			$res = $this->helper->convert_currency($price, $from, $to);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "rootcategory") {
            $res = $this->helper->rootCategoryData($store, $service);
            $echo= json_encode($res);
            $this->getResponse()->setBody($echo);
		}elseif ($service == "createuser") {
			$res = $this->helper->ws_createuser($store, $service, $firstname, $lastname, $email, $password);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "myprofile") {
			$res = $this->helper->ws_myProfile($custid);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "mofluidUpdateProfile") {
			$res = $this->helper->mofluidUpdateProfile($store, $service, $custid, $billAdd, $shippAdd, $profile, $billshipflag);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "changeprofilepassword") {
			$res = $this->helper->ws_changeProfilePassword($custid, $username, $oldpassword, $newpassword, $store);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "mofluidappcountry") {
			$res = $this->helper->ws_mofluidappcountry($store);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "mofluidappstates") {
			$res = $this->helper->ws_mofluidappstates($store, $country);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "productdetail") {
			$res = $this->helper->ws_productdetail($store, $service, $productid, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "getallCMSPages") {
			$res = $this->helper->getallCMSPages($store, $pageId);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "productinfo") {
			try {
				$res = $this->helper->ws_productinfo($store, $productid, $currency);
				$echo= json_encode($res);
        $this->getResponse()->setBody($echo);
			}
			catch (\Exception $ex) {
				$echo= 'Error' . $ex->getMessage();
        $this->getResponse()->setBody($echo);
			}
		}elseif ($service == "productdetailimage") {
			$res = $this->helper->ws_productdetailImage($store, $service, $productid, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "storedetails") {
			$res = $this->helper->ws_storedetails($store, $service, $theme, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "verifylogin") {
			$res = $this->helper->ws_verifyLogin($store, $service, $username, $password);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "loginwithsocial") {
			$res = $this->helper->ws_loginwithsocial($store, $username, $firstname, $lastname);
			$echo= json_encode($res) ;
      $this->getResponse()->setBody($echo);
		}elseif ($service == "forgotPassword") {
			$res = $this->helper->ws_forgotPassword($email);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
        }elseif ($service == "search") {
			$res = $this->helper->ws_search($store, $service, $search_data, $curr_page, $page_size, $sortType, $sortOrder, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "getpaymentmethod") {
			$res = $this->helper->ws_getpaymentmethod();
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "productQuantity") {
			$res = $this->helper->ws_productQuantity($product);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "checkout") {
			$res = $this->helper->ws_checkout($store, $service, $theme, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "myorders") {
			$res = $this->helper->ws_myOrder($custid, $curr_page, $page_size, $store, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "preparequote") {
			$res = $this->helper->prepareQuote($custid, $products, $store, $address, $smethod, $couponCode, $currency, $is_create_quote, $find_shipping, $theme);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "placeorder") {
			$res = $this->helper->placeorder($custid, $products, $store, $address, $couponCode, $is_create_quote, $transid, $pmethod, $smethod, $currency, $messages, $theme);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "validate_currency") {
			$res = $this->helper->ws_validatecurrency($store, $service, $currency, $paymentgateway);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "setaddress") {
			$res = $this->helper->ws_setaddress($store, $service, $custid, $address, $email, $saveaction);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "mofluid_reorder") {
			$res = $this->helper->ws_mofluid_reorder($store, $service, $pid, $orderid, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif($service=="address_list"){
		 $obj=MagentoFactory::getMagento2();
          $res = $obj->getAddressList($customer_id);
          $echo=  json_encode($res);
          $this->getResponse()->setBody($echo);
        }elseif($service=="add_new_address"){
		  $obj=MagentoFactory::getMagento2();

        }elseif($service=="update_address"){
		$obj=MagentoFactory::getMagento2();
        }elseif($service=="get_address"){
		 $obj=MagentoFactory::getMagento2();
       }elseif($service=="charge_stripe"){
       		$res = $this->helper->chargeStripe($stripeToken);
                $echo= json_encode($res);
      		$this->getResponse()->setBody($echo);
        }
		else {
			$this->ws_service404($service);
		}
    }

    /*=====================      Handle When Store Not Found      =========================*/
    public function ws_store404($store)
    {
        $echo= 'Store 404 Error :  Store ' . $store . ' is not found on your host ';
        $this->getResponse()->setBody($echo);
    }
    /*=====================      Handle When Service Not Found      =========================*/
    public function ws_service404($service)
    {
        if ($service == "" || $service == null)
            $echo= 'Service 404 Error :  No Such Web Service found under Mofluid APIs at your domain';
        else
            $echo= 'Service 404 Error : ' . $service . ' Web Service is not found under Mofluid APIs at your domain';
            $this->getResponse()->setBody($echo);
    }
}
