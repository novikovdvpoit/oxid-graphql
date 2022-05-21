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
use Projekteins\GraphQL\Mutator\Product\DataType\Product as ProductDataType;
use OxidEsales\GraphQL\Base\Exception\NotFound;

use RuntimeException;

use function array_map;
use function count;
use function is_iterable;

final class Product
{
    /**
     * @return EshopCategoryModel
     */
    public function getMainCategory(ProductDataType $product): ?EshopCategoryModel
    {
        /** @var null|EshopCategoryModel $category */
        $category = $product->getEshopModel()->getCategory();

        if (
            $category === null ||
            !$category->getId()
        ) {
            return null;
        }

        return $category;
    }

    public function getCategories(
        ProductDataType $product
    ): array {
        return $product->getEshopModel()->getCategoryIds();
    }

    /**
     * @return ProductDataType[]
     */
    public function getCrossSelling(ProductDataType $product): array
    {
        /** @var EshopProductListModel $products */
        $products = $product->getEshopModel()->getCrossSelling();

        if (!is_iterable($products) || count($products) === 0) {
            return [];
        }

        $crossSellings = [];

        /** @var EshopProductModel $product */
        foreach ($products as $product) {
            $crossSellings[] = new ProductDataType($product);
        }

        return $crossSellings;
    }

    /**
     * @return ProductDataType[]
     */
    public function getAccessories(ProductDataType $product): array
    {
        /** @var EshopProductListModel $products */
        $products = $product->getEshopModel()->getAccessoires();

        if (!is_iterable($products) || count($products) === 0) {
            return [];
        }

        $accessories = [];

        /** @var EshopProductModel $product */
        foreach ($products as $product) {
            $accessories[] = new ProductDataType($product);
        }

        return $accessories;
    }

    /**
     * @return ProductDataType[]
     */
    public function getVariants(ProductDataType $product): array
    {
        // when using getVariants() product relations are returned as SimpleVariant type
        $productVariants = $product->getEshopModel()->getFullVariants();

        if (!is_iterable($productVariants) || count($productVariants) === 0) {
            return [];
        }

        $variants = [];

        /** @var EshopProductModel $variant */
        foreach ($productVariants as $variant) {
            $variants[] = new ProductDataType($variant);
        }

        return $variants;
    }

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
