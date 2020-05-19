<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Service\Client\DTO\Abstraction;

interface RequestParametersDtoInterface
{
    /**
     * @return array|null
     */
    public function getHeaders(): ?array;

    /**
     * @return array|null
     */
    public function getPathParameters(): ?array;

    /**
     * @return array|null
     */
    public function getQueryParameters(): ?array;

    /**
     * @return array|null
     */
    public function getBodyParameters(): ?array;
}
