<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Command;


use Aleksbrgt\Balances\Entity\Address;
use Aleksbrgt\Balances\Enum\AddressCurrencyEnum;
use Aleksbrgt\Balances\Service\Address\AddressExists;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddAddressCommand extends Command
{
    /** @var AddressExists */
    private $addressExists;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SymfonyStyle */
    private $io;

    public function __construct(
        AddressExists $addressExists,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();

        $this->addressExists = $addressExists;
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    protected function configure(): void
    {
        $this
            ->setName('address:add')
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
        $this->io->title('Add an address');

        $currency = $this->io->choice('Currency', AddressCurrencyEnum::VALUES);
        $blockchainAddress = $this->askForAddress();

        $address = (new Address())
            ->setCurrency($currency)
            ->setAddress($blockchainAddress)
        ;

        if ($this->addressExists->exists($address)) {
            $this->io->error('Address already exists');

            return 0;
        }

        $this->entityManager->persist($address);
        $this->entityManager->flush();

        $this->io->success('Address added');

        return 0;
    }

    /**
     * @return string
     */
    private function askForAddress(): string
    {
        $question = new Question('Address');
        $question->setValidator(function ($answer): string {
            if (null === $answer || '' === $answer) {
                throw new InvalidArgumentException('The address should not be empty');
            }

            return $answer;
        });

        return $this->io->askQuestion($question);
    }
}
