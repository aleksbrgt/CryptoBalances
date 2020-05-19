<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client\Abstraction;

use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\ClientInformationDtoInterface;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\RequestParametersDtoInterface;
use Psr\Http\Message\ResponseInterface;

interface ApiClientInterface
{
    /**
     * @param RequestParametersDtoInterface $requestDto
     *
     * @return ResponseInterface
     */
    public function execute(RequestParametersDtoInterface $requestDto): ResponseInterface;

    /**
     * @return ClientInformationDtoInterface
     */
    public function getClientInformation(): ClientInformationDtoInterface;
}
