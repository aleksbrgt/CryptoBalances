<?php

declare(strict_types=1);

namespace Aleksbrgt\Balances\Command;

use Aleksbrgt\Balances\Service\Balance\GetTotalBalance;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetBalanceCommand extends Command
{
    /** @var GetTotalBalance */
    private $getTotalBalance;

    /**
     * @param GetTotalBalance $getTotalBalance
     */
    public function __construct(GetTotalBalance $getTotalBalance)
    {
        parent::__construct();

        $this->getTotalBalance = $getTotalBalance;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this->setName('balance');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $balances = $this->getTotalBalance->getTotalBalances();

        $table = new Table($output);
        $table
            ->setHeaderTitle('Balances')
            ->setHeaders(['address', 'currency', 'amount', 'EUR'])
        ;

        foreach ($balances['addresses'] as $address) {
            $table->addRow([
                $address['address'],
                $address['currency'],
                $address['balance'] ?? 'N/A',
                $address['balance_fiat'] ?? 'N/A',
            ]);
        }

        $table
            ->addRows([
                new TableSeparator(),
                [new TableCell('TOTAL', ['colspan' => 3]), $balances['total']]
            ])
            ->render()
        ;

        return 0;
    }
}
