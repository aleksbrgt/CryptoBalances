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
    private $client;

    /** @var EtherscanUriBuilder */
    private $uriBuilder;

    public function __construct(
        ClientInterface $client,
        EtherscanUriBuilder $uriBuiler
    ) {
        $this->client = $client;
        $this->uriBuilder = $uriBuiler;
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
            clone $this->client,
            (new ClientInformationDto())
                ->setMethod($method)
                ->setUriTemplate($uriTemplate),
            clone $this->uriBuilder
        );
    }
}
