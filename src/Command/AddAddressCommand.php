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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddAddressCommand extends Command
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var AddressExists */
    private $addressExists;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SymfonyStyle */
    private $io;

    /** @var Address */
    private $address;

    /**
     * @param ValidatorInterface $validator
     * @param AddressExists $addressExists
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ValidatorInterface $validator,
        AddressExists $addressExists,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();

        $this->validator = $validator;
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
            ->addOption('currency', 'c', InputOption::VALUE_OPTIONAL, 'Address currency')
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
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $this->address = (new Address())
            ->setCurrency($input->getOption('currency') ?? $this->io->choice('Currency', AddressCurrencyEnum::VALUES))
            ->setAddress($input->getOption('address') ?? $this->askForAddress())
        ;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Add an address');

        $violations = $this->validator->validate($this->address);
        if (0 < $violations->count()) {
            $this->displayViolationErrors($violations);

            return 0;
        }

        if ($this->addressExists->exists($this->address)) {
            $this->io->error('Address already exists');

            return 0;
        }

        $this->entityManager->persist($this->address);
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

    /**
     * @param ConstraintViolationListInterface $violations
     */
    private function displayViolationErrors(ConstraintViolationListInterface $violations): void
    {
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $this->io->error(sprintf(
                'Error on "%s": %s',
                $violation->getPropertyPath(),
                $violation->getMessage()
            ));
        }
    }
}
