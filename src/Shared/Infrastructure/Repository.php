<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Projekteins\GraphQL\Mutator\Shared\Infrastructure;

use InvalidArgumentException;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\DataType\Filter\FilterInterface;
use OxidEsales\GraphQL\Base\DataType\Pagination\Pagination as PaginationFilter;
use OxidEsales\GraphQL\Base\DataType\ShopModelAwareInterface;
use OxidEsales\GraphQL\Base\DataType\Sorting\Sorting as BaseSorting;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use Projekteins\GraphQL\Mutator\Product\DataType\Product as ProductDataType;
use Projekteins\GraphQL\Mutator\Shared\Exception\Repository as RepositoryException;
use PDO;
use RuntimeException;

final class Repository
{
    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    /**
     * @template T
     *
     * @param class-string<T> $type
     *
     * @throws InvalidArgumentException if $type is not instance of ShopModelAwareInterface
     * @throws NotFound                 if BaseModel can not be loaded
     *
     * @return T
     */
    public function getById(
        string $id,
        string $type,
        bool $disableSubShop = true
    ) {
        $model = $this->getModel($type::getModelClass(), $disableSubShop);

        if (!$model->load($id) || (method_exists($model, 'canView') && !$model->canView())) {
            throw new NotFound($id);
        }
        $type = new $type($model);

        if (!($type instanceof ShopModelAwareInterface)) {
            throw new InvalidArgumentException();
        }

        return $type;
    }

    /**
     * @return true
     * @throws NotFound
     *
     */
    public function delete(BaseModel $item): bool
    {
        if (!$item->delete()) {
            throw RepositoryException::cannotDeleteModel();
        }

        return true;
    }

    /**
     * @return true
     */
    public function saveModel(BaseModel $item): bool
    {
        if (!$item->save()) {
            throw RepositoryException::cannotSaveModel();
        }

        return true;
    }

    /**
     * @param class-string $modelClass
     *
     * @throws InvalidArgumentException if model in $type is not instance of BaseModel
     */
    private function getModel(string $modelClass, bool $disableSubShop): BaseModel
    {
        $model = oxNew($modelClass);

        if (!($model instanceof BaseModel)) {
            throw new InvalidArgumentException();
        }

        if (method_exists($model, 'setDisableShopCheck')) {
            $model->setDisableShopCheck($disableSubShop);
        }

        return $model;
    }
}
