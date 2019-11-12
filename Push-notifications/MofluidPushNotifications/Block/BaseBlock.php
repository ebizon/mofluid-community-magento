<?php
/**
 * Copyright © 2015 Push-notifications . All rights reserved.
 */
namespace Pushnotifications\MofluidPushNotifications\Block;
use Magento\Framework\UrlFactory;
class BaseBlock extends \Magento\Framework\View\Element\Template
{
	/**
     * @var \Push-notifications\MofluidPushNotifications\Helper\Data
     */
	 protected $_devToolHelper;

	 /**
     * @var \Magento\Framework\Url
     */
	 protected $_urlApp;

	 /**
     * @var \Push-notifications\MofluidPushNotifications\Model\Config
     */
    protected $_config;

    /**
     * @param \Push-notifications\MofluidPushNotifications\Block\Context $context
	 * @param \Magento\Framework\UrlFactory $urlFactory
     */
    public function __construct( \Pushnotifications\MofluidPushNotifications\Block\Context $context
	)
    {
        $this->_devToolHelper = $context->getMofluidPushNotificationsHelper();
		$this->_config = $context->getConfig();
        $this->_urlApp=$context->getUrlFactory()->create();
		parent::__construct($context);

    }

	/**
	 * Function for getting event details
	 * @return array
	 */
    public function getEventDetails()
    {
		return  $this->_devToolHelper->getEventDetails();
    }

	/**
     * Function for getting current url
	 * @return string
     */
	public function getCurrentUrl(){
		return $this->_urlApp->getCurrentUrl();
	}

	/**
     * Function for getting controller url for given router path
	 * @param string $routePath
	 * @return string
     */
	public function getControllerUrl($routePath){

		return $this->_urlApp->getUrl($routePath);
	}

	/**
     * Function for getting current url
	 * @param string $path
	 * @return string
     */
	public function getConfigValue($path){
		return $this->_config->getCurrentStoreConfigValue($path);
	}

	/**
     * Function canShowMofluidPushNotifications
	 * @return bool
     */
	public function canShowMofluidPushNotifications(){
		$isEnabled=$this->getConfigValue('mofluidpushnotifications/module/is_enabled');
		if($isEnabled)
		{
			$allowedIps=$this->getConfigValue('mofluidpushnotifications/module/allowed_ip');
			 if(is_null($allowedIps)){
				return true;
			}
			else {
				$remoteIp=$_SERVER['REMOTE_ADDR'];
				if (strpos($allowedIps,$remoteIp) !== false) {
					return true;
				}
			}
		}
		return false;
	}

}
