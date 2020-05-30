<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client\Builder;

use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\ClientInformationDtoInterface;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\RequestParametersDtoInterface;
use InvalidArgumentException;

class UriBuilder
{
    private const REGEX = '({(\w+)})';

    /**
     * @param ClientInformationDtoInterface $clientInformation
     * @param RequestParametersDtoInterface $requestParameters
     *
     * @return string
     */
    public function build(
        ClientInformationDtoInterface $clientInformation,
        RequestParametersDtoInterface $requestParameters
    ): string {
        $uriTemplate = $clientInformation->getUriTemplate();
        $pathParameters = $requestParameters->getPathParameters() ?? [];
        $queryParameters = $requestParameters->getQueryParameters() ?? [];

        if (0 !== count($pathParameters)) {
            $uriTemplate = $this->addPathParametersToUri($uriTemplate, $pathParameters);
        }

        if (0 !== count($queryParameters)) {
            $joinChar =  '?';

            $query = parse_url($uriTemplate)['query'] ?? null;
            if (null !== $query) {
                $joinChar = '&';
            }

            $uriTemplate = sprintf(
                '%s%s%s',
                $uriTemplate,
                $joinChar,
                http_build_query($queryParameters)
            );
        }

        return $uriTemplate;
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
