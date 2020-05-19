<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Locator;

use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Service\ApiIntegration\Abstraction\GetAddressInformationInterface;
use RuntimeException;

class GetAddressInformationLocator
{
    /** @var iterable|GetAddressInformationInterface[] */
    private $services;

    /**
     * @param iterable $services
     */
    public function __construct(iterable $services)
    {
        $this->services = $services;
    }

    /**
     * @param Address $address
     *
     * @return GetAddressInformationInterface
     */
    public function locate(Address $address): GetAddressInformationInterface
    {
        foreach ($this->services as $service) {
            if ($service->supports($address)) {
                return $service;
            }
        }

        throw new RuntimeException(sprintf(
            'Address for currency %s is not supported',
            $address->getCurrency()
        ));
    }
}
