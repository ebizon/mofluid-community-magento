<?php
/**
 * Copyright © 2015 Mofluid. All rights reserved.
 */

namespace Mofluid\Notifications\Controller\Adminhtml\Items;

class Index extends \Mofluid\Notifications\Controller\Adminhtml\Items
{
    /**
     * Items list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mofluid_Notifications::notifications');
        $resultPage->getConfig()->getTitle()->prepend(__('Mofluid Notification'));
        $resultPage->addBreadcrumb(__('Mofluid'), __('Mofluid'));
        $resultPage->addBreadcrumb(__('Items'), __('Items'));
        return $resultPage;
    }
}
