<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Command;


use Aleksbrgt\Balances\Repository\AddressRepository;
use Aleksbrgt\Balances\Service\Address\Blockchair\GetAddressInformation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetBalanceCommand extends Command
{
    /** @var GetAddressInformation */
    private $getAddressInformation;

    /** @var AddressRepository */
    private $addressRepository;

    /** @var SymfonyStyle */
    private $io;

    public function __construct(
        GetAddressInformation $getAddressInformation,
        AddressRepository $addressRepository
    )
    {
        parent::__construct();

        $this->getAddressInformation = $getAddressInformation;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('balance');
    }

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io =  new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table
            ->setStyle('borderless')
            ->setHeaderTitle('Portfolio')
            ->setHeaders([
            'address',
            'currency',
            'amount',
            'value',
        ]);

        $usdTotal = '0';
        foreach ($this->addressRepository->findAll() as $address) {
            $data = $this->getAddressInformation->get($address)['data'][$address->getAddress()];

            $table->addRow([
                $address->getAddress(),
                $address->getCurrency(),
                $data['address']['balance'],
                '$' . $data['address']['balance_usd'],
            ]);

            $usdTotal = bcadd($usdTotal, (string) $data['address']['balance_usd'], 11);
        }

        $table->addRow(new TableSeparator());
        $table->addRow([
            new TableCell('Total', ['colspan' => 3]),
            '$' . $usdTotal,
        ]);

        $table->render();

        return 0;
    }
}