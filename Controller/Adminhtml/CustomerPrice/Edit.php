<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPrice;

use Jeysmook\CustomerPrices\Controller\Adminhtml\CustomerPriceAction;
use Jeysmook\CustomerPrices\Model\CustomerPrice\Locator;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Edit the customer price entity
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Edit extends CustomerPriceAction
{
    /**
     * @var Locator
     */
    private $locator;

    /**
     * Edit constructor
     *
     * @param Action\Context $context
     * @param Locator $locator
     */
    public function __construct(
        Action\Context $context,
        Locator $locator
    ) {
        parent::__construct($context);
        $this->locator = $locator;
    }

    /**
     * Edit the customer price entity
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $this->getMessageManager()->addNoticeMessage(
            __('Please note that the customer price based on scope.')
        );

        $resultRedirect = $this->resultRedirectFactory->create();
        $customerPrice = $this->locator->getCustomerPrice();
        if (!empty($this->getRequest()->getParam('item_id')) && !$customerPrice->getItemId()) {
            $this->messageManager->addErrorMessage(__('This customer price no longer exists.'));
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        $pageTitle = $customerPrice->getItemId() ? __('Edit') : __('New');
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Jeysmook_CustomerPrices::customer_price');
        $resultPage->addBreadcrumb(__('Catalog'), __('Catalog'));
        $resultPage->addBreadcrumb(__('Inventory'), __('Inventory'));
        $resultPage->addBreadcrumb($pageTitle, $pageTitle);
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Prices'));
        $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        return $resultPage;
    }
}
