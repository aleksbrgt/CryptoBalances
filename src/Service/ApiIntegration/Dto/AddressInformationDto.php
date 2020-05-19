<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Dto;

class AddressInformationDto
{
    /** @var string */
    private $balance;

    /**
     * @return string
     */
    public function getBalance(): string
    {
        return $this->balance;
    }

    /**
     * @param string $balance
     *
     * @return AddressInformationDto
     */
    public function setBalance(string $balance): AddressInformationDto
    {
        $this->balance = $balance;

        return $this;
    }
}
