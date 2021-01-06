<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Test\Unit\Model\Command;

use Jeysmook\CustomerPrices\Model\Command\GetScopeCustomerIdsGroupIds;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see GetScopeCustomerIdsGroupIds
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class GetScopeCustomerIdsGroupIdsTest extends TestCase
{
    /**
     * @var AdapterInterface|MockObject
     */
    private $connection;

    /**
     * @var GetScopeCustomerIdsGroupIds
     */
    private $getScopeCustomerIdsGroupIds;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'from', 'limit'])
            ->getMock();
        $this->connection = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->connection->expects($this->any())
            ->method('select')
            ->willReturn($select);
        $resourceConnection = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resourceConnection->expects($this->any())
            ->method('getConnection')
            ->willReturn($this->connection);
        $this->getScopeCustomerIdsGroupIds = new GetScopeCustomerIdsGroupIds($resourceConnection);
    }

    /**
     * @dataProvider dataProvider
     * @param int $websiteId
     * @param array $expectedValue
     * @see GetScopeCustomerIdsGroupIds::execute()
     */
    public function testExecute(int $websiteId, array $expectedValue): void
    {
        $this->connection->expects($this->once())
            ->method('fetchPairs')
            ->willReturn($expectedValue);
        $this->assertEquals($expectedValue, $this->getScopeCustomerIdsGroupIds->execute($websiteId));
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            // [websiteId, expectedValue]
            [1, [1 => 1, 2 => 1, 3 => 1]],
            [2, [4 => 2, 5 => 2, 6 => 2]],
            [3, [7 => 3, 8 => 3, 9 => 3]],
        ];
    }
}
