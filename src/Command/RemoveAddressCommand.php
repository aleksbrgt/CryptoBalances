<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Command;

use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveAddressCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var AddressRepository */
    private $addressRepository;

    /** @var SymfonyStyle */
    private $io;

    /**
     * @param EntityManagerInterface $entityManager
     * @param AddressRepository $addressRepository
     */
    public function __construct(EntityManagerInterface $entityManager, AddressRepository $addressRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->addressRepository = $addressRepository;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName('address:remove')
            ->addOption('address', 'a', InputOption::VALUE_OPTIONAL, 'Address on chain')
        ;
    }

    /**
     * @inheritDoc
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Remove an address');

        $chainAddress = $input->getOption('address') ?? $this->askForAddress();

        $address = $this->addressRepository->findOneBy(['address' => $chainAddress]);

        if (!$address instanceof Address) {
            $this->io->error('Address not found');

            return 1;
        }

        $this->entityManager->remove($address);
        $this->entityManager->flush();

        $this->io->success('Address removed');

        return 0;
    }

    /**
     * @return string
     */
    private function askForAddress(): string
    {
        $question = (new Question('Address'))
            ->setAutocompleterValues(array_map(function (Address $address): string {
                return $address->getAddress();
            }, $this->addressRepository->findAll()))
        ;

        return $this->io->askQuestion($question);
    }
}
