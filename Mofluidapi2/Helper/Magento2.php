<?php
namespace Mofluid\Mofluidapi2\Helper;
use Mofluid\Mofluidapi2\Helper\Magento;
class Magento2 extends Magento{

 public function __construct(){
    parent::__construct();
    // Do stuff specific for Bar
  }
private function isValidResponse($response){
if(strpos($response,"message:") !== false)
return FALSE;
return TRUE;
}
private function getDummyResponse(){
$res["status"]="not exist";
return json_encode($res);
}
//customer related api
public function getCustomer($customer_id){
$response=parent::getResponse("/V1/customers/".$customer_id);
return $response;
	}
//address related api
public function getAddressList($customer_id){
$response=$this->getCustomer($customer_id);
$obj=json_decode($response,true);
return json_encode($obj["addresses"]);
	}
public function getAddress($address_id){
   $response=parent::getResponse("/V1/customers/addresses/".$address_id);
    return $response;
	}
public function getBillingAddress($customer_id){
	$response=parent::getResponse("/V1/customers/".$customer_id."/billingAddress");
        $response_obj=json_decode($response,true);
        if(!array_key_exists("id", $response_obj))
          return $this->getDummyResponse();
    return $response;
	}
public function getShippingAddress($customer_id){
	$response=parent::getResponse("/V1/customers/".$customer_id."/shippingAddress");
         $response_obj=json_decode($response,true);
         if(!array_key_exists("id", $response_obj))
          return $this->getDummyResponse();
    return $response;
	}
public function deleteAddress($address_id){
	$response=parent::delete("/V1/addresses/$address_id");
        return $response;
	}
public function postAddress($customer_id,$address_data){
   /*$address_data='{
  "customer_id": 87,
  "region": {
    "region_code": "AS",
    "region": "American Samoa",
    "region_id": 3
  },
  "region_id": 3,
  "country_id": "US",
  "street": [
    "noida sector 62",
    "tower A logix cyber park"
  ],
  "company": "ebizon info pvt ltd",
  "telephone": "9911997403",
  "postcode": "201301",
  "city": "noida sector 62",
  "firstname": "saddam",
  "lastname": "hussain",
  "default_shipping": false,
  "default_billing": false
}';*/
   $customer=$this->getCustomer($customer_id);
   $obj=json_decode($customer,true);
   $obj["addresses"][count($obj["addresses"])]=json_decode($address_data);
   $obj1["customer"]=$obj;
   $obj1["password"]="123456";
   $response=parent::update("/V1/customers/$customer_id",json_encode($obj1));
   return $response;
	}
public function updateAddress($address_id,$address_data){
 $customer=$this->getCustomer($customer_id);
   $obj=json_decode($customer,true);
  $addresses= $obj["addresses"];
  for($i=0;$i<count($addresses);$i++){
   $address=$addresses[$i];
    if($address["id"]==$address_id)
      { $addresses[$i]=json_decode($address_data);
        break;
      }
}
$obj["addresses"]=$addresses;

$obj1["customer"]=$obj;
$obj1["password"]="123456";
$response=parent::update("/V1/customers/$customer_id",json_encode($obj1));
   return $response;
	}
public function setBillingAddress($customer_id,$address_id){
      $customer=$this->getCustomer($customer_id);
   $obj=json_decode($customer,true);
  $addresses= $obj["addresses"];
  for($i=0;$i<count($addresses);$i++){
   $address=$addresses[$i];
  $addresses[$i]["default_billing"]=false;
    if($address["id"]==$address_id)
      { $addresses[$i]["default_billing"]=true;
        break;
      }
}
$obj["addresses"]=$addresses;
$obj1["customer"]=$obj;
$obj1["password"]="123456";
$response=parent::update("/V1/customers/$customer_id",json_encode($obj1));
return $response;

	}
public function setShippingAddress($customer_id,$address_id){
      $customer=$this->getCustomer($customer_id);
   $obj=json_decode($customer,true);
  $addresses= $obj["addresses"];
  for($i=0;$i<count($addresses);$i++){
   $address=$addresses[$i];
  $addresses[$i]["default_shipping"]=false;
    if($address["id"]==$address_id)
      { $addresses[$i]["default_shipping"]=true;
        break;
      }
}
$obj["addresses"]=$addresses;
$obj1["customer"]=$obj;
$obj1["password"]="saddam123";
$response=parent::update("/V1/customers/$customer_id",json_encode($obj1));
return $response;
	}
//cart api for mofluid

public function getCartItems($customer_id){
	    $res1=parent::post("/V1/customers/".$customer_id."/carts");
        $cart_id= json_decode($res1);
        $response=parent::getResponse("/V1/carts/$cart_id/items");
	$obj=json_decode($response,true);
    for($i=0;$i<count($obj);$i++){
      $sku=$obj[$i]["sku"];
      $image=$this->getImage($sku);
     $product=$this->getProduct($sku);
    $product_obj=json_decode($product,true);
      $obj[$i]["stock"]=$product_obj["extension_attributes"]["stock_item"]["qty"];
      $obj[$i]["is_in_stock"]=$product_obj["extension_attributes"]["stock_item"]["is_in_stock"];
     $obj[$i]["image"]=json_decode($image,true);
     }
	return json_encode($obj);
	}
public function addItemsTocart($customer_id,$item){
$res1=parent::post("/V1/customers/".$customer_id."/carts");
/*$item='{
    "sku": "24-MB03",
    "qty": 1,
    "quoteId": "1055",
    "product_type":"simple"
}';*/
$obj["cartItem"]=json_decode($item,true);
 $cart_id= json_decode($res1);
$obj["cartItem"]["quoteId"]=$cart_id;
//echo '<pre>'.print_r($item);
//echo '<pre>'.print_r($obj);
$cart_items=$this->getCartItems($customer_id);
$cart_items_obj=json_decode($cart_items,true);
for($i=0;$i<count($cart_items_obj);$i++){
$item=$cart_items_obj[$i];
if($item["sku"]==$obj["cartItem"]["sku"]){
$this->removeItemFromCart($customer_id,$item["item_id"]);
break;
}
}
$response=parent::post("/V1/carts/$cart_id/items",json_encode($obj));
	return $response;
}
public function removeItemFromCart($customer_id,$itemId){
$res1=parent::post("/V1/customers/".$customer_id."/carts");
        $cart_id= json_decode($res1);
        $response=parent::delete("/V1/carts/$cart_id/items/$itemId");
	return $response;
}
//shipping method mofluid api
public function setBillingAddressToCart($customer_id,$address_id=null){
$res1=parent::post("/V1/customers/".$customer_id."/carts");
$cart_id= json_decode($res1);
if($address_id==null)
$address=$this->getBillingAddress($customer_id);
else $address=$this->getAddress($address_id);
$address_obj=json_decode($address,true);
$address_obj["regionCode"]=$address_obj["region"]["region_code"];
$address_obj["regionId"]=$address_obj["region_id"];
$address_obj["countryId"]=$address_obj["country_id"];
$address_obj["customerId"]=$address_obj["customer_id"];
//$shipping_address_obj["customerAddressId"]=$shipping_address_obj["id"];
$address_obj["region"]=$address_obj["region"]["region"];
unset($address_obj["default_billing"]);
unset($address_obj["default_shipping"]);
//$obj["address"]=$address_obj;
$obj["address"]["region"]=$address_obj["region"];
$obj["address"]["region_id"]=$address_obj["region_id"];
$obj["address"]["region_code"]=$address_obj["regionCode"];
$obj["address"]["country_id"]=$address_obj["country_id"];
$obj["address"]["street"]=$address_obj["street"];
$obj["address"]["telephone"]=$address_obj["telephone"];
$obj["address"]["postcode"]=$address_obj["postcode"];
$obj["address"]["city"]=$address_obj["city"];
$obj["address"]["firstname"]=$address_obj["firstname"];
$obj["address"]["lastname"]=$address_obj["lastname"];
$obj["address"]["sameAsBilling"]=1;
$obj["address"]["saveInAddressBook"]=1;
//$obj["address"]["email"]=$address_obj["email"];
$obj["useForShipping"]=true;
$res=parent::post("/V1/carts/$cart_id/billing-address",json_encode($obj));
return $res;
}
public function estimateShippingMethods($customer_id,$address_id=null){
if($address_id==null){
$address=$this->getShippingAddress($customer_id);
$address_obj=json_decode($address,true);
$address_id=$address_obj["id"];
}
else{
$address=$this->getAddress($address_id);
$address_obj=json_decode($address,true);
$address_id=$address_obj["id"];
}
$res1=parent::post("/V1/customers/$customer_id/carts");
$cart_id= json_decode($res1);
$body["addressId"]=$address_id;
$body["cartId"]=(int)$cart_id;
$res=parent::post("/V1/carts/$cart_id/estimate-shipping-methods-by-address-id",json_encode($body));
return $res;
}
public function getShippingMethod($customer_id,$address_id=null){
$res1=parent::post("/V1/customers/$customer_id/carts");
$cart_id= json_decode($res1);
if($address_id!=null)
$this->setShippingAddress($customer_id,$address_id);
$echo= $this->setShippingToCart($customer_id);
$this->getResponse()->setBody($echo);
$response=parent::getResponse("/V1/carts/$cart_id/shipping-methods");
return $response;

}
public function getShippingMethodUtil($customer_id,$address_id=null){
$res=$this->estimateShippingMethods($customer_id,$address_id);
return $res;
}
public function setShippingAndBillingInfoToCart($customer_id,$shipping_method_code,$shipping_carrier_code){
$customer=$this->getCustomer($customer_id);
$customer_obj=json_decode($customer,true);
$email=$customer_obj["email"];
$shipping_address=$this->getShippingAddress($customer_id);
$shipping_address_obj=json_decode($shipping_address,true);
$billing_address=$this->getBillingAddress($customer_id);
$billing_address_obj=json_decode($billing_address,true);
$obj["addressInformation"]["shipping_address"]["region"]=$shipping_address_obj["region"]["region"];
$obj["addressInformation"]["shipping_address"]["region_id"]=$shipping_address_obj["region_id"];
$obj["addressInformation"]["shipping_address"]["region_code"]=$shipping_address_obj["region"]["region_code"];
$obj["addressInformation"]["shipping_address"]["country_id"]=$shipping_address_obj["country_id"];
$obj["addressInformation"]["shipping_address"]["street"]=$shipping_address_obj["street"];
$obj["addressInformation"]["shipping_address"]["postcode"]=$shipping_address_obj["postcode"];
$obj["addressInformation"]["shipping_address"]["city"]=$shipping_address_obj["city"];
$obj["addressInformation"]["shipping_address"]["firstname"]=$shipping_address_obj["firstname"];
$obj["addressInformation"]["shipping_address"]["lastname"]=$shipping_address_obj["lastname"];
$obj["addressInformation"]["shipping_address"]["email"]=$email;
$obj["addressInformation"]["shipping_address"]["telephone"]=$shipping_address_obj["telephone"];

$obj["addressInformation"]["billing_address"]["region"]=$billing_address_obj["region"]["region"];
$obj["addressInformation"]["billing_address"]["region_id"]=$billing_address_obj["region_id"];
$obj["addressInformation"]["billing_address"]["region_code"]=$billing_address_obj["region"]["region_code"];
$obj["addressInformation"]["billing_address"]["country_id"]=$billing_address_obj["country_id"];
$obj["addressInformation"]["billing_address"]["street"]=$billing_address_obj["street"];
$obj["addressInformation"]["billing_address"]["postcode"]=$billing_address_obj["postcode"];
$obj["addressInformation"]["billing_address"]["city"]=$billing_address_obj["city"];
$obj["addressInformation"]["billing_address"]["firstname"]=$billing_address_obj["firstname"];
$obj["addressInformation"]["billing_address"]["lastname"]=$billing_address_obj["lastname"];
$obj["addressInformation"]["billing_address"]["email"]=$email;
$obj["addressInformation"]["billing_address"]["telephone"]=$billing_address_obj["telephone"];
$obj["addressInformation"]["shipping_carrier_code"]=$shipping_carrier_code;
$obj["addressInformation"]["shipping_method_code"]=$shipping_method_code;
$res1=parent::post("/V1/customers/$customer_id/carts");
$cart_id= json_decode($res1);
$info=parent::post("/V1/carts/$cart_id/shipping-information",json_encode($obj));
$res = json_encode($decodedinfo);
return  $res;
}
public function placeOrder($customer_id,$payment_method,$shipping_method_code=null,$shipping_carrier_code=null){
if($shipping_method_code!=null && $shipping_carrier_code!=null){
$res=$this->setShippingAndBillingInfoToCart($customer_id,$shipping_method_code,$shipping_carrier_code);
}
$res1=parent::post("/V1/customers/$customer_id/carts");
$cart_id= json_decode($res1);
$body["paymentMethod"]["method"]=$payment_method;
$body["cartId"]=$cart_id;
$res=parent::update("/V1/carts/$cart_id/order",json_encode($body));
$order_id=json_decode($res);
$resp["orderId"]=$order_id;
return json_encode($resp);
}

public function getImage($sku){
$res=parent::getResponse("/V1/products/$sku/media");
$response=array();
$protocol=(isset($_SERVER['HTTPS']) ? "https" : "http");
$base_url = $protocol."://".$_SERVER['HTTP_HOST'];
$res_obj=json_decode($res,true);
for($i=0;$i<count($res_obj);$i++){
$types=$res_obj[$i]["types"];
for($j=0;$j<count($types);$j++){
if($types[$j]=="thumbnail")
  $response["thumbnail"]=$base_url."/pub/media/catalog/product".$res_obj[$i]["file"];
if($types[$j]=="small_image")
  $response["small_image"]=$base_url."/pub/media/catalog/product".$res_obj[$i]["file"];

}
}

return json_encode($response);
}


public function getProductDetails($product_id){
$param="searchCriteria[filterGroups][0][filters][0][field]=entity_id&searchCriteria[filterGroups][0][filters][0][condition_type]=eq&searchCriteria[filterGroups][0][filters][0][value]=$product_id";
$res=parent::get("/V1/products",null,$param);
return $res;
}
public function getOrdersList($customer_id,$cur_page=null,$page_size=null){
$customer=$this->getCustomer($customer_id);
$customer_obj=json_decode($customer,true);
$param2=null;
$email=$customer_obj["email"];
$param1="searchCriteria[filter_groups][0][filters][0][field]=customer_email&searchCriteria[filter_groups][0][filters][0][value]=$email&searchCriteria[sortOrders][0][field]=increment_id&searchCriteria[sortOrders][0][direction]=DESC";
if($cur_page!=null && $page_size!=null)
$param2="searchCriteria[pageSize]=$page_size&searchCriteria[currentPage]=$cur_page";
$fparam=$param1."&".$param2;
$res1=parent::get("/V1/orders",null,$fparam);
$res2 = json_decode($res1,true);
		
foreach($res2["items"] as $orderskey=>$ordersvalue)
{
	foreach($res2["items"][$orderskey]["items"] as $itemskey=>$itemsvalue)
	{
		$imagepath = json_decode($this->getImage($res2["items"][$orderskey]["items"][$itemskey]["sku"]),true);
		$thumbnailpath = $imagepath["thumbnail"];
		$res2["items"][$orderskey]["items"][$itemskey]["thumbnail"]=$thumbnailpath;
	}
}
$res1 = json_encode($res2);
return $res1;

	}
public function getWishlistItems($customer_id){
$res=parent::get("/V1/wishlist/items/$customer_id");
return $res;
	}
public function removeItemfromWishlist($customer_id,$itemId){
$res=parent::delete("/V1/wishlist/delete/$customer_id/$itemId");
return $res;
	}
public function addItemtoWishlist($customer_id,$product_id){
$res=parent::post("/V1/wishlist/add/$customer_id/$product_id");
return $res;
	}
public function getNewProducts($page_size,$cur_page){
$param="searchCriteria[page_size]=$page_size&searchCriteria[currentPage]=$cur_page&searchCriteria[sortOrders][0][field]=created_at&searchCriteria[sortOrders][1][direction]=DESC";
$res=parent::get("/V1/products",null,$param);
return $res;
}
public function addProduct(){
for($i=100;$i<10000000;$i++){
$sku="Item-100".$i;
$product=$this->getProduct("24-MB03");
$product_obj=json_decode($product,true);
unset($product_obj["id"]);
unset($product_obj["media_gallery_entries"]);
unset($product_obj["custom_attributes"][8]);
unset($product_obj["extension_attributes"]["stock_item"]["item_id"]);
unset($product_obj["extension_attributes"]["stock_item"]["product_id"]);
unset($product_obj["extension_attributes"]["stock_item"]["stock_id"]);
$product_obj["product"]=$product_obj;
$product_obj["product"]["sku"]=$sku;
$product_obj["product"]["name"]="Product ".$i;
//echo json_encode($product_obj);
$res=parent::post("/V1/products",json_encode($product_obj));
//return $res;
}

}
public function getProductDetailsAll($sku){
$res=parent::get("/V1/products/$sku");
$res_obj=json_decode($res,true);
$product_links_obj=$res_obj["product_links"];
for($i=0;$i<count($product_links_obj);$i++){
$linked_product_sku=$product_links_obj[$i]["linked_product_sku"];
$cur_product=$this->getProduct($linked_product_sku);
$cur_product_obj=json_decode($cur_product,true);
$res_obj["product_links"][$i]["detail"]=$cur_product_obj;
}
return json_encode($res_obj);

}
public function getProduct($sku){
$res=parent::get("/V1/products/$sku");
return $res;

}
public function getProductsOfCategory($categoryId,$sorttype,$sortorder,$currentpage,$pagesize,$filter_data){
if($sorttype==null) $sorttype="name";
if($sortorder==null) $sortorder="ASC";
if($currentpage==null) $currentpage=0;
if($pagesize==null) $pagesize=10;
$filter_param="";
if($filter_data!=null){
$filter_data_obj=json_decode($filter_data,true);
$ind=1;
$from=9999999;
$to=-9999999;
$is_price=FALSE;
for($i=0;$i<count($filter_data_obj);$i++){
$id=$filter_data_obj[$i]["id"];
$code=$filter_data_obj[$i]["code"];
$condition_type="eq";
switch($code){
case "price":
	     $pricestr=$id;
	     $priceStrLen=strlen($pricestr);
	     if($pricestr[0]=='-')
	        $pricestr="0".$pricestr;
	     if($pricestr[$priceStrLen-1]=='-')
	     $pricestr=$pricestr."9999999";
	     $priceValues=explode(",",$pricestr);
	     $priceValues_len=count($priceValues);
	     for($j=0;$j<$priceValues_len;$j++){
			$cur=$priceValues[$j];
			$cur_arr=explode("-",$cur);
			$cur_len=count($cur_arr);
			for($k=0;$k<$cur_len;$k++){
				$cur_val=(int)$cur_arr[$k];
				if($cur_val<$from)
				   $from=$cur_val;
				if($cur_val>$to)
				   $to=$cur_val;
				}
			 }

$is_price=TRUE;
break;
case "cat":
$categoryId=$id;
$is_price=TRUE;
break;
case "category":
$categoryId=$id;
$is_price=TRUE;
break;
default:
$is_price=FALSE;
}
if($is_price==FALSE){
$filter_param.="&searchCriteria[filter_groups][$ind][filters][0][field]=$code&searchCriteria[filter_groups][$ind][filters][0][value]=$id&searchCriteria[filter_groups][$ind][filters][0][condition_type]=$condition_type";
$ind++;
}
}

}
if($from!=9999999){
$filter_param.="&searchCriteria[filter_groups][$ind][filters][0][field]=price&searchCriteria[filter_groups][$ind][filters][0][value]=$from&searchCriteria[filter_groups][$ind][filters][0][condition_type]=from";
$ind++;
}
if($to!=-9999999){
$filter_param.="&searchCriteria[filter_groups][$ind][filters][0][field]=price&searchCriteria[filter_groups][$ind][filters][0][value]=$to&searchCriteria[filter_groups][$ind][filters][0][condition_type]=to";
$ind++;
}
$param="searchCriteria[filter_groups][0][filters][0][field]=category_id&searchCriteria[filter_groups][0][filters][0][value]=$categoryId&searchCriteria[filter_groups][0][filters][0][condition_type]=eq&searchCriteria[current_page]=$currentpage&searchCriteria[page_size]=$pagesize&searchCriteria[sortOrders][0][field]=$sorttype&searchCriteria[sortOrders][0][direction]=$sortorder";
//echo $param.$filter_param;
$res=parent::get("/V1/products",null,$param.$filter_param);
return $res;
}
public function getAllProducts(){
  $param="searchCriteria[sortOrders][0][field]=created_at&searchCriteria[sortOrders][1][direction]=DESC&searchCriteria[current_page]=0&searchCriteria[page_size]=10";
  $res=parent::get("/V1/products",null,$param);
  return $res;
}
public function sortByRating($a, $b)
{
    $a = $a['avg_rating_percent'];
    $b = $b['avg_rating_percent'];
    if ($a == $b) return 0;
    return ($a < $b) ? -1 : 1;
}
public function getFeaturedProducts(){
$products=$this->getAllProducts();
$products_obj=json_decode($products,true);
$product_list=$products_obj["items"];
for($i=0;$i<count($product_list);$i++){
  $id=$product_list[$i]["id"];
  $rating=$this->getRating($id);
  $raing_obj=json_decode($rating,true);
  $product_list[$i]["avg_rating_percent"]=$raing_obj["avg_rating_percent"];
}
usort($product_list, array($this, "sortByRating"));
$products_obj["items"]=$product_list;
return json_encode($products_obj);
}
public function getCategories(){
$res=parent::get("/V1/categories");
return $res;
}
public function getRating($productId){
$res=parent::get("V1/review/reviews/$productId");
return $res;

}
public function getFilter(){
$param="searchCriteria[sortOrders][0][field]=entity_type_id&searchCriteria[sortOrders][1][direction]=ASC";
$res=parent::get("/V1/products/attributes/types");
return $res;
}

public function chargeStripe($stripeToken){
try{
// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys

//\Stripe\Stripe::setApiKey("");

$charge = \Stripe\Charge::create([
    'amount' => $stripeToken["amount"],
    'currency' => $stripeToken["currency"],
    'description' => 'Mofluid Order',
    'source' => $stripeToken["token"],
]);
}
catch(\Stripe\Error\Card $e) {
  	// Since it's a decline, \Stripe\Error\Card will be caught
  	$body = $e->getJsonBody();
  	$err  = $body['error'];
  	print('Status is:' . $e->getHttpStatus() . "\n");
  	print('Type is:' . $err['type'] . "\n");
  	print('Code is:' . $err['code'] . "\n");
  	// param is '' in this case
  	print('Param is:' . $err['param'] . "\n");
	print('Message is:' . $err['message'] . "\n");
} 
catch (\Stripe\Error\RateLimit $e) {
  	// Too many requests made to the API too quickly
  	echo $e->getMessage();
} 
catch (\Stripe\Error\InvalidRequest $e) {
	// Invalid parameters were supplied to Stripe's API
	echo $e->getMessage();  
} 
catch (\Stripe\Error\Authentication $e) {
  	// Authentication with Stripe's API failed (maybe you changed API keys recently)
	echo $e->getMessage();
} 
catch (\Stripe\Error\ApiConnection $e) {
  	// Network communication with Stripe failed
	echo $e->getMessage();
} 
catch (\Stripe\Error\Base $e) {
  	// Display a very generic error to the user, and maybe send yourself an email
	echo $e->getMessage();
} 
catch (Exception $e) {
  	// Something else happened, completely unrelated to Stripe
	echo $e->getMessage();
}
}
	}
