<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client\Builder;

use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\ClientInformationDtoInterface;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\RequestParametersDtoInterface;

class EtherscanUriBuilder extends UriBuilder
{
    public const API_KEY_PARAM = 'apiKey';

    /** @var string */
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @inheritDoc
     */
    public function build(ClientInformationDtoInterface $clientInformation, RequestParametersDtoInterface $requestParameters): string
    {
        $requestParameters->setQueryParameters(array_merge(
            $requestParameters->getQueryParameters() ?? [],
            [
                self::API_KEY_PARAM => $this->apiKey,
            ]
        ));

        return parent::build($clientInformation, $requestParameters);
    }
}
