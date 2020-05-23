<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Tests\Service\ApiIntegration\Blockchair;

use Aleksbrgt\Balances\Enum\AddressCurrencyEnum;
use Aleksbrgt\Balances\Service\ApiIntegration\Blockchair\GetAddressesInformation;
use Aleksbrgt\Balances\Service\ApiIntegration\Dto\AddressInformationDto;
use Aleksbrgt\Balances\Service\Client\Abstraction\ApiClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GetAddressInformationTest extends TestCase
{
    /** @var \Aleksbrgt\Balances\Service\ApiIntegration\Etherscan\GetAddressesInformation */
    private $getAddressInformation;

    /** @var ApiClientInterface|MockObject */
    private $apiClient;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->apiClient = $this->createMock(ApiClientInterface::class);
        $this->getAddressInformation = new GetAddressesInformation($this->apiClient);
    }

    /**
     * @dataProvider supportsProvider
     *
     * @param string $addressCurrency
     * @param bool $expected
     */
    public function testSupports(string $addressCurrency, bool $expected): void
    {
        $this->assertEquals(
            $expected,
            $this->getAddressInformation->supports($addressCurrency)
        );
    }

    /**
     * @return array
     */
    public function supportsProvider(): array
    {
        return [
            [
                'addressCurrency' => AddressCurrencyEnum::BTC,
                'expected' => true,
            ],
            [
                'addressCurrency' => AddressCurrencyEnum::ETH,
                'expected' => false,
            ]
        ];
    }

    public function testGetClientThrowsException(): void
    {
        $this->prepareClientThrowsException();

        $expected = [
            (new AddressInformationDto())
                ->setAddress('addr1')
                ->setCurrency('BTC')
                ->setBalance(null),
            (new AddressInformationDto())
                ->setAddress('addr2')
                ->setCurrency('BTC')
                ->setBalance(null),
        ];

        $this->assertEquals(
            $expected,
            iterator_to_array($this->getAddressInformation->get(['addr1', 'addr2']))
        );
    }

    /**
     * @dataProvider getProvider
     *
     * @param array $addresses
     * @param array $apiResponse
     * @param array $expected
     */
    public function testGet(
        array $addresses,
        array $apiResponse,
        array $expected
    ): void
    {
        $this->prepareClient($apiResponse);

        $this->assertEquals(
            $expected,
            iterator_to_array($this->getAddressInformation->get($addresses))
        );
    }

    /**
     * @return array
     */
    public function getProvider(): array
    {
        return [
            'none of the address are in the response' => [
                'addresses' => ['addr1', 'addr2'],
                'apiResponse' => ['data' => ['addresses' => []]],
                'expected' => [
                    (new AddressInformationDto())
                        ->setCurrency('BTC')
                        ->setAddress('addr1')
                        ->setBalance(null),
                    (new AddressInformationDto())
                        ->setCurrency('BTC')
                        ->setAddress('addr2')
                        ->setBalance(null),
                ],
            ],
            'result has more thant the requested addresses' => [
                'addresses' => ['addr1'],
                'apiResponse' => [
                    'data' => [
                        'addresses' => [
                            'addr1' => ['balance' => '0'],
                            'addr2' => ['balance' => '0'],
                        ],
                    ],
                ],
                'expected' => [
                    (new AddressInformationDto())
                        ->setCurrency('BTC')
                        ->setAddress('addr1')
                        ->setBalance('0.00000000'),
                ]
            ]
        ];
    }

    private function prepareClientThrowsException(): void
    {
        $this->apiClient
            ->method('execute')
            ->willThrowException(new BadResponseException(
                'Bad Request',
                $this->createMock(RequestInterface::class),
                $this->createMock(ResponseInterface::class)
            ));
    }

    /**
     * @param array $responseContent
     */
    private function prepareClient(array $responseContent): void
    {
        $response = $this->createConfiguredMock(
            ResponseInterface::class,
            [
                'getBody' => $this->createConfiguredMock(
                    StreamInterface::class,
                    [
                        'getContents' => json_encode($responseContent),
                    ]
                ),
            ]
        );

        $this->apiClient
            ->expects($this->once())
            ->method('execute')
            ->willReturn($response);
    }
}
