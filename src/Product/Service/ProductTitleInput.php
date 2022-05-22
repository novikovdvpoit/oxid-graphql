<?php

declare(strict_types = 1);

namespace Projekteins\GraphQL\Mutator\Product\Service;

use OxidEsales\GraphQL\Storefront\Product\DataType\Product as ProductDataType;
use Projekteins\GraphQL\Mutator\Product\Infrastructure\Product as ProductRepository;
use Projekteins\GraphQL\Mutator\Product\Infrastructure\ProductMutation as ProductMutationService;
use OxidEsales\GraphQL\Storefront\Product\Exception\ProductNotFound;
use OxidEsales\GraphQL\Base\Exception\NotFound;
use TheCodingMachine\GraphQLite\Annotations\Factory;

final class ProductTitleInput
{
    /** @var ProductRepository */
    private $productRepository;

    /** @var ProductMutationService */
    private $productMutationService;

    public function __construct(
        ProductRepository $productRepository,
        ProductMutationService $productMutationService
    ) {
        $this->productRepository = $productRepository;
        $this->productMutationService = $productMutationService;
    }

    /**
     * @Factory
     */
    public function fromUserInput(string $productId, string $title): ProductDataType
    {
        try {
            $product = $this->productRepository->product($productId);
        } catch (NotFound $e) {
            throw ProductNotFound::byId($productId);
        }

        return $this->productMutationService->assignTitle($product, $title);
    }
}
