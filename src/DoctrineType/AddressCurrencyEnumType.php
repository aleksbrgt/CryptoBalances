<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\DoctrineType;

use Aleksbrgt\Balances\DoctrineType\Abstraction\AbstractEnumType;
use Aleksbrgt\Balances\Enum\AddressCurrencyEnum;

class AddressCurrencyEnumType extends AbstractEnumType
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'address_currency_enum';
    }

    /**
     * @inheritDoc
     */
    public function getKeyValues(): array
    {
        return AddressCurrencyEnum::VALUES;
    }
}