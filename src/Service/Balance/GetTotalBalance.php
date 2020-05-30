<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Balance;

use Aleksbrgt\Balances\Repository\AddressRepository;
use Aleksbrgt\Balances\Service\ApiIntegration\Dto\AddressInformationDto;
use Aleksbrgt\Balances\Service\ApiIntegration\GetAddressesInformation;
use Aleksbrgt\Balances\Service\ApiIntegration\Locator\GetCurrencyPriceLocator;
use GuzzleHttp\Exception\BadResponseException;

class GetTotalBalance
{
    /** @var AddressRepository */
    private $addressRepository;

    /** @var GetCurrencyPriceLocator */
    private $getCurrencyPriceLocator;

    /** @var GetAddressesInformation */
    private $getAddressesInformation;

    /**
     * @param AddressRepository $addressRepository
     * @param GetCurrencyPriceLocator $getCurrencyPriceLocator
     * @param GetAddressesInformation $getAddressesInformation
     */
    public function __construct(AddressRepository $addressRepository, GetCurrencyPriceLocator $getCurrencyPriceLocator, GetAddressesInformation $getAddressesInformation)
    {
        $this->addressRepository = $addressRepository;
        $this->getCurrencyPriceLocator = $getCurrencyPriceLocator;
        $this->getAddressesInformation = $getAddressesInformation;
    }

    /**
     * @return array
     */
    public function getTotalBalances(): array
    {
        $balances = [
            'addresses' => [],
            'totals' => [],
        ];

        $prices = [];

        try {
            $prices = $this->getCurrencyPriceLocator->locate()->get();
        } catch (BadResponseException $exception) {

        }

        $fiatTotal = '0.00';

       $addresses = $this->addressRepository->findAll();

       foreach ($this->getAddressesInformation->getInformation($addresses) as $information) {
           $fiatAmount = $this->getFiatAmount($information, $prices);

           $balances['addresses'][] = [
               'address' => $information->getAddress(),
               'currency' => $information->getCurrency(),
               'balance' => $information->getBalance(),
               'balance_fiat' => $fiatAmount,
           ];

           $fiatTotal = bcadd($fiatTotal, $fiatAmount ?? '0', 2);
       }

        $balances['total'] = $fiatTotal;

        return $balances;
    }

    /**
     * @param AddressInformationDto $information
     * @param array $prices
     *
     * @return string|null
     */
    private function getFiatAmount(AddressInformationDto $information, array $prices): ?string
    {
        if (null === $information->getBalance()) {
            return null;
        }

        $conversionRate = $prices[$information->getCurrency()] ?? null;

        if (null === $conversionRate) {
            return null;
        }

        return bcmul($information->getBalance(), $conversionRate, 2);
    }
}
