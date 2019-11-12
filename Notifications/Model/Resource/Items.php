<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

namespace Mofluid\Notifications\Model\Resources;

class Items extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mofluid_notifications_items', 'id');
    }
}
