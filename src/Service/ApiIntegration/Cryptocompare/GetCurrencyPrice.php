<?php
declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Cryptocompare;


use Aleksbrgt\Balances\Service\ApiIntegration\Abstraction\GetCurrencyPriceInterface;
use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\DTO\RequestParametersDto;

class GetCurrencyPrice implements GetCurrencyPriceInterface
{
    /** @var ApiClientInterface */
    private $client;

    public function __construct(ApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function supports(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function get(): array
    {
        $response = $this->client->execute(
            (new RequestParametersDto())
                ->setQueryParameters([
                    'fsyms' => 'BTC,ETH',
                    'tsyms' => 'EUR',
                ])
        );

        $data = json_decode($response->getBody()->getContents(), true);

        $symbolPrices = [];
        foreach ($data as $fromSymbol => $prices) {
            foreach ($prices as $toSymbol => $price) {
                $symbolPrices[$fromSymbol] = (string) $price;
            }
        }

        return $symbolPrices;
    }
}