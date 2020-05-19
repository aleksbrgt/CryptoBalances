<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client\Factory;

use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\ApiClient;
use Aleksbrgt\Balances\Service\Client\Builder\EtherscanUriBuilder;
use Aleksbrgt\Balances\Service\Client\DTO\ClientInformationDto;
use GuzzleHttp\ClientInterface;

class EtherscanClientFactory
{
    /** @var ClientInterface */
    private $etherscanClient;

    /** @var EtherscanUriBuilder */
    private $etherscanUriBuilder;

    public function __construct(
        ClientInterface $etherscanClient,
        EtherscanUriBuilder $etherscanUriBuilder
    ) {
        $this->etherscanClient = $etherscanClient;
        $this->etherscanUriBuilder = $etherscanUriBuilder;
    }

    /**
     * @param string $method
     * @param string $uriTemplate
     *
     * @return ApiClientInterface
     */
    public function create(
        string $method,
        string $uriTemplate
    ): ApiClientInterface {
        return new ApiClient(
            clone $this->etherscanClient,
            (new ClientInformationDto())
                ->setMethod($method)
                ->setUriTemplate($uriTemplate),
            clone $this->etherscanUriBuilder
        );
    }
}
