<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

namespace Mofluid\Notifications\Controller\Adminhtml\Items;

class NewAction extends \Mofluid\Notifications\Controller\Adminhtml\Items
{

    public function execute()
    { 
        $this->_forward('edit');
    }
}
