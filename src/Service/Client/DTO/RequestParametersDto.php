<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client\DTO;

use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\RequestParametersDtoInterface;

class RequestParametersDto implements RequestParametersDtoInterface
{
    /** @var string */
    private $method;

    /** @var array|null */
    private $headers;

    /** @var array|null */
    private $pathParameters;

    /** @var array|null */
    private $queryParameters;

    /** @var array|null */
    private $bodyParameters;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return RequestParametersDto
     */
    public function setMethod(string $method): RequestParametersDto
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param array|null $headers
     *
     * @return RequestParametersDto
     */
    public function setHeaders(?array $headers): RequestParametersDto
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getPathParameters(): ?array
    {
        return $this->pathParameters;
    }

    /**
     * @param array|null $pathParameters
     *
     * @return RequestParametersDto
     */
    public function setPathParameters(?array $pathParameters): RequestParametersDto
    {
        $this->pathParameters = $pathParameters;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParameters(): ?array
    {
        return $this->queryParameters;
    }

    /**
     * @param array|null $queryParameters
     *
     * @return RequestParametersDto
     */
    public function setQueryParameters(?array $queryParameters): RequestParametersDto
    {
        $this->queryParameters = $queryParameters;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getBodyParameters(): ?array
    {
        return $this->bodyParameters;
    }

    /**
     * @param array|null $bodyParameters
     *
     * @return RequestParametersDto
     */
    public function setBodyParameters(?array $bodyParameters): RequestParametersDto
    {
        $this->bodyParameters = $bodyParameters;

        return $this;
    }
}
