<?php


namespace Aleksbrgt\Balances\Service\Client\Factory;


use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\ApiClient;
use Aleksbrgt\Balances\Service\Client\Builder\UriBuilder;
use Aleksbrgt\Balances\Service\Client\DTO\ClientInformationDto;
use GuzzleHttp\ClientInterface;

class CryptocompareClientFactory
{
    /** @var ClientInterface */
    private $cryptocompareClient;

    /** @var UriBuilder */
    private $uriBuilder;

    /**
     * @param ClientInterface $cryptocompareClient
     * @param UriBuilder $uriBuilder
     */
    public function __construct(ClientInterface $cryptocompareClient, UriBuilder $uriBuilder)
    {
        $this->cryptocompareClient = $cryptocompareClient;
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
            clone $this->cryptocompareClient,
            (new ClientInformationDto())
                ->setMethod($method)
                ->setUriTemplate($uriTemplate),
            clone $this->uriBuilder
        );
    }
}