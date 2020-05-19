<?php
declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Abstraction;


interface GetCurrencyPriceInterface
{
    /**
     * @return bool
     */
    public function supports(): bool;

    /**
     * @return array
     */
    public function get(): array;
}