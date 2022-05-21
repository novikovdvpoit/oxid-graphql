<?php

declare(strict_types=1);

namespace Projekteins\GraphQL\Mutator\Shared\Service;

use OxidEsales\GraphQL\Base\Framework\NamespaceMapperInterface;

final class NamespaceMapper implements NamespaceMapperInterface
{
    private const SPACE = '\\Projekteins\\GraphQL\\Mutator\\';

    public function getControllerNamespaceMapping(): array
    {
        return [
            self::SPACE . 'Product\\Controller' => __DIR__ . '/../../Product/Controller/',
        ];
    }

    public function getTypeNamespaceMapping(): array
    {
        return [
            self::SPACE . 'Shared\\DataType' => __DIR__ . '/../../Shared/DataType/',
            self::SPACE . 'Shared\\Service' => __DIR__ . '/../../Shared/Service/',
            self::SPACE . 'Product\\DataType' => __DIR__ . '/../../Product/DataType/',
            self::SPACE . 'Product\\Service' => __DIR__ . '/../../Product/Service/',
        ];
    }
}
