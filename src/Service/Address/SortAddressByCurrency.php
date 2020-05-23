<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Service\Address;

use Aleksbrgt\Balances\Entity\Address;

class SortAddressByCurrency
{
    /**
     * @param Address[] $addresses
     *
     * @return array
     */
    public function sort(array $addresses): array
    {
        $currencies = [];

        foreach ($addresses as $address) {
            if (!array_key_exists($address->getCurrency(), $currencies)) {
                $currencies[$address->getCurrency()] = [];
            }

            $currencies[$address->getCurrency()][] = $address->getAddress();
        }

        return $currencies;
    }
}
