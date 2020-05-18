<?php


namespace Aleksbrgt\Balances\Service\Address\Blockchair;


use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Enum\AddressCurrencyEnum;
use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\DTO\RequestParametersDto;
use RuntimeException;

class GetAddressInformation
{
    /** @var ApiClientInterface */
    private $client;

    private const MAPPING = [
        AddressCurrencyEnum::BTC => 'bitcoin',
        AddressCurrencyEnum::ETH => 'ethereum',
    ];

    /**
     * @param ApiClientInterface $client
     */
    public function __construct(ApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param Address $address
     *
     * @return array
     */
    public function get(Address $address): array
    {
        $currencyName = self::MAPPING[$address->getCurrency()] ?? null;

        if (null === $currencyName) {
            throw new RuntimeException(sprintf(
                'Currency address "%s" is not supported',
                $address->getCurrency()
            ));
        }

        $response = $this->client->execute(
            (new RequestParametersDto())
                ->setPathParameters([
                    'currency' => $currencyName,
                    'address' => $address->getAddress(),
                ])
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}