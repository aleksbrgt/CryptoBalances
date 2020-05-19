<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Enum;

class AddressCurrencyEnum
{
    public const ETH = 'ETH';

    public const BTC = 'BTC';

    public const VALUES = [
        1 => self::ETH,
        2 => self::BTC,
    ];
}
