<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Jeysmook\CustomerPrices\Model\Command\GetProductByCustomerPriceId;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see GetProductByCustomerPriceId
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class GetProductByCustomerPriceIdTest extends TestCase
{
    /**
     * @var AdapterInterface|MockObject
     */
    private $connection;

    /**
     * @var ProductRepositoryInterface|MockObject
     */
    private $productRepository;

    /**
     * @var GetProductByCustomerPriceId
     */
    private $getProductByCustomerPriceId;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'from', 'order'])
            ->getMock();
        $this->connection = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->any())
            ->method('select')
            ->willReturn($select);
        $resource = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resource->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);
        $this->productRepository = $this->getMockBuilder(ProductRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->getProductByCustomerPriceId = new GetProductByCustomerPriceId(
            $resource,
            $this->productRepository
        );
    }

    /**
     * @throws NoSuchEntityException
     * @see GetProductByCustomerPriceId::execute()
     */
    public function testExecute(): void
    {
        $productId = 1;
        $customerPriceId = 2;
        $product = $this->getMockBuilder(ProductInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->once())
            ->method('fetchOne')
            ->willReturn($productId);
        $this->productRepository->expects($this->once())
            ->method('getById')
            ->willReturn($product);
        $this->assertEquals($product, $this->getProductByCustomerPriceId->execute($customerPriceId));
    }

    /**
     * @throws NoSuchEntityException
     * @see GetProductByCustomerPriceId::execute()
     */
    public function testExecuteWithException(): void
    {
        $customerPriceId = 3;
        $this->connection->expects($this->once())
            ->method('fetchOne')
            ->willReturn(0);
        $this->expectException(NoSuchEntityException::class);
        $this->getProductByCustomerPriceId->execute($customerPriceId);
    }
}
