<?php
declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Locator;

use Aleksbrgt\Balances\Service\ApiIntegration\Abstraction\GetCurrencyPriceInterface;
use RuntimeException;

class GetCurrencyPriceLocator
{
    /** @var iterable|GetCurrencyPriceInterface[] */
    private $services;

    /**
     * @param iterable $services
     */
    public function __construct(iterable $services)
    {
        $this->services = $services;
    }

    /**
     * @return GetCurrencyPriceInterface
     */
    public function locate(): GetCurrencyPriceInterface
    {
        foreach ($this->services as $service) {
            if ($service->supports()) {
                return $service;
            }
        }

        throw new RuntimeException('No service found to get currency prices');
    }
}
