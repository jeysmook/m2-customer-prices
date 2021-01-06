<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Block\Adminhtml\CustomerPrice\Edit;

use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Block\Adminhtml\CustomerPrice\Edit\DeleteButton;

/**
 * @see DeleteButton
 */
class DeleteButtonTest extends GenericButtonTest
{
    /**
     * @var DeleteButton
     */
    private $deleteButton;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->deleteButton = new DeleteButton(
            $this->context,
            $this->customerPriceRepository
        );
    }

    /**
     * @see DeleteButton::getButtonData()
     */
    public function testGetButtonData(): void
    {
        $deleteUrl = 'https://example.com/admin/customerPrice';
        $buttonData = [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to do this?')
                . '\', \'' . $deleteUrl . '\', {"data": {}})',
            'sort_order' => 20,
        ];
        $this->urlBuilder->expects($this->once())
            ->method('getUrl')
            ->willReturn($deleteUrl);
        $customerPriceId = 2;
        $customerPrice = $this->createMock(CustomerPriceInterface::class);
        $customerPrice->expects($this->any())
            ->method('getItemId')
            ->willReturn($customerPriceId);
        $this->request->expects($this->any())
            ->method('getParam')
            ->with('item_id')
            ->willReturn($customerPriceId);
        $this->customerPriceRepository->expects($this->any())
            ->method('get')
            ->with($customerPriceId)
            ->willReturn($customerPrice);
        $this->assertEquals($buttonData, $this->deleteButton->getButtonData());
    }
}
