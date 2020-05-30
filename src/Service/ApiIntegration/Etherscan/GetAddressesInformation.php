<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Etherscan;

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
        return AddressCurrencyEnum::ETH === $currency;
    }

    /**
     * @param array $addresses
     *
     * @return Traversable|AddressInformationDto[]
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

        $rawData = json_decode($response->getBody()->getContents(), true)['result'];

        $data = [];
        foreach ($rawData as $element) {
            $data[$element['account']] = $element['balance'];
        }

        foreach ($addresses as $address) {
            $weiBalance = $data[$address] ?? null;
            $etherBalance = null;

            if (null !== $weiBalance) {
                // The balance is recovered in wei, it has to be divided by 10^18 to get the ether value
                $etherBalance = bcdiv((string) $weiBalance, '1000000000000000000', 18);
            }

            yield (new AddressInformationDto())
                ->setAddress($address)
                ->setCurrency(AddressCurrencyEnum::ETH)
                ->setBalance($etherBalance)
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
                ->setCurrency(AddressCurrencyEnum::ETH)
            ;
        }
    }
}
