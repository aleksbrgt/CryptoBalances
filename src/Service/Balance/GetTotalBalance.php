<?php
declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Balance;


use Aleksbrgt\Balances\Repository\AddressRepository;
use Aleksbrgt\Balances\Service\ApiIntegration\Locator\GetAddressInformationLocator;
use Aleksbrgt\Balances\Service\ApiIntegration\Locator\GetCurrencyPriceLocator;

class GetTotalBalance
{
    /** @var AddressRepository */
    private $addressRepository;

    /** @var GetAddressInformationLocator */
    private $getAddressInformationLocator;

    /** @var GetCurrencyPriceLocator */
    private $getCurrencyPriceLocator;

    /**
     * @param AddressRepository $addressRepository
     * @param GetAddressInformationLocator $getAddressInformationLocator
     * @param GetCurrencyPriceLocator $getCurrencyPriceLocator
     */
    public function __construct(
        AddressRepository $addressRepository,
        GetAddressInformationLocator $getAddressInformationLocator,
        GetCurrencyPriceLocator $getCurrencyPriceLocator
    ) {
        $this->addressRepository = $addressRepository;
        $this->getAddressInformationLocator = $getAddressInformationLocator;
        $this->getCurrencyPriceLocator = $getCurrencyPriceLocator;
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
        $prices = $this->getCurrencyPriceLocator->locate()->get();

        $fiatTotal = '0.00';

        foreach ($this->addressRepository->findAll() as $address) {
            $information = $this->getAddressInformationLocator->locate($address)->get($address);

            $fiatAmount = bcmul($information->getBalance(), $prices[$address->getCurrency()], 2);

            $balances['addresses'][] = [
                'address' => $address->getAddress(),
                'currency' => $address->getCurrency(),
                'balance' => $information->getBalance(),
                'balance_fiat' => $fiatAmount,
            ];

            $fiatTotal = bcadd($fiatTotal, $fiatAmount, 2);
        }

        $balances['total'] = $fiatTotal;

        return $balances;
    }
}