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
        \Mofluid\Mofluidapi2\Model\Index $Mauthentication,
        Data $helper
    ){
        $this->resultPageFactory = $resultPageFactory;
        $this->mproduct = $Mproduct;
        $this->helper = $helper;
        $this->_mauthentication =$Mauthentication;
        parent::__construct($context);
    }
    public function ws_validateAuthenticate()
    {
		$request = $this->_objectManager->get('Magento\Framework\App\RequestInterface');
		$authappid = $request->getHeader('authappid');
		$token = $request->getHeader('token');
		$secretkey = $request->getHeader('secretkey');
		if(empty($authappid) || $authappid == null)
			return false;
		if(empty($token) || $token == null)
			return false;
		if(empty($secretkey) || $secretkey == null)
			return false;

		$mofluid_authentication = $this->_mauthentication->getCollection()->addFieldToFilter('appid', $authappid)->addFieldToFilter('token', $token)->addFieldToFilter('secretkey', $secretkey)->getData();
		if(count($mofluid_authentication) > 0){
			return true;
		}else{
			return false;
		}
		return false;
	}

	/**
     * Check if token is expired.
     *
     * @param Token $token
     * @return bool
     */
    private function isTokenExpired(Token $token): bool
    {
        return false;
    }


    /**
     * Finds the bearer token and looks up the value.
     *
     * @return void
     */
    protected function processRequest()
    {
        if ($this->isRequestProcessed) {
            return;
        }

        $authorizationHeaderValue = $this->request->getHeader('Authorization');
        if (!$authorizationHeaderValue) {
            $this->isRequestProcessed = true;
            return;
        }

        $headerPieces = explode(" ", $authorizationHeaderValue);
        if (count($headerPieces) !== 2) {
            $this->isRequestProcessed = true;
            return;
        }

        $tokenType = strtolower($headerPieces[0]);
        if ($tokenType !== 'bearer') {
            $this->isRequestProcessed = true;
            return;
        }

        $bearerToken = $headerPieces[1];
        $token = $this->tokenFactory->create()->loadByToken($bearerToken);

        if (!$token->getId() || $token->getRevoked() || $this->isTokenExpired($token)) {
            $this->isRequestProcessed = true;

            return;
        }

        $this->isRequestProcessed = true;
        return $this->isRequestProcessed;
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
    	$processRequest = $this->processRequest();

		if(!$processRequest){            

			$maskedException =  new \Magento\Framework\Webapi\Exception(
				new Phrase("The consumer isn't authorized to access"),
				0,
				401
			);

			return $this->response->setException($maskedException);
		}

		$request = $this->_objectManager->get('Magento\Framework\App\RequestInterface');
		$service = $request->getParam("service");

		// get authenticate token and secret key
		if($service == 'gettoken'){
			$mofluidAuthResponse = array();
			$authappid = $request->getParam("authappid");
			if(empty($authappid) || $authappid == null){
				$echo= json_encode(array("Invalid App id"));
        $this->getResponse()->setBody($echo);
				return;
			}
			$mofluid_authentication = $this->_mauthentication->getCollection()->addFieldToFilter('appid', $authappid)->getData();
			if(count($mofluid_authentication) > 0){
				$data = ['appid' => $mofluid_authentication[0]['appid'], 'token' => $mofluid_authentication[0]['token'], 'secretkey' => $mofluid_authentication[0]['secretkey']];
				$echo= json_encode($data);
        $this->getResponse()->setBody($echo);
				return;
			}else{
				$token = openssl_random_pseudo_bytes(16);
				$token = bin2hex($token);
				$secretKey = md5(uniqid($authappid, TRUE));
				$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
				$model = $objectManager->create('Mofluid\Mofluidapi2\Model\Index');
				$data = ['appid' => $authappid, 'token' => $token, 'secretkey' => $secretKey];
				$model->setData($data);
				$model->save();
				$echo= json_encode($data);
        $this->getResponse()->setBody($echo);
				return;
			}
		}

		// get authenticate token and secret key end here

		 /*if(!$this->ws_validateAuthenticate()){
			 $echo= json_encode(array('unauthorized'));
			 return;
		 }*/

		$store = $request->getParam("store");
		if ($store == null || $store == '') {
			$store = 1;
		}

		$categoryid = $request->getParam("categoryid");
		$filterdataencode =   $request->getParam("filterdata");
        $filterdata=base64_decode($filterdataencode);
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
                $scarrier                 =$request->getParam("shipcarrier");
		$transid                  = $request->getParam("transactionid");
		$product                  = $request->getParam("product");
		$shippCharge              = $request->getParam("shippcharge");
		$search_data              = $request->getParam("search_data");
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
		//$postdata                 = $this->getRequest()->getParam();
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
		$customer_id              = $request->getParam("customer_id");
		$apiKey                   = $request->getParam("apiKey");
		$token_id                 = $request->getParam("token_id");
		$card_id                  = $request->getParam("card_id");
		$mofluid_Custid           = $request->getParam("mofluid_Custid");
		$discription              = $request->getParam("discription");
		$name1                    = $request->getParam("name");
		$qty					  = $request->getParam("qty");
	    $currency				  = $request->getParam("currency");
		$payment_data		      =  $request->getParam("payment_data");
         $address_id=$request->getParam("addressid");
         $item_id=$request->getParam("itemid");
         $address_data=base64_decode($request->getParam("address_data"));
         $item_data=base64_decode($request->getParam("item_data"));
	     $name=base64_decode($name1);
	     $sku=$request->getParam("sku");
		//$currency='USD';

		if ($service == "sidecategory") {
			$res = $this->helper->ws_sidecategory($store, $service);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "initial") {
			$res = $this->helper->fetchInitialData($store, $service, $currency);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "getallCMSPages") {
			$res = $this->helper->getallCMSPages($store, $pageId);
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
                      //  $obj=new Magento2();
                       // $res=$obj->getProductDetails($productid);
                        // $echo= $res;
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
			$res = $this->helper->ws_getNewProducts($currency, $service, $store, $curr_page, $page_size, $sortType, $sortOrder);
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
			$res = $this->helper->ws_createuser($store, $service, $firstname, $lastname , $email, $password);
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
		}elseif ($service == "register_push") {
			$res = $this->helper->mofluid_register_push($store, $deviceid, $pushtoken, $platform, $appname, $description);
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
                          $obj=new Magento2();
                          $res=$obj->getOrdersList($custid,$curr_page,$page_size);
                          $echo= $res;
                          $this->getResponse()->setBody($echo);
		 	//$res = $this->helper->ws_myOrder($custid, $curr_page, $page_size, $store, $currency);
			//$echo= json_encode($res);
		}elseif ($service == "preparequote") {
			$res = $this->helper->prepareQuote($custid, $products, $store, $address, $smethod, $couponCode, $currency, $is_create_quote, $find_shipping, $theme);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "placeorder") {
                        $obj=new Magento2();
                        $res=$obj->placeOrder($custid,$pmethod,$smethod,$scarrier);
		//	$res = $this->helper->placeorder($custid, $products, $store, $address, $couponCode, $is_create_quote, $transid, $pmethod, $smethod, $currency, $messages, $theme);
			$echo= $res;
      $this->getResponse()->setBody($echo);
		}elseif($service == "checkout_new"){
                   $obj=new Magento2();
                   $res=$obj->setShippingAndBillingInfoToCart($custid,$smethod,$scarrier);
                   $echo= $res;
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
		}else if ($service == "filter") {
			$res = $this->helper->ws_filter($store, $service, $categoryid, $curr_page, $page_size, $sortType, $sortOrder, $currency,$filterdata);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}elseif ($service == "getcategoryfilter") {
          $res = $this->helper->ws_getcategoryfilter($store,$categoryid);
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        }else if ($service == "getProductStock1") {
			$res = $this->helper->getProductStock1($store,$service,$product_id);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
		}else if ($service == "retrieveCustomerStripe") {
          $res = $this->helper->ws_retrieveCustomerStripe($customer_id);
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        } else if ($service == "createCardStripe") {
          $res = $this->helper->ws_createCardStripe($customer_id,$token_id,$card_id);
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        }else if ($service == "customerUpdateStripe") {
          $res = $this->helper->ws_customerUpdateStripe($customer_id, $discription);
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        }else if ($service == "stripecustomercreate") {  // die('hii');
           $res = $this->helper->stripecustomercreate($mofluid_Custid,$token_id,$email,$name);
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        } else if ($service == "chargeStripe") {
          $res = $this->helper->chargeStripe($customer_id,$price,$currency,$card_id);
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        } else if ($service == "stripeData") {
          $res = $this->helper->stripeData();
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        }else if ($service == "addCartItem") {
          $res = $this->helper->ws_addCartItem($store,$service,$custid,$product_id,$qty);
          $echo= json_encode($res);
          $this->getResponse()->setBody($echo);
        }else if ($service == "authorizecheckout") {
			$res = $this->helper->authorizecheckout($payment_data);
			$echo= json_encode($res);
      $this->getResponse()->setBody($echo);
	}elseif($service=="remove_address"){
         $obj=new Magento2();
         $res = $obj->deleteAddress($address_id);
         $echo=  $res;
         $this->getResponse()->setBody($echo);
       } elseif($service=="address_list"){
           $obj=new Magento2();
         $res = $obj->getAddressList($custid);
         $echo=  $res;
         $this->getResponse()->setBody($echo);
        }elseif($service=="add_new_address"){
               $obj=new Magento2();
               $res = $obj->postAddress($custid,$address_data);
               $echo= $res;
               $this->getResponse()->setBody($echo);
       }elseif($service=="update_address"){
              $obj=new Magento2();
              $res = $obj->updateAddress($address_id,$address_data);
               $echo= $res;
               $this->getResponse()->setBody($echo);
       }elseif($service=="get_address"){
          $obj=new Magento2();
          $res=$obj->getAddress($address_id);
          $echo= $res;
          $this->getResponse()->setBody($echo);
      }elseif($service=="get_billing_address"){
		  $obj=new Magento2();
          $res=$obj->getBillingAddress($custid);
          $echo= $res;
          $this->getResponse()->setBody($echo);
	  }elseif($service=="get_shipping_address"){
		$obj=new Magento2();
          $res=$obj->getShippingAddress($custid);
          $echo= $res;
          $this->getResponse()->setBody($echo);
	  }elseif($service=="set_shipping_address"){
		  $obj=new Magento2();
          $res=$obj->setShippingAddress($custid,$address_id);
          $echo= $res;
          $this->getResponse()->setBody($echo);
	  }elseif($service=="set_billing_address"){
		  $obj=new Magento2();
          $res=$obj->setBillingAddress($custid,$address_id);
          $echo= $res;
          $this->getResponse()->setBody($echo);
	  }elseif($service=="get_cart_items"){
		$obj=new Magento2();
          $res=$obj->getCartItems($custid);
          $echo= $res;
          $this->getResponse()->setBody($echo);
	 }elseif($service=="remove_cart_item"){
		 $obj=new Magento2();
          $res=$obj->removeItemFromCart($custid,$item_id);
          $echo= $res;
          $this->getResponse()->setBody($echo);
	 }elseif($service=="add_cart_item"){
		 $obj=new Magento2();
          $res=$obj->addItemsTocart($custid,$item_data);
          $echo= $res;
          $this->getResponse()->setBody($echo);
	 }elseif($service=="get_shipping_method"){
		 $obj=new Magento2();
		  $res=$obj->getShippingMethodUtil($custid,$address_id);
		  $echo=  $res;
      $this->getResponse()->setBody($echo);
	  }elseif($service=="get_image"){
               $obj=new Magento2();
               $res=$obj->getImage($sku);
               $echo= $res;
               $this->getResponse()->setBody($echo);
      }elseif($service=="getWishlist"){
		  $obj=new Magento2();
          $res=$obj->getWishlistItems($custid);
               $echo= $res;
               $this->getResponse()->setBody($echo);
	  }elseif($service=="removefromWishlist"){
		  $obj=new Magento2();
          $res=$obj->removeItemfromWishlist($custid,$item_id);
               $echo= $res;
               $this->getResponse()->setBody($echo);
	  }elseif($service=="addtoWishlist"){
		  $obj=new Magento2();
          $res=$obj->addItemtoWishlist($custid,$pid);
               $echo= $res;
               $this->getResponse()->setBody($echo);
	  }elseif($service=="get_products"){
          $obj=new Magento2();
               $res=$obj->getProducts();
               $echo= $res;
               $this->getResponse()->setBody($echo);

          }elseif($service=="get_new_products"){
           $obj=new Magento2();
          $res=$obj->getNewProducts(20,0);
               $echo= $res;
              $this->getResponse()->setBody($echo);

          }elseif($service=="get_category"){
           $obj=new Magento2();
          $res=$obj->getCategories();
               $echo= $res;
              $this->getResponse()->setBody($echo);

          }elseif($service=="get_product_detail"){
           $obj=new Magento2();
          $res=$obj->getProductDetailsAll($sku);
               $echo= $res;
              $this->getResponse()->setBody($echo);

          }elseif($service=="get_category_product"){
           $obj=new Magento2();
          $res=$obj->getProductsOfCategory($categoryid,$sortType,$sortOrder,$curr_page,$page_size,$filterdata);
               $echo= $res;
              $this->getResponse()->setBody($echo);

          }elseif($service=="get_featured_product"){
           $obj=new Magento2();
          $res=$obj->getFeaturedProducts();
               $echo= $res;
              $this->getResponse()->setBody($echo);
          }elseif($service=="get_filter"){
           $obj=new Magento2();
          $res=$obj->getFilter();
               $echo= $res;
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
