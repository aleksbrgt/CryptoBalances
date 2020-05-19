<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client;

use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use Aleksbrgt\Balances\Service\Client\Builder\UriBuilder;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\ClientInformationDtoInterface;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\RequestParametersDtoInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class ApiClient implements ApiClientInterface
{
    /** @var ClientInterface */
    private $client;

    /** @var ClientInformationDtoInterface */
    private $clientInformation;

    /** @var UriBuilder */
    private $uriBuilder;

    public function __construct(
        ClientInterface $client,
        ClientInformationDtoInterface $clientInformation,
        UriBuilder $uriBuilder
    ) {
        $this->client = $client;
        $this->clientInformation = $clientInformation;
        $this->uriBuilder = $uriBuilder;
    }

    /**
     * @param RequestParametersDtoInterface $requestDto
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function execute(RequestParametersDtoInterface $requestDto): ResponseInterface
    {
        return $this->client->send(
            new Request(
                $this->clientInformation->getMethod(),
                $this->uriBuilder->build(
                    $this->clientInformation,
                    $requestDto
                )
            ),
            [
                RequestOptions::JSON => $requestDto->getBodyParameters() ?? [],
            ]
        );
    }

    /**
     * @return ClientInformationDtoInterface
     */
    public function getClientInformation(): ClientInformationDtoInterface
    {
        return $this->clientInformation;
    }
}
