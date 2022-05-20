<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Storefront\Customer\Exception;

use OxidEsales\GraphQL\Base\Exception\Error;
use OxidEsales\GraphQL\Base\Exception\ErrorCategories;

final class InvalidEmail extends Error
{
    public function getCategory(): string
    {
        return ErrorCategories::REQUESTERROR;
    }

    public static function byEmptyString(): self
    {
        return new self('The e-mail address must not be empty!');
    }

    public static function byString(string $email): self
    {
        return new self(sprintf("This e-mail address '%s' is invalid!", $email));
    }

    public static function byConfirmationCode(string $code): self
    {
        return new self(sprintf("Wrong e-mail confirmation code '%s'!", $code));
    }
}
