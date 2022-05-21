<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types = 1);

namespace Projekteins\GraphQL\Mutator\Product\Service;

use OxidEsales\GraphQL\Base\DataType\Pagination\Pagination as PaginationFilter;
use OxidEsales\GraphQL\Base\DataType\Sorting\Sorting as BaseSorting;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use Projekteins\GraphQL\Mutator\Product\DataType\Product as ProductDataType;
use Projekteins\GraphQL\Mutator\Product\Exception\ProductNotFound;
use Projekteins\GraphQL\Mutator\Product\Infrastructure\Product as ProductInfrastructure;
use Projekteins\GraphQL\Mutator\Shared\Infrastructure\Repository as Repository;
use TheCodingMachine\GraphQLite\Types\ID;

final class Product
{
    /** @var Repository */
    private $repository;

    public function __construct(
        Repository $repository,
    ) {
        $this->repository = $repository;
    }

    /**
     * @throws ProductNotFound
     * @throws InvalidLogin
     */
    public function product(ID $id): ProductDataType
    {
        try {
            /** @var ProductDataType $product */
            $product = $this->repository->getById((string)$id, ProductDataType::class);
        } catch (NotFound $e) {
            throw ProductNotFound::byId((string)$id);
        }

        if ($product->isActive()) {
            return $product;
        }

        throw new InvalidLogin('Unauthorized');
    }

    /**
     * @return true
     */
    public function store(ProductDataType $product): bool
    {
        return (new ProductInfrastructure())->saveProduct($product);
    }
}
