<?php


namespace Aleksbrgt\Balances\Service\Client\DTO;


use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\ClientInformationDtoInterface;

class ClientInformationDto implements ClientInformationDtoInterface
{
    /** @var string */
    private $method;

    /** @var string */
    private $uriTemplate;

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
     * @return ClientInformationDto
     */
    public function setMethod(string $method): ClientInformationDto
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getUriTemplate(): string
    {
        return $this->uriTemplate;
    }

    /**
     * @param string $uriTemplate
     *
     * @return ClientInformationDto
     */
    public function setUriTemplate(string $uriTemplate): ClientInformationDto
    {
        $this->uriTemplate = $uriTemplate;

        return $this;
    }
}