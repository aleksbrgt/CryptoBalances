<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Test\Service\Address;

use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Repository\AddressRepository;
use Aleksbrgt\Balances\Service\Address\AddressExists;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class AddressExistsTest extends TestCase
{
    /** @var AddressExists */
    private $addressExists;

    /** @var AddressRepository|MockObject */
    private $addressRepository;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->addressRepository = $this->createMock(AddressRepository::class);
        $this->addressExists = new AddressExists($this->addressRepository);
    }

    /**
     * @dataProvider existsProvider
     *
     * @param bool $addressExists
     * @param bool $expected
     */
    public function testExists(bool $addressExists, bool $expected): void
    {
        $this->prepareAddressRepository($addressExists);

        $this->assertEquals(
            $expected,
            $this->addressExists->exists($this->getPreparedAddress())
        );
    }

    /**
     * @return array
     */
    public function existsProvider(): array
    {
        return [
            [
                'addressExists' => true,
                'expected' => true,
            ],
            [
                'addressExists' => false,
                'expected' => false,
            ],
        ];
    }

    /**
     * @return Address|MockObject
     */
    private function getPreparedAddress(): Address
    {
        return $this->createConfiguredMock(
            Address::class,
            [
                'getAddress' => 'address',
                'getCurrency' => 'ETH',
            ]
        );
    }

    /**
     * @param bool $isAddressFound
     */
    private function prepareAddressRepository(bool $isAddressFound): void
    {
        $this->addressRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with([
                'address' => 'address',
                'currency' => 'ETH',
            ])
            ->willReturn($isAddressFound ? new Address() : null)
        ;
    }
}