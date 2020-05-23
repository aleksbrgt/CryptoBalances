<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Abstraction;

use Aleksbrgt\Balances\Service\ApiIntegration\Dto\AddressInformationDto;

interface GetAddressesInformationInterface
{
    /**
     * @param string $currency
     *
     * @return bool
     */
    public function supports(string $currency): bool;

    /**
     * @param array $addresses
     *
     * @return iterable|AddressInformationDto[]
     */
    public function get(array $addresses): iterable;
}
