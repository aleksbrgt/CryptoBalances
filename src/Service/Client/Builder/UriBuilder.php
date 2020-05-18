<?php

namespace Aleksbrgt\Balances\Service\Client\Builder;

use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\ClientInformationDtoInterface;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\RequestParametersDtoInterface;
use InvalidArgumentException;

class UriBuilder
{
    private const REGEX = '({(\w+)})';

    public function build(
        ClientInformationDtoInterface $clientInformation,
        RequestParametersDtoInterface $requestParameters
    ): string {
        $uri = $clientInformation->getUriTemplate();
        $pathParameters = $requestParameters->getPathParameters() ?? [];
        $queryParameters = $requestParameters->getQueryParameters() ?? [];

        if (0 !== count($pathParameters)) {
            $uri = $this->addPathParametersToUri($uri, $pathParameters);
        }

        if (0 !== count($queryParameters)) {
            $uri = sprintf('%s?%s', $uri, http_build_query($queryParameters));
        }

        return $uri;
    }

    /**
     * @param string $templateUri
     * @param array $pathParameters
     *
     * @return string
     */
    private function addPathParametersToUri(string $templateUri, array $pathParameters): string
    {
        return preg_replace_callback(self::REGEX, function (array $matches) use ($templateUri, $pathParameters): string {
            $parameter = $pathParameters[$matches[1]] ?? null;

            if (null === $parameter) {
                throw new InvalidArgumentException(sprintf(
                    'Template uri "%" has parameter "%s", but it was not provided in the path parameters',
                    $templateUri,
                    $parameter
                ));
            }

            return $parameter;
        }, $templateUri);
    }
}