<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Projekteins\GraphQL\Mutator\Product\Controller;

use OxidEsales\GraphQL\Storefront\Product\DataType\Product as ProductDataType;
use Projekteins\GraphQL\Mutator\Product\Service\Product as ProductService;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Types\ID;

final class ProductMutation
{
    /** @var ProductService */
    private $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    /**
     * @Query()
     */
    public function product(ID $productId): ProductDataType
    {
        return $this->productService->product($productId);
    }

    /**
     * @Mutation()
     * @Logged()
     */
    public function productTitleUpdate(ProductDataType $product): ProductDataType
    {
        $this->productService->store($product);

        return $product;
    }
}
