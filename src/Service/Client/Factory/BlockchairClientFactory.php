<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client\Factory;

use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\ApiClient;
use Aleksbrgt\Balances\Service\Client\Builder\UriBuilder;
use Aleksbrgt\Balances\Service\Client\DTO\ClientInformationDto;
use GuzzleHttp\ClientInterface;

class BlockchairClientFactory
{
    /** @var ClientInterface */
    private $client;

    /** @var UriBuilder */
    private $uriBuilder;

    /**
     * @param ClientInterface $client
     * @param UriBuilder $uriBuilder
     */
    public function __construct(ClientInterface $client, UriBuilder $uriBuilder)
    {
        $this->client = $client;
        $this->uriBuilder = $uriBuilder;
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
