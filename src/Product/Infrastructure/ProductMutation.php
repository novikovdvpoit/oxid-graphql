<?php

declare(strict_types = 1);

namespace Projekteins\GraphQL\Mutator\Product\Infrastructure;

use OxidEsales\GraphQL\Storefront\Product\DataType\Product as ProductDataType;

final class ProductMutation
{
    public function assignTitle(
        ProductDataType $product,
        string $title
    ): ProductDataType {
        $product->getEshopModel()->assign(
            [
                'oxtitle' => $title
            ]
        );

        return $product;
    }
}
