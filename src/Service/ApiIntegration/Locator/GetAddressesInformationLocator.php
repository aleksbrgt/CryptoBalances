<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Locator;

use Aleksbrgt\Balances\Service\ApiIntegration\Abstraction\GetAddressesInformationInterface;
use RuntimeException;

class GetAddressesInformationLocator
{
    /** @var iterable|GetAddressesInformationInterface[] */
    private $services;

    /**
     * @param iterable $services
     */
    public function __construct(iterable $services)
    {
        $this->services = $services;
    }

    /**
     * @param string $currency
     *
     * @return GetAddressesInformationInterface
     */
    public function locate(string $currency): GetAddressesInformationInterface
    {
        foreach ($this->services as $service) {
            if ($service->supports($currency)) {
                return $service;
            }
        }

        throw new RuntimeException(sprintf(
            'Address for currency %s is not supported',
            $currency
        ));
    }
}
