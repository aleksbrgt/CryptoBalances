<?php


namespace Aleksbrgt\Balances\Service\Address;


use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Repository\AddressRepository;

class AddressExists
{
    /** @var AddressRepository */
    private $addressRepository;

    /**
     * @param AddressRepository $addressRepository
     */
    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepository = $addressRepository;
    }

    /**
     * @param Address $address
     *
     * @return bool
     */
    public function exists(Address $address): bool
    {
        $existingAddress = $this->addressRepository->findOneBy([
            'address' => $address->getAddress(),
            'currency' => $address->getCurrency()
        ]);

        return $existingAddress instanceof Address;
    }
}