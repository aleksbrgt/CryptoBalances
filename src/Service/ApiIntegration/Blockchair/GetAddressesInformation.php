<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Blockchair;

use Aleksbrgt\Balances\Enum\AddressCurrencyEnum;
use Aleksbrgt\Balances\Service\ApiIntegration\Abstraction\GetAddressesInformationInterface;
use Aleksbrgt\Balances\Service\ApiIntegration\Dto\AddressInformationDto;
use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\DTO\RequestParametersDto;
use GuzzleHttp\Exception\BadResponseException;
use Traversable;

class GetAddressesInformation implements GetAddressesInformationInterface
{
    /** @var ApiClientInterface */
    private $client;

    /**
     * @param ApiClientInterface $client
     */
    public function __construct(ApiClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function supports(string $currency): bool
    {
        return AddressCurrencyEnum::BTC === $currency;
    }

    /**
     * @inheritDoc
     */
    public function get(array $addresses): Traversable
    {
        try {
            $response = $this->client->execute(
                (new RequestParametersDto())
                    ->setPathParameters([
                        'addresses' => join(',', $addresses),
                    ])
            );
        } catch (BadResponseException $exception) {
            yield from $this->notFoundAddresses($addresses);

            return;
        }

        $data = json_decode($response->getBody()->getContents(), true)['data']['addresses'] ?? [];

        foreach ($addresses as $address) {
            $information = $data[$address] ?? [];

            $satoshiBalance = $information['balance'] ?? null;
            $btcBalance = null;

            if (null !== $satoshiBalance) {
                // The balance is recovered in satoshi, is has to divided by 10^8 to get the BTC value
                $btcBalance = bcdiv((string) $satoshiBalance, '100000000', 8);
            }

            yield (new AddressInformationDto())
                ->setCurrency(AddressCurrencyEnum::BTC)
                ->setAddress($address)
                ->setBalance($btcBalance)
            ;
        }
    }

    /**
     * @param array $addresses
     *
     * @return iterable|AddressInformationDto[]
     */
    private function notFoundAddresses(array $addresses): iterable
    {
        foreach ($addresses as $address) {
            yield (new AddressInformationDto())
                ->setAddress($address)
                ->setCurrency(AddressCurrencyEnum::BTC)
            ;
        }
    }
}
