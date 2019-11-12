<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mofluid\Mofluidapi2\Model\ResourceModel\Logo;

/**
 * Banners Collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Mofluid\Mofluidapi2\Model\Logo', 'Mofluid\Mofluidapi2\Model\ResourceModel\Logo');
    }
}
