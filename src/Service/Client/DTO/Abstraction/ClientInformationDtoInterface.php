<?php


namespace Aleksbrgt\Balances\Service\Client\DTO\Abstraction;


interface ClientInformationDtoInterface
{
    /**
     * @return string
     */
    public function getMethod(): string;

    /**
     * @return string
     */
    public function getUriTemplate(): string;
}