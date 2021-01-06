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
use Jeysmook\CustomerPrices\Block\Adminhtml\CustomerPrice\Edit\ResetButton;

/**
 * @see ResetButton
 */
class ResetButtonTest extends GenericButtonTest
{
    /**
     * @var ResetButton
     */
    private $resetButton;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->resetButton = new ResetButton(
            $this->context,
            $this->customerPriceRepository
        );
    }

    /**
     * @see ResetButton::getButtonData()
     */
    public function testGetButtonData(): void
    {
        $buttonData = [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30,
        ];
        $customerPriceId = 3;
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
        $this->assertEquals($buttonData, $this->resetButton->getButtonData());
    }
}
