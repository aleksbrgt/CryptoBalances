<?php

declare(strict_types=1);


namespace Aleksbrgt\Balances\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class KernelCommandContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var string */
    private $commandOutput;

    /** @var int */
    private $commandStatusCode;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @BeforeScenario
     */
    public function cleanUp(): void
    {
        $this->commandOutput = null;
        $this->commandStatusCode = null;
    }

    /**
     * @When I execute the command :commandName
     * @When I execute the command :commandName with parameters:
     *
     * @param string         $commandName
     * @param TableNode|null $parameters
     *
     * @throws Exception
     */
    public function iExecuteCommand(
        string $commandName,
        ?TableNode $parameters = null
    ): void {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $extraParameters = [];
        if (null !== $parameters) {
            foreach ($parameters->getTable() as $parameter) {
                $extraParameters[$parameter[0]] = $parameter[1];
            }
        }

        $input = new ArrayInput(array_merge(
            $extraParameters,
            ['command' => $commandName]
        ));

        $output = new BufferedOutput();
        $this->commandStatusCode = $application->run($input, $output);

        $this->commandOutput = $output->fetch();
    }

    /**
     * @Then the command should succeed
     */
    public function theCommandShouldSucceeds(): void
    {
        if ($this->commandStatusCode !== 0) {
            throw new RuntimeException(sprintf(
                'The command exit code is "%s"',
                $this->commandStatusCode
            ));
        }
    }

    /**
     * @Then display the command output
     */
    public function andDisplayTheCommandOutput(): void
    {
        print $this->commandOutput;
    }

    /**
     * @Then the command output should contain :element
     *
     * @param string $element
     */
    public function theOutputShouldContain(string $element): void
    {
        if (false !== strstr($this->commandOutput, $element)) {
            return;
        }

        throw new RuntimeException(sprintf(
            'Element "%s" was not found in command output: """%s%s%s"""',
            $element,
            PHP_EOL,
            $this->commandOutput,
            PHP_EOL
        ));
    }

}
