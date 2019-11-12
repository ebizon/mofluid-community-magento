<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */
namespace Mofluid\Mofluidapi2\Model\ResourceModel;

/**
 * Banner resource
 */
class Logo extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('mofluid_themes_images', 'mofluid_image_id');
    }

  
}
