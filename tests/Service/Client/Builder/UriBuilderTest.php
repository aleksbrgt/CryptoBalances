<?php

namespace Aleksbrgt\Balances\Test\Service\Client\Builder;

use Aleksbrgt\Balances\Service\Client\Builder\UriBuilder;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\ClientInformationDtoInterface;
use Aleksbrgt\Balances\Service\Client\DTO\Abstraction\RequestParametersDtoInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UriBuilderTest extends TestCase
{
    /** @var UriBuilder */
    private $uriBuilder;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->uriBuilder = new UriBuilder();
    }

    /**
     * @dataProvider buildProvider
     *
     * @param string $uriTemplate
     * @param array|null $pathParameters
     * @param array|null $queryParameters
     * @param string $expected
     */
    public function testBuild(
        string $uriTemplate,
        ?array $pathParameters,
        ?array $queryParameters,
        string $expected
    ): void {
        $this->assertEquals(
            $expected,
            $this->uriBuilder->build(
                $this->getPreparedClientInformation($uriTemplate),
                $this->getPreparedRequestParameters(
                    $pathParameters,
                    $queryParameters
                )
            )
        );
    }

    public function buildProvider(): array
    {
        return [
            [
                'uriTemplate' => 'foo/bar',
                'pathParameters' => null,
                'queryParameters' => null,
                'expected' => 'foo/bar',
            ],
            [
                'uriTemplate' => 'foo/bar',
                'pathParameters' => [],
                'queryParameters' => [],
                'expected' => 'foo/bar',
            ],
            [
                'uriTemplate' => 'foo/bar',
                'pathParameters' => null,
                'queryParameters' => [
                    'param1' => 'foo',
                    'param2' => 'bar',
                    'param3' => [
                        'lorem',
                        'ipsum',
                        'dolor',
                    ],
                ],
                'expected' => 'foo/bar?param1=foo&param2=bar&param3%5B0%5D=lorem&param3%5B1%5D=ipsum&param3%5B2%5D=dolor',
            ],
            [
                'uriTemplate' => '{param1}/{param2}',
                'pathParameters' => [
                    'param1' => 'foo',
                    'param2' => 'bar',
                ],
                'queryParameters' => null,
                'expected' => 'foo/bar',
            ],
            [
                'uriTemplate' => 'route/to/endpoint?param1=foo&param2={param2}',
                'pathParameters' => [
                    'param2' => 'bar',
                ],
                'queryParameters' => [
                    'param3' => 'baz',
                ],
                'expected' => 'route/to/endpoint?param1=foo&param2=bar&param3=baz',
            ],
        ];
    }

    /**
     * @param string $uriTemplate
     *
     * @return ClientInformationDtoInterface|MockObject
     */
    private function getPreparedClientInformation(string $uriTemplate): ClientInformationDtoInterface
    {
        return $this->createConfiguredMock(
            ClientInformationDtoInterface::class,
            [
                'getUriTemplate' => $uriTemplate,
            ]
        );
    }

    /**
     * @param array|null $pathParameters
     * @param array|null $queryParameters
     *
     * @return RequestParametersDtoInterface|MockObject
     */
    private function getPreparedRequestParameters(
        ?array $pathParameters,
        ?array $queryParameters
    ): RequestParametersDtoInterface {
        return $this->createConfiguredMock(
            RequestParametersDtoInterface::class,
            [
                'getPathParameters' => $pathParameters,
                'getQueryParameters' => $queryParameters,
            ]
        );
    }
}