<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Storefront\Customer\Exception;

use OxidEsales\GraphQL\Base\Exception\Error;
use OxidEsales\GraphQL\Base\Exception\ErrorCategories;
use Throwable;

final class CustomerNotDeletable extends Error
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getCategory(): string
    {
        return ErrorCategories::REQUESTERROR;
    }

    public static function notEnabledByAdmin(): self
    {
        return new self('Deleting your own account is not enabled by shop admin!', 403);
    }

    public static function whileMallAdmin(): self
    {
        return new self('Unable to delete an account marked as mall admin!', 403);
    }

    public static function byModel(): self
    {
        return new self('Server error encountered while deleting account!', 500);
    }
}
