<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Aleksbrgt\Balances\Enum\AddressCurrencyEnum;

/**
 * @ORM\Entity()
 */
class Address
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid", unique=true)
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="address_currency")
     *
     * @Assert\NotBlank()
     * @Assert\Choice(choices=AddressCurrencyEnum::VALUES, message="Invalid currency")
     */
    private $currency;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Address
     */
    public function setId(string $id): Address
    {
        $this->id = $id;
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
     * @return Address
     */
    public function setCurrency(string $currency): Address
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return Address
     */
    public function setAddress(string $address): Address
    {
        $this->address = $address;
        return $this;
    }
}
