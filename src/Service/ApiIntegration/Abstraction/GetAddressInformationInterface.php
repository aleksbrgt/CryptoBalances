<?php
declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Abstraction;


use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Service\ApiIntegration\Dto\AddressInformationDto;

interface GetAddressInformationInterface
{
    /**
     * @param Address $address
     *
     * @return bool
     */
    public function supports(Address $address): bool;

    /**
     * @param Address $address
     *
     * @return AddressInformationDto
     */
    public function get(Address $address): AddressInformationDto;
}