<?php

/**
 * Customer Prices for Magento 2
 *
 * Copyright © Dmitry Kaplin - All rights reserved.
 * See LICENSE.txt bundled with this module for license details.
 */

declare(strict_types=1);

namespace Jeysmook\CustomerPrices\Model\Command\CustomerPrice;

use Exception;
use Jeysmook\CustomerPrices\Api\Data\CustomerPriceInterface;
use Jeysmook\CustomerPrices\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save the customer price
 */
class Save
{
    /**
     * @var CustomerPrice
     */
    private $resource;

    /**
     * Save constructor
     *
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPrice $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Save the customer price
     *
     * @param CustomerPriceInterface $request
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(CustomerPriceInterface $request): int
    {
        try {
            $this->resource->save($request);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the customer price: %error', ['error' => $exception->getMessage()]),
                $exception
            );
        }
        return (int)$request->getItemId();
    }
}
