<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Projekteins\GraphQL\Mutator\Product\Infrastructure;

use OxidEsales\Eshop\Application\Model\Article as EshopProductModel;
use OxidEsales\Eshop\Application\Model\ArticleList as EshopProductListModel;
use OxidEsales\Eshop\Application\Model\Category as EshopCategoryModel;
use OxidEsales\GraphQL\Storefront\Product\DataType\Product as ProductDataType;
use OxidEsales\GraphQL\Base\Exception\NotFound;

use RuntimeException;

use function array_map;
use function count;
use function is_iterable;

final class Product
{
    /**
     * @throws RuntimeException
     * @return true
     */
    public function saveProduct(ProductDataType $product): bool
    {
        if (!$product->getEshopModel()->save()) {
            throw new RuntimeException('Object save failed');
        }

        return true;
    }

    /**
     * @throws NotFound
     */
    public function product(string $id): ProductDataType
    {
        /** @var EshopProductModel */
        $product = oxNew(EshopProductModel::class);

        if (!$product->load($id)) {
            throw new NotFound();
        }

        return new ProductDataType(
            $product
        );
    }
}
