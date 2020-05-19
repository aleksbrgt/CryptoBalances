<?php
declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\ApiIntegration\Etherscan;

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
    public function supports(Address $address): bool
    {
        return AddressCurrencyEnum::ETH === $address->getCurrency();
    }

    /**
     * @param Address $address
     *
     * @return AddressInformationDto
     */
    public function get(Address $address): AddressInformationDto
    {
        $response = $this->client->execute(
            (new RequestParametersDto())
                ->setPathParameters([
                    'address' => $address->getAddress(),
                ])
        );

        $weiBalance = json_decode($response->getBody()->getContents(), true)['result'];

        // The balance is recovered in wei, it has to be divided by 10^18 to get the ether value
        $etherBalance = bcdiv((string) $weiBalance, '1000000000000000000', 18);

        return (new AddressInformationDto())->setBalance($etherBalance);
    }
}