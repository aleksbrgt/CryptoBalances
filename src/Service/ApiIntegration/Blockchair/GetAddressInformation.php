<?php
declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Blockchair;


use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Enum\AddressCurrencyEnum;
use Aleksbrgt\Balances\Service\ApiIntegration\Abstraction\GetAddressInformationInterface;
use Aleksbrgt\Balances\Service\ApiIntegration\Dto\AddressInformationDto;
use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\DTO\RequestParametersDto;

class GetAddressInformation implements GetAddressInformationInterface
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
    public function supports(Address $address): bool
    {
        return AddressCurrencyEnum::BTC === $address->getCurrency();
    }

    public function get(Address $address): AddressInformationDto
    {
        $response = $this->client->execute(
            (new RequestParametersDto())
                ->setPathParameters([
                    'address' => $address->getAddress(),
                ])
        );

        $data = json_decode($response->getBody()->getContents(), true);

        $satoshiBalance = (string) $data['data'][$address->getAddress()]['address']['balance'];

        // The balance is recovered in satoshi, is has to divided by 10^8 to get the BTC value
        $btcBalance = bcdiv($satoshiBalance, '100000000', 8);

        return (new AddressInformationDto())
            ->setBalance($btcBalance)
        ;
    }

}