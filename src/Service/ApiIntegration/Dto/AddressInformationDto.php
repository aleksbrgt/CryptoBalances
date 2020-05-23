<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Dto;

class AddressInformationDto
{
    /** @var string */
    private $address;

    /** @var string */
    private $currency;

    /** @var string|null */
    private $balance;

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return AddressInformationDto
     */
    public function setAddress(string $address): AddressInformationDto
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     *
     * @return AddressInformationDto
     */
    public function setCurrency(string $currency): AddressInformationDto
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBalance(): ?string
    {
        return $this->balance;
    }

    /**
     * @param string|null $balance
     *
     * @return AddressInformationDto
     */
    public function setBalance(?string $balance): AddressInformationDto
    {
        $this->balance = $balance;
        return $this;
    }
}
