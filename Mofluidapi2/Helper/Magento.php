<?php
namespace Mofluid\Mofluidapi2\Helper;
use Magento\Authorization\Model\Acl\Role\Group as RoleGroup;
use Magento\Authorization\Model\UserContextInterface;
 class Magento{
private $oauth_consumer_key;
private $oauth_token;
private $oauth_signature_method;
private $oauth_nonce;
private $oauth_version;
private $oauth_signature;
private $base_url;
private $userData;
private $role_name="mofluid_rest";
private  $user_name="mofluid_rest";
private  $first_name="mofluid_rest";
private  $last_name="mofluid_rest";
private  $password="mofluid2@!rest";
private  $email="mofluid@gmail.com";
public function createUser(){
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$userObj=$objectManager->create('Magento\User\Model\UserFactory')->create();
$roleFactory=$objectManager->create('Magento\Authorization\Model\RoleFactory')->create();
$rulesFactory=$objectManager->create('Magento\Authorization\Model\RulesFactory')->create();
$roleFactory->setName($this->role_name)->setPid(0)->setRoleType(RoleGroup::ROLE_TYPE)->setUserType(UserContextInterface::USER_TYPE_ADMIN);
$roleFactory->save();
$resource=['Magento_Backend::dashboard', 
'Magento_Analytics::analytics', 
'Magento_Analytics::analytics_api', 
'Magento_Sales::sales', 
'Magento_Sales::sales_operation', 
'Magento_Sales::sales_order', 
'Magento_Sales::actions', 
'Magento_Sales::create', 
'Magento_Sales::actions_view', 
'Magento_Sales::email', 
'Magento_Sales::reorder', 
'Magento_Sales::actions_edit', 
'Magento_Sales::cancel', 
'Magento_Sales::review_payment', 
'Magento_Sales::capture', 
'Magento_Sales::invoice', 
'Magento_Sales::creditmemo', 
'Magento_Sales::hold', 
'Magento_Sales::unhold', 
'Magento_Sales::ship', 
'Magento_Sales::comment', 
'Magento_Sales::emails', 
'Magento_Sales::sales_invoice', 
'Magento_Sales::shipment', 
'Temando_Shipping::dispatches', 
'Magento_Sales::sales_creditmemo', 
'Magento_Paypal::billing_agreement', 
'Magento_Paypal::billing_agreement_actions', 
'Magento_Paypal::billing_agreement_actions_view', 
'Magento_Paypal::actions_manage', 
'Magento_Paypal::use', 
'Magento_Sales::transactions', 
'Magento_Sales::transactions_fetch', 
'Magento_Catalog::catalog', 
'Magento_Catalog::catalog_inventory', 
'Magento_Catalog::products', 
'Magento_Catalog::categories', 
'Magento_Customer::customer', 
'Magento_Customer::manage', 
'Magento_Customer::online', 
'Magento_Cart::cart', 
'Magento_Cart::manage', 
'Magento_Backend::myaccount', 
'Magento_Backend::marketing', 
'Magento_CatalogRule::promo', 
'Magento_CatalogRule::promo_catalog', 
'Magento_SalesRule::quote', 
'Dotdigitalgroup_Email::automation', 
'Dotdigitalgroup_Email::automation_studio', 
'Dotdigitalgroup_Email::exclusion_rules', 
'Magento_Backend::marketing_communications', 
'Magento_Email::template', 
'Magento_Newsletter::template', 
'Magento_Newsletter::queue', 
'Magento_Newsletter::subscriber', 
'Magento_Backend::marketing_seo', 
'Magento_Search::search', 
'Magento_Search::synonyms', 
'Magento_UrlRewrite::urlrewrite', 
'Magento_Sitemap::sitemap', 
'Magento_Backend::marketing_user_content', 
'Magento_Review::reviews_all', 
'Magento_Review::pending', 
'Magento_Backend::content', 
'Magento_Backend::content_elements', 
'Magento_Cms::page', 
'Magento_Cms::save', 
'Magento_Cms::page_delete', 
'Magento_Cms::block', 
'Magento_Widget::widget_instance', 
'Magento_Cms::media_gallery', 
'Magento_Backend::design', 
'Magento_Theme::theme', 
'Magento_Backend::schedule', 
'Magento_Backend::content_translation', 
'Magento_Reports::report', 
'Dotdigitalgroup_Email::reports', 
'Dotdigitalgroup_Email::contact', 
'Dotdigitalgroup_Email::order', 
'Dotdigitalgroup_Email::review', 
'Dotdigitalgroup_Email::wishlist', 
'Dotdigitalgroup_Email::catalog', 
'Dotdigitalgroup_Email::importer', 
'Dotdigitalgroup_Email::campaign', 
'Dotdigitalgroup_Email::cron', 
'Dotdigitalgroup_Email::dashboard', 
'Dotdigitalgroup_Email::automation_enrollment', 
'Dotdigitalgroup_Email::logviewer', 
'Magento_Reports::report_marketing', 
'Magento_Reports::shopcart', 
'Magento_Reports::product', 
'Magento_Reports::abandoned', 
'Magento_Reports::report_search', 
'Magento_Newsletter::problem', 
'Magento_Reports::review',
'Magento_Reports::review_customer',
'Magento_Reports::review_product',
'Magento_Reports::salesroot',
'Magento_Reports::salesroot_sales',
'Magento_Reports::tax',
'Magento_Reports::invoiced',
'Magento_Reports::shipping',
'Magento_Reports::refunded',
'Magento_Reports::coupons',
'Magento_Paypal::paypal_settlement_reports',
'Magento_Paypal::paypal_settlement_reports_view',
'Magento_Paypal::fetch',
'Magento_Braintree::settlement_report',
'Magento_Reports::customers',
'Magento_Reports::totals',
'Magento_Reports::customers_orders',
'Magento_Reports::accounts',
'Magento_Reports::report_products',
'Magento_Reports::viewed',
'Magento_Reports::bestsellers',
'Magento_Reports::lowstock',
'Magento_Reports::sold',
'Magento_Reports::downloads',
'Magento_Reports::statistics',
'Magento_Reports::statistics_refresh',
'Magento_Analytics::business_intelligence',
'Magento_Analytics::advanced_reporting',
'Magento_Analytics::bi_essentials',
'Magento_Backend::stores',
'Magento_Backend::stores_settings',
'Magento_Backend::store',
'Magento_Config::config',
'Mofluid_Notifications::system_config',
'Magento_Catalog::config_catalog',
'Magento_CatalogSearch::config_catalog_search',
'Dotdigitalgroup_Email::config',
'Magento_Sales::fraud_protection',
'Magento_GoogleAnalytics::google',
'Magento_Newsletter::newsletter',
'Magento_Downloadable::downloadable',
'Magento_CatalogInventory::cataloginventory',
'Magento_Payment::payment_services',
'Magento_Payment::payment',
'Magento_Contact::contact',
'Magento_Cms::config_cms',
'Magento_Shipping::config_shipping',
'Magento_Shipping::carriers',
'Magento_Shipping::shipping_policy',
'Magento_Multishipping::config_multishipping',
'Magento_Config::config_general',
'Magento_Config::web',
'Magento_Config::config_design',
'Magento_Paypal::paypal',
'Magento_Customer::config_customer',
'Magento_Tax::config_tax',
'Magento_Checkout::checkout',
'Magento_Sales::config_sales',
'Magento_Persistent::persistent',
'Magento_Sales::sales_email',
'Magento_Sales::sales_pdf',
'Magento_Reports::reports',
'Magento_Sitemap::config_sitemap',
'Magento_Config::config_system',
'Magento_Wishlist::config_wishlist',
'Magento_Config::advanced',
'Magento_SalesRule::config_promo',
'Magento_Config::trans_email',
'Magento_Config::config_admin',
'Magento_Config::dev',
'Magento_Config::currency',
'Magento_Rss::rss',
'Magento_Config::sendfriend',
'Magento_Analytics::analytics_settings',
'Magento_NewRelicReporting::config_newrelicreporting',
'Magento_CheckoutAgreements::checkoutagreement',
'Magento_Sales::order_statuses',
'Temando_Shipping::shipping',
'Temando_Shipping::carriers',
'Temando_Shipping::locations',
'Temando_Shipping::packaging',
'Magento_Tax::manage_tax',
'Magento_CurrencySymbol::system_currency',
'Magento_CurrencySymbol::currency_rates',
'Magento_CurrencySymbol::symbols',
'Magento_Backend::stores_attributes',
'Magento_Catalog::attributes_attributes',
'Magento_Catalog::update_attributes',
'Magento_Catalog::sets',
'Magento_Review::ratings',
'Magento_Swatches::iframe',
'Magento_Backend::stores_other_settings',
'Magento_Customer::group',
'Magento_Backend::system',
'Magento_Backend::convert',
'Magento_ImportExport::import',
'Magento_ImportExport::export',
'Magento_TaxImportExport::import_export',
'Magento_ImportExport::history',
'Magento_Backend::extensions',
'Magento_Backend::local',
'Magento_Backend::custom',
'Magento_Backend::tools',
'Magento_Backend::cache',
'Magento_Backend::main_actions',
'Magento_Backend::flush_cache_storage',
'Magento_Backend::flush_magento_cache',
'Magento_Backend::mass_actions',
'Magento_Backend::toggling_cache_type',
'Magento_Backend::refresh_cache_type',
'Magento_Backend::additional_cache_management',
'Magento_Backend::flush_catalog_images',
'Magento_Backend::flush_js_css',
'Magento_Backend::flush_static_files',
'Magento_Backend::setup_wizard',
'Magento_Backup::backup',
'Magento_Backup::rollback',
'Magento_Indexer::index',
'Magento_Indexer::changeMode',
'Magento_User::acl',
'Magento_User::acl_users',
'Magento_User::locks',
'Magento_User::acl_roles',
'Magento_Backend::system_other_settings',
'Magento_AdminNotification::adminnotification',
'Magento_AdminNotification::show_toolbar',
'Magento_AdminNotification::show_list',
'Magento_AdminNotification::mark_as_read',
'Magento_AdminNotification::adminnotification_remove',
'Magento_Variable::variable',
'Magento_EncryptionKey::crypt_key',
'Magento_Backend::global_search',
'Mofluid_Notifications::notification',
'Mofluid_Notifications::add_news',
'Mofluid_Notifications::manage_news',
'Mofluid_Notifications::configuration'];
$rulesFactory->setRoleId($roleFactory->getId())->setResources($resource)->saveRel();
try {
         $userObj->setData(array(
            'username'  => $this->user_name,
            'firstname' => $this->first_name,
            'lastname'  => $this->last_name,
            'email'     => $this->email,
            'password'  => $this->password,
            'is_active' => 1
        ));
$userObj->setRoleId($roleFactory->getId());
   $userObj->save();
} catch (Exception $e) {
  return false;
}
return true;
}
public function isUserExist(){
$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
$userObj=$objectManager->create('Magento\User\Model\UserFactory')->create();
$user_data=$userObj->getCollection()->addFieldToFilter('username',$this->user_name)->getData();
if(count($user_data)>=1)
return true;
return false;
}
public function __construct()
{           
              if(!$this->isUserExist())
                 $this->createUser();
            $this-> userData=array("username" => $this->user_name, "password" => $this->password);
            $this->oauth_consumer_key="ypitih373p6yx7p4ndo6ojx7nj38mpbg";
            $this->oauth_token="ro692ioqbt49i96nt69imwytk8ortuqs";
            $this->oauth_signature_method="HMAC-SHA1";
            $this->oauth_nonce="TBSR5T";
            $this->oauth_version=1.0;
            $this->oauth_signature="D5X43jZL8DkmclDiEm37V%20Jp3k4%3D";
          $protocol=(isset($_SERVER['HTTPS']) ? "https" : "http");
          $url = $protocol."://".$_SERVER['HTTP_HOST'];
           $this->base_url=$url."/rest";
        }
public function getHeader(){
$header="";
//$header.="oauth_consumer_key=".$this->oauth_consumer_key;
//$header.="&oauth_token=".$this->oauth_token;
//$header.="&oauth_signature_method=".$this->oauth_signature_method;
//$header.="&oauth_nonce=".$this->oauth_nonce;
//$header.="&oauth_version=".$this->oauth_version;
//$header.="&oauth_signature=".$this->oauth_signature;
return $header;
	}
public function getResponse($url,$param=null){
$curl = curl_init();
$url=$this->base_url.$url;
$header=$this->getHeader();
$final_query_string=$header;
if($param!=null && !empty($param))
$final_query_string.="&".$param;
$final_url=$url."?".$final_query_string;
curl_setopt_array($curl, array(
  CURLOPT_URL => $final_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer " . json_decode($this->getToken("/V1/integration/admin/token")),
    "accept: application/json",
    "cache-control: no-cache"
  ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
return $response;
	}
public function getToken($url){
$ch = curl_init($this->base_url.$url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->userData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Content-Lenght: " . strlen(json_encode($this->userData))));
$token = curl_exec($ch);
return $token;
	}
public function util($url,$opearation,$body=null,$param){
$curl = curl_init();
$url=$this->base_url.$url;
$header=$this->getHeader();
$final_query_string=$header;
if($param!=null && !empty($param))
$final_query_string.="&".$param;
$final_url=$url."?".$final_query_string;
//$final_url=$url;
curl_setopt_array($curl, array(
  CURLOPT_URL => $final_url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 300,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => $opearation,
  CURLOPT_RETURNTRANSFER=>true,
//  CURLOPT_POSTFIELDS=>json_encode($body),
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer " . json_decode($this->getToken("/V1/integration/admin/token")),
    "accept: application/json",
    "cache-control: no-cache",
    "Content-type: application/json"
  )
));
if($body!=null && !empty($body))
curl_setopt($curl, CURLOPT_POSTFIELDS,$body);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
return $response;
	}
public function post($url,$body=null,$param=null){
	return $this->util($url,"POST",$body,$param);
	}
public function update($url,$body=null,$param=null){
	return $this->util($url,"PUT",$body,$param);
	}
public function delete($url,$body=null,$param=null){
	return $this->util($url,"DELETE",$body,$param);
	}
public function get($url,$body=null,$param=null){
	return $this->util($url,"GET",$body,$param);
	}
}
