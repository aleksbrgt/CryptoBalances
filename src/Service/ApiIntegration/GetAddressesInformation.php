<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Service\ApiIntegration;

use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Service\Address\SortAddressByCurrency;
use Aleksbrgt\Balances\Service\ApiIntegration\Dto\AddressInformationDto;
use Aleksbrgt\Balances\Service\ApiIntegration\Locator\GetAddressesInformationLocator;

class GetAddressesInformation
{
    /** @var GetAddressesInformationLocator */
    private $getAddressesInformationLocator;

    /** @var SortAddressByCurrency */
    private $sortAddresses;

    /**
     * @param GetAddressesInformationLocator $getAddressesInformationLocator
     * @param SortAddressByCurrency $sortAddresses
     */
    public function __construct(
        GetAddressesInformationLocator $getAddressesInformationLocator,
        SortAddressByCurrency $sortAddresses
    ) {
        $this->getAddressesInformationLocator = $getAddressesInformationLocator;
        $this->sortAddresses = $sortAddresses;
    }

    /**
     * @param Address[] $addresses
     *
     * @return iterable|AddressInformationDto[]
     */
    public function getInformation(array $addresses): iterable
    {
        foreach ($this->sortAddresses->sort($addresses) as $currency => $addresses) {
            yield from $this->getAddressesInformationLocator
                ->locate($currency)
                ->get($addresses)
            ;
        }
    }
}
