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
    private $blockchairClient;

    /** @var UriBuilder */
    private $uriBuilder;

    /**
     * @param ClientInterface $blockchairClient
     * @param UriBuilder $uriBuilder
     */
    public function __construct(ClientInterface $blockchairClient, UriBuilder $uriBuilder)
    {
        $this->blockchairClient = $blockchairClient;
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
            clone $this->blockchairClient,
            (new ClientInformationDto())
                ->setMethod($method)
                ->setUriTemplate($uriTemplate),
            clone $this->uriBuilder
        );
    }
}
