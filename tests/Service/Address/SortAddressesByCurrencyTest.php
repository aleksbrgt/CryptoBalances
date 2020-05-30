<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Tests\Service\Address;

use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Service\Address\SortAddressByCurrency;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SortAddressesByCurrencyTest extends TestCase
{
    /** @var SortAddressByCurrency */
    private $sortAddressesByCurrency;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->sortAddressesByCurrency = new SortAddressByCurrency();
    }

    /**
     * @dataProvider sortProvider
     *
     * @param Address[] $addresses
     *
     * @param array $expected
     */
    public function testSort(array $addresses, array $expected): void
    {
        $this->assertEquals(
            $expected,
            $this->sortAddressesByCurrency->sort($addresses)
        );
    }

    /**
     * @return array
     */
    public function sortProvider(): array
    {
        return [
            [
                'addresses' => [],
                'expected' => [],
            ],
            [
                'addresses' => [
                    $this->getPreparedAddress('btc', 'addr1'),
                    $this->getPreparedAddress('eth', 'addr2'),
                ],
                'expected' => [
                    'btc' => ['addr1'],
                    'eth' => ['addr2'],
                ],
            ],
            [
                'addresses' => [
                    $this->getPreparedAddress('btc', 'addr1'),
                    $this->getPreparedAddress('eth', 'addr2'),
                    $this->getPreparedAddress('ltc', 'addr3'),
                    $this->getPreparedAddress('eth', 'addr4'),
                    $this->getPreparedAddress('btc', 'addr5'),
                    $this->getPreparedAddress('eth', 'addr6'),
                ],
                'expected' => [
                    'btc' => ['addr1', 'addr5'],
                    'eth' => ['addr2', 'addr4', 'addr6'],
                    'ltc' => ['addr3'],
                ],
            ],
        ];
    }

    /**
     * @param string $currency
     * @param string $address
     *
     * @return Address|MockObject
     */
    private function getPreparedAddress(
        string $currency,
        string $address
    ): Address {
        return $this->createConfiguredMock(
            Address::class,
            [
                'getCurrency' => $currency,
                'getAddress' => $address,
            ]
        );
    }
}
