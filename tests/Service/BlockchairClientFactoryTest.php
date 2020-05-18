<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Test\Service\Client;

use Aleksbrgt\Balances\Service\Client\BlockchairClientFactory;
use Aleksbrgt\Balances\Service\Client\Builder\UriBuilder;
use Aleksbrgt\Balances\Service\Client\DTO\ClientInformationDto;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;

class BlockchairClientFactoryTest extends TestCase
{
    /** @var BlockchairClientFactory */
    private $factory;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->factory = new BlockchairClientFactory(
            $this->createMock(ClientInterface::class),
            $this->createMock(UriBuilder::class)
        );
    }

    /**
     * @dataProvider createProvider
     *
     * @param string $method
     * @param string $uriTemplate
     */
    public function testCreate(
        string $method,
        string $uriTemplate
    ): void {
        $client = $this->factory->create($method, $uriTemplate);

        $this->assertEquals(
            (new ClientInformationDto())
                ->setMethod($method)
                ->setUriTemplate($uriTemplate),
            $client->getClientInformation()
        );
    }

    public function createProvider(): array
    {
        return [
            [
                'method' => 'POST',
                'uriTemplate' => 'foo/bar',
            ],
            [
                'method' => 'GET',
                'uriTemplate' => 'foo/bar/baz',
            ],
        ];
    }
}